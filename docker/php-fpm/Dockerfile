ARG PHP_VERSION=8.2.19

FROM php:${PHP_VERSION}-fpm

ARG WORKDIR=/usr/share/nginx/html

RUN apt-get update \
    && pecl install \
        xdebug \

RUN set -eux; \
    docker-php-ext-install -j$(nproc) \
        pdo \
    && docker-php-ext-enable \
        xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR ${WORKDIR}