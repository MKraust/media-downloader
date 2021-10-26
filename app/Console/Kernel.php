<?php

namespace App\Console;

use App\Jobs;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $time = Cache::get('schedule:time:check_favorites_for_new_episodes');
        if (!$time) {
            $minute = random_int(0, 59);
            $minute = $minute < 10 ? "0{$minute}" : $minute;
            $hour = random_int(16, 18);
            $time = "{$hour}:{$minute}";

            Cache::put('schedule:time:check_favorites_for_new_episodes', $time, Carbon::now()->endOfDay());
        }

        $schedule->job(new Jobs\RefreshTorrentDownloads)->everyMinute();
        $schedule->job(new Jobs\CheckFavoritesForNewEpisodes)->dailyAt($time);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
