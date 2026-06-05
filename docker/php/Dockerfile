FROM php:8.4-fpm-alpine

# 必要な拡張・ツール
RUN apk add --no-cache \
    bash \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    mysql-client \
    icu-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# storage / bootstrap/cache を書き込み可能にする
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions \
    storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER www-data
