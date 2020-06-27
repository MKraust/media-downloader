<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(App\Telegram\Client::class, function ($app) {
            return new App\Telegram\Client;
        });

        $this->app->singleton(App\Server\Storage::class, function ($app) {
            return new App\Server\Storage;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
