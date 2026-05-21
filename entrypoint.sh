#!/bin/sh
set -e

# Ensure SQLite DB file exists
touch database/database.sqlite

# Run migrations
php artisan migrate --force

# Ensure storage link
php artisan storage:link || true


# Ensure proper permissions for runtime directories and database
chown -R www-data:www-data database storage bootstrap/cache
chmod -R 775 database storage bootstrap/cache


# Start Apache
exec apache2-foreground

