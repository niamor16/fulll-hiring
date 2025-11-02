FROM php:8.4.10-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl zip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer