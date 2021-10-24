FROM webdevops/php-nginx:8.0-alpine

RUN set -xe \
    && apk add --update \
        icu \
    && apk add --no-cache --virtual .php-deps \
        make \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        zlib-dev \
        icu-dev \
        g++ \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        intl \
    && docker-php-ext-enable intl \
    && { find /usr/local/lib -type f -print0 | xargs -0r strip --strip-all -p 2>/dev/null || true; } \
    && apk del .build-deps \
    && rm -rf /tmp/* /usr/local/lib/php/doc/* /var/cache/apk/*

WORKDIR /app

ENV PHP_POST_MAX_SIZE 512M
ENV PHP_UPLOAD_MAX_FILESIZE 256M

COPY . /app

USER root
RUN rm -rf /app/writable
RUN mkdir /app/writable
RUN chmod -R 777 /app/writable
RUN composer require
RUN composer require jbzoo/image

RUN rm /opt/docker/etc/nginx/vhost.common.d/10-location-root.conf
COPY ./location.conf /opt/docker/etc/nginx/vhost.common.d/location.conf
COPY ./nginx-config.conf /opt/docker/etc/nginx/vhost.common.d/nginx-config.conf
RUN echo "memory_limit=-1" > /usr/local/etc/php/conf.d/maxmem.ini