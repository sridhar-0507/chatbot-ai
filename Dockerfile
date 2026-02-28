FROM php:8.2-cli

WORKDIR /var/www

COPY . .

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev zip \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --optimize-autoloader --no-dev

RUN php artisan key:generate
RUN php artisan config:cache
RUN php artisan route:cache

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000