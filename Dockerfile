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
FROM php:7.4-cli-alpine AS php-base

RUN apk add --update --no-cache gmp gmp-dev \
    && docker-php-ext-install -j$(nproc) gmp bcmath

COPY . /app/
COPY --from=vendor /app/vendor/ /app/vendor/

WORKDIR /app/

COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

##################################################################################################################
# Test Stage
##################################################################################################################
FROM php-base AS test

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY --from=vendor /usr/bin/composer /usr/bin/composer

# run the test script(s) from composer, this validates the application before allowing the build to succeed
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist --no-ansi --ignore-platform-reqs
RUN vendor/bin/phpunit --testdox

##################################################################################################################
# Production Stage
##################################################################################################################
FROM php-base AS production

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# compile the container for performance reasons
RUN /app/bin/bolt11 >/dev/null

ENTRYPOINT ["docker-entrypoint"]
