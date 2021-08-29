FROM php:7.4-apache




RUN docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg && \
    docker-php-ext-install gd

RUN docker-php-ext-install pdo_mysql zip dom curl mbstring intl



RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html

WORKDIR /var/www/html

RUN composer install --no-interaction --no-dev --prefer-dist

RUN chmod -R 777 /var/www/html
RUN a2enmod rewrite
RUN service apache2 restart


