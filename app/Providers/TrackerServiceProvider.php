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
        $this->app->singleton(App\Tracker\Processor::class);
        $this->app->singleton(App\Tracker\Keeper::class);
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
