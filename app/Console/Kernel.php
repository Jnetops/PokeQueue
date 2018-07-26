<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        '\App\Console\Commands\DisbandInactiveGroup',
        '\App\Console\Commands\RemoveInactiveRaid',
        '\App\Console\Commands\RemoveInactivePokemon',
        'App\Console\Commands\EventStartingSoon',
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
      $schedule->command('DisbandInactiveGroup:disbandgroup')->everyThirtyMinutes();
      $schedule->command('RemoveInactiveRaid:removeraid')->everyMinute();
      $schedule->command('RemoveInactivePokemon:removepokemon')->everyMinute();
      $schedule->command('RemoveExpiredEvent:removeevent')->everyMinute();
      $schedule->command('EventStartingSoon:soon')->everyMinute();
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
