#!/bin/bash
set -e

php-fpm -D
/etc/init.d/tor start

echo '* * * * * cd /var/www && php artisan schedule:run >> /dev/null 2>&1' | crontab
cron

cd /var/www
composer install --no-interaction --prefer-dist
php artisan migrate

php -a