FROM phpdockerio/php:8.2-fpm
WORKDIR "/application"

ARG WWWGROUP
ARG WWWUSER

RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        php8.2-bcmath \
        php8.2-bz2 \
        php8.2-dba \
        php8.2-enchant \
        php8.2-gd \
        php8.2-intl \
        php8.2-mysql \
        php8.2-redis \
        php8.2-xdebug; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN groupadd --force -g $WWWGROUP controle.financeiro
RUN useradd -s /bin/bash --no-user-group -g $WWWGROUP -u $WWWUSER controle.financeiro
