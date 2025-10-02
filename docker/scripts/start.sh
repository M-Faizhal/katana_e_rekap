#!/bin/bash

# Create deployment marker with timestamp
echo "$(date '+%Y-%m-%d_%H:%M:%S')_$(hostname)" > /var/www/html/.deployment

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
php artisan storage:link

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create log directories for supervisor
mkdir -p /var/log/supervisor

echo "Starting Apache..."
exec apache2-foreground
