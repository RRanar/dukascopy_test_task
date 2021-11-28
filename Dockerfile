FROM php:7.2-fpm

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY --from=alpine/git:latest /usr/bin/git /usr/local/bin/git

RUN apt-get update -y && apt-get install -y \
        libzip-dev \
        zip \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip 

WORKDIR /usr/share/local/cli-php