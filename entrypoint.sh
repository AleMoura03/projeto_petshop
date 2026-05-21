#!/bin/sh
set -e

# Ensure SQLite DB file exists
if [ -f /data/database.sqlite ] && php -r "
  try {
    \$db = new PDO('sqlite:/data/database.sqlite');
    \$stmt = \$db->query('SELECT COUNT(*) FROM users');
    if (\$stmt !== false && \$stmt->fetchColumn() > 0) {
      exit(0);
    }
  } catch (Exception \$e) {}
  exit(1);
"; then
  echo "Using existing persistent DB (contains users)"
else
  echo "Persistent DB is empty, copying from repository"
  mkdir -p /data
  if [ -f database/database.sqlite ]; then
    cp database/database.sqlite /data/database.sqlite
  else
    touch /data/database.sqlite
  fi
fi
# Symlink to Laravel expected location
ln -sf /data/database.sqlite database/database.sqlite

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
chown -R www-data:www-data database storage bootstrap/cache /data
chmod -R 775 database storage bootstrap/cache /data


# Start Apache
exec apache2-foreground

