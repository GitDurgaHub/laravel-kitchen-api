FROM php:8.3-fpm-alpine

# System dependencies
RUN apk add --no-cache git curl bash shadow libpng-dev libzip-dev oniguruma-dev icu-dev

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring intl zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Optimize dev UX (set permissions)
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
USER www-data

# Pre-install vendor dependencies (first build only)
RUN composer install || true
