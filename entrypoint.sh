#!/bin/sh
set -e

# Ensure SQLite DB file exists
touch database/database.sqlite

# Run migrations
php artisan migrate --force

# Cache config and routes
php artisan config:cache
php artisan route:cache

# Ensure storage link
php artisan storage:link || true

# Ensure proper permissions for runtime directories and database
chown -R www-data:www-data database storage bootstrap/cache

# Start Apache
exec apache2-foreground

