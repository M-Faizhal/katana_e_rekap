#!/bin/bash

# Script untuk sync storage files ke public/storage
# Ini akan berjalan setiap kali ada file baru di storage/app/public

echo "Syncing storage files to public/storage..."

# Create public/storage if not exists
mkdir -p /var/www/html/public/storage

# Sync all files from storage/app/public to public/storage
rsync -av --delete /var/www/html/storage/app/public/ /var/www/html/public/storage/

# Set proper permissions
chown -R www-data:www-data /var/www/html/public/storage
chmod -R 755 /var/www/html/public/storage

echo "Storage sync completed!"
