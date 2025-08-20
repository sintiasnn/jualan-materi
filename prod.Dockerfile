# =========================
# Stage 1 — Composer (deps)
# =========================
FROM composer:2 AS vendor
WORKDIR /app

# Copy manifest dulu biar cache efektif
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts

# Baru copy semua source dan optimize autoload
COPY . .
RUN composer dump-autoload -o

# =======================
# Stage 2 — Vite (assets)
# =======================
FROM node:20-alpine AS frontend
WORKDIR /app

# Pasang deps JS lalu build
COPY package.json package-lock.json* pnpm-lock.yaml* yarn.lock* ./
RUN if [ -f pnpm-lock.yaml ]; then npm i -g pnpm && pnpm i --frozen-lockfile; \
    elif [ -f yarn.lock ]; then yarn --frozen-lockfile; \
    elif [ -f package-lock.json ]; then npm ci; else npm i; fi

COPY . .
# Vite akan output ke public/build
RUN npm run build

# =========================================
# Stage 3 — Runtime (Apache + PHP Extensions)
# =========================================
FROM php:8.2-apache

# Ekstensi & modul yang umum dipakai Laravel
RUN apt-get update -y && apt-get install -y --no-install-recommends \
      libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libonig-dev libxml2-dev \
  && rm -rf /var/lib/apt/lists/* \
  && docker-php-ext-configure gd --with-jpeg --with-freetype \
  && docker-php-ext-install gd pdo_mysql mbstring zip \
  && a2enmod rewrite headers

# Set DocumentRoot -> /var/www/public
ENV APACHE_DOCUMENT_ROOT=/var/www/public
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf \
 && sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# PHP tunables (upload limit dll)
RUN printf "%s\n" \
  "file_uploads=On" \
  "memory_limit=1024M" \
  "upload_max_filesize=64M" \
  "post_max_size=64M" \
  "max_execution_time=120" \
  > /usr/local/etc/php/conf.d/uploads.ini \
 && printf "%s\n" \
  "opcache.enable=1" \
  "opcache.enable_cli=1" \
  "opcache.validate_timestamps=0" \
  "opcache.max_accelerated_files=20000" \
  "opcache.memory_consumption=128" \
  "opcache.interned_strings_buffer=16" \
  > /usr/local/etc/php/conf.d/opcache-recommended.ini

WORKDIR /var/www

# Copy app (sudah termasuk vendor) dari stage Composer
COPY --from=vendor /app /var/www

# Copy hasil Vite ke public/build
COPY --from=frontend /app/public/build /var/www/public/build

# Jaga permission & file log agar www-data bisa tulis
RUN mkdir -p storage/logs \
 && touch storage/logs/laravel.log \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R ug+rwX storage bootstrap/cache \
 && php artisan storage:link || true

EXPOSE 80
CMD ["apache2-foreground"]
