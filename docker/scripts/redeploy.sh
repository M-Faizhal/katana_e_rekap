#!/bin/bash

# Redeploy script for automatic deployments
echo "Starting redeploy process..."

# Pull latest changes (if using git)
if [ -d ".git" ]; then
    echo "Pulling latest changes..."
    git pull origin main || git pull origin master
fi

# Install/update PHP dependencies
echo "Updating PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install/update Node.js dependencies
if [ -f "package.json" ]; then
    echo "Installing Node.js dependencies..."
    npm ci --silent
    
    echo "Running npm audit fix..."
    npm audit fix --silent || true
    
    echo "Building assets..."
    npm run build
    
    # Clean up dev dependencies
    npm ci --only=production --silent
fi

# Clear Laravel caches
echo "Clearing application cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Cache Laravel configuration
echo "Caching application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Redeploy completed successfully!"
