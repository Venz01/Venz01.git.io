#!/bin/bash
set -e

echo "Starting Laravel application..."

if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is not set. Refusing to start."
    echo "Please set APP_KEY in the environment (or .env) before running this container."
    exit 1
fi

# Storage must be writable before migrations / file cache / sessions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations FIRST so `sessions`, `cache`, `jobs`, etc. exist before any
# Artisan command that uses the DB (e.g. cache:clear with CACHE_STORE=database).
echo "Running database migrations..."
php artisan migrate --force

# Clear and warm caches (safe now that DB tables exist if using database drivers)
echo "Refreshing caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan route:clear || true

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf