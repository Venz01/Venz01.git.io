# ----------------------------
# Stage 1: Build frontend
# ----------------------------
FROM node:20 AS frontend

WORKDIR /app

# Copy package files and install
COPY package*.json ./
RUN npm install

# Copy all frontend files and build
COPY . .
RUN npm run build

# ----------------------------
# Stage 2: Setup PHP + Apache
# ----------------------------
FROM php:8.3-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy Laravel backend
COPY . .

# Copy built frontend assets
COPY --from=frontend /app/public/build /var/www/html/public/build

# Install PHP extensions
RUN apt-get update && apt-get install -y \
        libpq-dev \
        zip \
        unzip \
    && docker-php-ext-install pdo pdo_pgsql mbstring opcache

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy environment file (ensure you have .env in repo or handle in Render secrets)
# COPY .env /var/www/html/.env

# Expose port 8080 (Render default)
EXPOSE 8080

# Start Apache in foreground
CMD ["apache2-foreground"]
