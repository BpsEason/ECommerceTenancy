# --- Dockerfile for Laravel Backend ---
FROM php:8.3-fpm-alpine
WORKDIR /var/www/html
RUN apk add --no-cache \
    curl \
    unzip \
    git \
    supervisor \
    libzip-dev \
    postgresql-dev \
    redis-dev
RUN docker-php-ext-install pdo_pgsql zip pcntl
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY . .
# Install Prometheus PHP client and other dependencies
RUN composer require --no-dev --prefer-dist jimdo/prometheus_client_php
RUN composer install --no-dev --optimize-autoloader --no-scripts
RUN php artisan key:generate --force
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
EXPOSE 9000
CMD ["php-fpm"]
