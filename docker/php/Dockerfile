FROM php:8.4.10-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer