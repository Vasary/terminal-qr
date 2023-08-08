FROM php:8.2-fpm-alpine as builder

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

COPY . .

RUN set -ex && \
    apk add --no-cache --virtual .php-deps make && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS zlib-dev icu-dev g++ && \
    apk add --update --no-cache icu sqlite-dev sqlite pcre-dev linux-headers nodejs npm && \
    docker-php-ext-configure intl && \
    docker-php-ext-install pdo pdo_ sockets intl mysqli && \
    docker-php-ext-enable intl mysqli

RUN composer install --no-ansi --no-dev --no-interaction --no-progress --optimize-autoloader --prefer-dist
RUN npm run watch --prefix /app/assets

FROM php:8.2-fpm-alpine

WORKDIR /app

COPY --from=builder /app/bin /app/bin
COPY --from=builder /app/config /app/config
COPY --from=builder /app/.env /app/.env
COPY --from=builder /app/public /app/public
COPY --from=builder /app/src /app/src
COPY --from=builder /app/vendor /app/vendor
COPY --from=builder /app/translations /app/translations
COPY --from=builder /app/composer.json /app/composer.json
COPY --from=builder /app/build/entrypoint.sh /entrypoint.sh

RUN set -ex && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS zlib-dev icu-dev g++ && \
    apk add --update --no-cache icu sqlite-dev sqlite pcre-dev linux-headers nginx curl ca-certificates && \
    docker-php-ext-configure intl && \
    docker-php-ext-install pdo pdo_sqlite intl && \
    docker-php-ext-enable intl && \
    mkdir /app/resource && \
    chown www-data:www-data -R /app && \
    ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

COPY build/nginx/http.d/default.conf /etc/nginx/http.d/default.conf

HEALTHCHECK --interval=30s --timeout=5s CMD curl --fail http://localhost/health/check || exit 1

CMD ["nginx", "-g", "daemon off;"]

ENTRYPOINT ["/entrypoint.sh"]

STOPSIGNAL SIGQUIT
EXPOSE 80
