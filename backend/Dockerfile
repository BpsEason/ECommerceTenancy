FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    libzip-dev \
    libpng-dev \
    jpeg-dev \
    postgresql-dev \
    oniguruma-dev \
    curl \
    git \
    build-base

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Redis extension
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Generate a JWT secret key during build
RUN php artisan jwt:secret

# Expose port 9000 for PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
