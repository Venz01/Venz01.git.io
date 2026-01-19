# Stage 1 — Build Frontend (Vite)
FROM node:18 AS frontend
WORKDIR /app

COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2 — Laravel Backend
FROM php:8.3-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy Laravel app
COPY . .

# Copy frontend build
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

# Expose port for Render
EXPOSE 8080

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
