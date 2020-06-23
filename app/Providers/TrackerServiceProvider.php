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

        if (request('tracker_id') === null) {
            return;
        }

        $this->app->singleton(App\Tracker\BaseTracker::class, function ($app) {
            /** @var App\Tracker\Keeper $keeper */
            $keeper = $this->app->make(App\Tracker\Keeper::class);
            return $keeper->getTrackerById(request('tracker_id'));
        });

        /** @var App\Tracker\BaseTracker $tracker */
        $tracker = $this->app->make(App\Tracker\BaseTracker::class);
        if (!$tracker->isBlocked()) {
            return;
        }

        $this->app->singleton(App\Tracker\BlockedTracker::class, static function ($app) {
            return $app->make(App\Tracker\BaseTracker::class);
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
