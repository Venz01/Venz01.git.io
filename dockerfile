# Stage 1 - Build Frontend (Vite)
FROM node:18 AS frontend
WORKDIR /app

# Install frontend dependencies
COPY package*.json ./
RUN npm install

# Copy frontend files and build
COPY . .
RUN npm run build

# Stage 2 - Backend (Laravel + PHP)
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy Laravel app files
COPY . .

# Copy frontend build from Stage 1
COPY --from=frontend /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Storage & cache permissions
RUN mkdir -p storage/framework/{cache,data,sessions,views} \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Clear caches
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

# Expose HTTP port for Render
EXPOSE 8080

# Serve Laravel using PHP built-in server
# Laravel will use environment variables from Render
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
