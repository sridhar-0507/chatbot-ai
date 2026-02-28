FROM php:8.2-cli

WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev zip curl \
    default-mysql-client

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy all project files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Generate app key and cache config
RUN php artisan key:generate
RUN php artisan config:cache
RUN php artisan route:cache

# Link storage
RUN php artisan storage:link

# Expose port for Laravel serve
EXPOSE 10000

# Start command
CMD php artisan serve --host=0.0.0.0 --port=10000