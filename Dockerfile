# syntax=docker/dockerfile:1

# ---- Frontend assets ----
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources resources
COPY vite.config.js tailwind.config.js postcss.config.js ./
RUN npm run build

# ---- PHP dependencies ----
FROM composer:2 AS vendor
WORKDIR /app
COPY database database
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --optimize-autoloader

# ---- Runtime ----
FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    nginx supervisor \
    ghostscript \
    libpng-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath zip gd

WORKDIR /var/www/html

COPY . .
COPY --from=vendor /app/vendor vendor
COPY --from=assets /app/public/build public/build

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN php artisan package:discover --ansi \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]
