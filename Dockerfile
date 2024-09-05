# Use the official PHP 8.3 Apache image
FROM php:8.3-apache

# Set environment variables to prevent interactive mode during installation
ENV DEBIAN_FRONTEND=noninteractive

# Update and install required dependencies, PHP extensions, and MySQL client
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libssl-dev \
    nodejs \
    npm \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql mbstring exif pcntl bcmath opcache \
    && docker-php-ext-install zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set the ServerName directive globally to suppress Apache warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache Rewrite Module for Laravel routing
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy Composer dependencies early to leverage Docker layer caching
COPY composer.json composer.lock ./

# Install Composer dependencies (optimized with cache)
RUN composer install --no-dev --prefer-dist --no-scripts --no-autoloader

# Copy existing application directory contents to the container
COPY . .

# Re-run Composer autoloader optimization after code copy
RUN composer dump-autoload --optimize

# Ensure correct permissions in Dockerfile
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 0755 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 8080 for Apache
EXPOSE 8080

COPY .env.production .env

# Copy the docker-entrypoint.sh script and make it executable
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set Apache DocumentRoot to Laravel's public directory
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf

# Modify Apache to listen on port 8080
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Optional: Optimize PHP for Cloud Run
RUN set -ex; \
  { \
    echo "; Cloud Run enforces memory & timeouts"; \
    echo "memory_limit = -1"; \
    echo "max_execution_time = 0"; \
    echo "; File upload at Cloud Run network limit"; \
    echo "upload_max_filesize = 32M"; \
    echo "post_max_size = 32M"; \
    echo "; Configure Opcache for Containers"; \
    echo "opcache.enable = On"; \
    echo "opcache.validate_timestamps = Off"; \
    echo "opcache.memory_consumption = 32"; \
  } > "$PHP_INI_DIR/conf.d/cloud-run.ini"

# Start Apache in the foreground
CMD ["apache2-foreground"]

# Set the entrypoint to the script
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
