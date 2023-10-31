<?php

namespace App\Console;

use App\Console\Commands\ChangeStatus;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *  @return void
     */

    protected $commands = [
        // ChangeStatus::class
    ];
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('app:change_status')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
