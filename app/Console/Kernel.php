<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\CommandFacebook::class,
        \App\Console\Commands\CommandTwitter::class,
        \App\Console\Commands\CommandInstagram::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('facebook')
            ->dailyAt('00:10');
        $schedule->command('twitter')
            ->dailyAt('00:10');
        $schedule->command('instagram')
            ->dailyAt('00:10');

        $schedule->command('facebook 1')
            ->everyThirtyMinutes()->unlessBetween('23:00', '3:00');
        $schedule->command('twitter 1')
            ->everyThirtyMinutes()->unlessBetween('23:00', '3:00');
        $schedule->command('instagram 1')
            ->everyThirtyMinutes()->unlessBetween('23:00', '3:00');

    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
