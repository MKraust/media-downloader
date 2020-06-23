<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class TrackerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(App\Tracker\Keeper::class, static function ($app) {
            return new App\Tracker\Keeper;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
