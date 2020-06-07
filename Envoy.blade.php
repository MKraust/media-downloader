@servers(['web' => 'a0204983@mkraust.ru'])

@task('deploy')

{{-- cd to website directory --}}
cd ~/domains/mkraust.ru

{{-- Maintenance mode ON --}}
php72 artisan down

{{-- Pull changes from Git --}}
git pull origin master

{{-- Install missing PHP dependencies --}}
php72 ~/bin/composer install --no-interaction --no-dev --prefer-dist

npm install
npm run prod

{{-- Maintenance mode OFF --}}
php72 artisan up

@endtask
