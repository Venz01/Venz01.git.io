# -----------------------------
# Stage 1: Build
# -----------------------------
FROM php:8.3-fpm AS build

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    npm \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring zip opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install
RUN npm run build

# -----------------------------
# Stage 2: Production
# -----------------------------
FROM php:8.3-fpm

WORKDIR /var/www

# Copy built files from build stage
COPY --from=build /var/www /var/www

# Ensure permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Expose port 8080 for Render
EXPOSE 8080

# Set Laravel environment
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV APP_URL=https://your-render-app.onrender.com

# Run PHP built-in server (Render automatically sets port)
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
