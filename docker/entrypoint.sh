#!/bin/sh
set -e

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force

if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link
fi

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
