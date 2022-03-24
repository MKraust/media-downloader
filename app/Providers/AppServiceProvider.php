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
        $this->app->singleton(App\Services\Http\Requester::class);
        $this->app->singleton(App\Services\Http\ProxyRequester::class);
        $this->app->singleton(App\Telegram\Client::class);
        $this->app->singleton(App\Services\Server\Storage::class);

        $this->app->bind('requester.base', App\Services\Http\Requester::class);
        $this->app->bind('requester.proxy', App\Services\Http\ProxyRequester::class);
        $this->app->bind('requester.tor', App\Services\Http\TorProxyRequester::class);
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
