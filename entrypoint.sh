#!/bin/sh
set -e

# Ensure SQLite DB file exists
if [ -f /data/database.sqlite ]; then
  cp /data/database.sqlite database/database.sqlite
else
  touch database/database.sqlite
fi

# Run migrations
php artisan migrate --force

# Clear and rebuild Laravel caches to pick up new env vars
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# Ensure storage link
php artisan storage:link || true


# Ensure proper permissions for runtime directories and database
chown -R www-data:www-data database storage bootstrap/cache
chmod -R 775 database storage bootstrap/cache


# Start Apache
exec apache2-foreground

