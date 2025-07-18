FROM composer:2.2 AS composer_base

# Gunakan MySQL dan ekstensi lain yang dibutuhkan
ARG PHP_EXTS="pdo_mysql pcntl gd zip intl exif mysqli"
ARG PHP_PECL_EXTS="redis"
ARG PHP_ENABLE_EXTS="exif intl redis"

RUN set -eux; \
    apk add --virtual build-dependencies --no-cache ${PHPIZE_DEPS} openssl ca-certificates libxml2-dev oniguruma-dev \
    && apk add --update --no-cache freetype-dev libjpeg-turbo-dev jpeg-dev libpng-dev libzip-dev icu-dev \
    && pecl install ${PHP_PECL_EXTS} \
    && docker-php-ext-enable ${PHP_PECL_EXTS} \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) ${PHP_EXTS} \
    && docker-php-ext-enable ${PHP_ENABLE_EXTS} \
    && apk del build-dependencies

COPY . /var/www

WORKDIR /var/www

RUN composer install --optimize-autoloader --no-interaction --no-progress --prefer-dist

# -------------------------------------------

FROM node:22-slim AS node_base

COPY --from=composer_base /var/www /var/www

WORKDIR /var/www

RUN npm install && npm run build

# -------------------------------------------

FROM php:8.3.4-fpm-alpine

USER root

# Tambahkan dependency untuk MySQL dan Redis
RUN apk add --no-cache \
    msmtp perl wget procps shadow \
    libzip libpng libjpeg-turbo libwebp freetype icu \
    && apk add --no-cache --virtual build-essentials \
    icu-dev icu-libs zlib-dev g++ make automake autoconf libzip-dev \
    libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        gd \
        pdo \
        pdo_mysql \
        bcmath \
        intl \
        opcache \
        exif \
        zip \
    && apk del build-essentials && rm -rf /usr/src/php*

# Install nginx
RUN apk add --no-cache nginx wget

RUN mkdir -p /run/nginx

ARG PORT=8080
ENV PORT=$PORT

COPY ops/cloudrun/nginx/nginx.conf /etc/nginx/nginx.conf

COPY --from=node_base --chown=www-data:www-data /var/www /var/www

CMD ["sh", "/var/www/ops/cloudrun/nginx/startup.sh"]
