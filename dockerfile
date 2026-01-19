FROM php:8.3-fpm

# System deps
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

# Composer install
RUN composer install --no-dev --optimize-autoloader

# .env and APP_KEY
RUN cp .env.example .env \
    && php artisan key:generate

# Permissions
RUN mkdir -p storage/framework/{cache,data,sessions,views} \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Laravel cache clear
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

# Serve app
EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
