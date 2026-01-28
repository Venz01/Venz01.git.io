#!/bin/bash
set -e

echo "Starting Laravel application..."

# Force HTTPS scheme
export FORCE_HTTPS=true

# Check if APP_KEY is set
if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is not set!"
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Clear all caches
echo "Clearing caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan route:clear || true

# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations
echo "Running migrations..."
php artisan migrate --force || echo "Migration failed or no migrations to run"

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf