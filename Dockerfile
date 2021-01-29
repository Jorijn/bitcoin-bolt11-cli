##################################################################################################################
# Dependency Stage
##################################################################################################################
FROM composer:2 AS vendor

WORKDIR /app/

COPY composer.json composer.lock /app/

COPY . /app/

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --classmap-authoritative \
    --no-ansi \
    --no-dev

##################################################################################################################
# Base Stage
##################################################################################################################
FROM php:7.4-cli

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apt-get update -y \
    && apt-get install -y libgmp-dev file \
    && ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/local/include/ \
    && docker-php-ext-install -j$(nproc) gmp bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY . /app/
COPY --from=vendor /app/vendor/ /app/vendor/

WORKDIR /app/

COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

# compile the container for performance reasons
RUN /app/bin/bolt11 >/dev/null

ENTRYPOINT ["docker-entrypoint"]
