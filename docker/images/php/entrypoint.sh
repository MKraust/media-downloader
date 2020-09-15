#!/bin/bash
set -e

php-fpm -D

echo '* * * * * cd /var/www && php artisan schedule:run >> /dev/null 2>&1' | crontab

cd /var/www
composer install --no-interaction --prefer-dist
php artisan migrate

tor