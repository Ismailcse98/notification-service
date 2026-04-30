FROM php:8.2-fpm-alpine

RUN apk add --no-cache bash curl libzip-dev oniguruma-dev
RUN docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader

CMD ["php-fpm"]