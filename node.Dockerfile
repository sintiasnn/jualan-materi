FROM node:latest


# Set working directory
WORKDIR /var/www/html

RUN echo ls

# Install Node dependencies and build assets
RUN npm install --legacy-peer-deps #&& npm run build --legacy-peer-deps

RUN npm run dev

EXPOSE 3001
