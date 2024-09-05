#!/bin/bash

# Ensure storage and bootstrap/cache directories have the correct permissions
echo "Setting permissions for storage and bootstrap/cache..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Run database migrations (optional)
# echo "Running database migrations..."
# php artisan migrate --force

# Run any additional Laravel commands you need (optional)
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# Run Apache in the foreground
echo "Starting Apache..."
exec apache2-foreground
