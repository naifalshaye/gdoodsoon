#!/bin/bash

# Clear Laravel caches (optional)
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

# Run any additional setup scripts (e.g., migrate database)
 php artisan migrate:fresh --force

# Execute the default command (Apache server)
exec "$@"
