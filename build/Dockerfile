FROM php:8.2.3-fpm-alpine as builder

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

COPY . .

RUN set -ex && \
    apk add --no-cache --virtual .php-deps make && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS zlib-dev icu-dev g++ && \
    apk add --update --no-cache icu sqlite-dev sqlite pcre-dev linux-headers && \
    pecl install redis xdebug uopz pcov && \
    docker-php-ext-configure intl && \
    docker-php-ext-install pdo pdo_sqlite sockets intl && \
    docker-php-ext-enable redis xdebug uopz intl pcov

RUN composer install --no-ansi --no-dev --no-interaction --no-progress --optimize-autoloader --prefer-dist

FROM php:8.2.3-fpm-alpine

WORKDIR /app

COPY --from=builder /app/bin /app/bin
COPY --from=builder /app/config /app/config
COPY --from=builder /app/.env /app/.env
COPY --from=builder /app/public /app/public
COPY --from=builder /app/src /app/src
COPY --from=builder /app/vendor /app/vendor
COPY --from=builder /app/composer.json /app/composer.json
COPY --from=builder /app/build/entrypoint.sh /entrypoint.sh

RUN set -ex && \
    apk add --no-cache --virtual .php-deps make && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS zlib-dev icu-dev g++ && \
    apk add --update --no-cache icu sqlite-dev sqlite pcre-dev linux-headers && \
    pecl install redis && \
    docker-php-ext-configure intl && \
    docker-php-ext-install pdo pdo_sqlite sockets intl && \
    docker-php-ext-enable redis intl && \
    chown www-data:www-data -R /app

CMD ["php-fpm"]
ENTRYPOINT ["/entrypoint.sh"]

EXPOSE 9000
