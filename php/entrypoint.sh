#!/bin/bash
set -e

echo '* * * * * cd /var/www && php artisan schedule:run >> /dev/null 2>&1' | crontab
cron
#
#cd /var/www
#composer install --no-interaction --prefer-dist
#php artisan migrate
#npm i
#npm run prod
#
php-fpm