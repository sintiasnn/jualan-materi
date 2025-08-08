FROM php:8.2-apache

# Install system dependencies
RUN apt-get update -y && apt-get install -y \
    openssl zip unzip git curl \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    gnupg

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring zip gd

# Update apache conf to point to application public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Update uploads config
RUN echo "file_uploads = On\n" \
         "memory_limit = 1024M\n" \
         "upload_max_filesize = 512M\n" \
         "post_max_size = 512M\n" \
         "max_execution_time = 1200\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

# Enable headers module
RUN a2enmod rewrite headers

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install

# Install PHP dependencies
RUN composer install

# Make symlink
RUN php artisan storage:link

# Install Node.js (LTS version)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs


# Serve Laravel app
#CMD php artisan serve --host=127.0.0.1 --port=8181

#serve node js
#CMD npm run dev --host=0.0.0.0 --port=3000

EXPOSE 8081
CMD ["apache2-foreground"]

