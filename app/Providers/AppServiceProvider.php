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
        $this->app->singleton(App\Services\HttpRequester\Requester::class);
        $this->app->singleton(App\Services\HttpRequester\ProxyRequester::class);
        $this->app->singleton(App\Telegram\Client::class);
        $this->app->singleton(App\Server\Storage::class);
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
