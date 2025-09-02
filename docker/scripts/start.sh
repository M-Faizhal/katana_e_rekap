#!/bin/bash

# Wait for database to be ready
echo "Waiting for database..."
while ! nc -z $DB_HOST $DB_PORT; do
  sleep 1
done
echo "Database is ready!"

# Generate app key if not exists
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear and cache application
echo "Clearing application cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Caching application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
echo "Creating storage symlink..."
# Remove existing link if exists
if [ -L "/var/www/html/public/storage" ]; then
    rm /var/www/html/public/storage
fi
# Create symlink
php artisan storage:link || true

# Alternative: Copy storage files if symlink fails
if [ ! -d "/var/www/html/public/storage" ]; then
    echo "Symlink failed, creating directory and copying files..."
    mkdir -p /var/www/html/public/storage
    cp -r /var/www/html/storage/app/public/* /var/www/html/public/storage/ 2>/dev/null || true
fi

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 755 /var/www/html/public

# Sync storage files
echo "Syncing storage files..."
/var/www/html/docker/scripts/sync-storage.sh

echo "Starting Apache..."
apache2-foreground
