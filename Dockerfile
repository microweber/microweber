FROM php:7.1-fpm-alpine

# docker-entrypoint.sh dependencies
RUN apk add --no-cache bash sudo

# install the PHP extensions we need
RUN set -ex; \
 \
 apk add --no-cache --virtual .build-deps \
  icu-dev \
 ; \
 \
  docker-php-ext-install intl; \
 \
 runDeps="$( \
  scanelf --needed --nobanner --recursive \
   /usr/local/lib/php/extensions \
   | awk '{ gsub(/,/, "\nso:", $2); print "so:" $2 }' \
   | sort -u \
   | xargs -r apk info --installed \
   | sort -u \
 )"; \
 apk add --virtual .microweber-phpexts-rundeps $runDeps; \
 apk del .build-deps

RUN mkdir /usr/src/microweber
ADD / /usr/src/microweber

RUN curl https://getcomposer.org/composer.phar -o /usr/local/bin/composer && chmod +x /usr/local/bin/composer

WORKDIR /usr/src/microweber

CMD ["php-fpm"]
