#!/bin/sh
set -e

# The Fly volume is mounted at runtime and shadows whatever ownership the
# Dockerfile set at build time -- it comes in root-owned, so www-data
# (php-fpm's user) can't write into it until we fix this on every boot.
chown -R www-data:www-data /var/www/html/storage/app/public

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force

if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link
fi

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
