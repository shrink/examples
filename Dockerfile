FROM php:7.4-cli-alpine as build

WORKDIR /srv

COPY --from=composer:1 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock /srv/
RUN composer global require hirak/prestissimo --dev && \
    composer install \
        --no-ansi \
        --no-autoloader \
        --no-interaction

COPY . /srv

RUN composer dump-autoload

FROM build as quality

RUN composer quality
