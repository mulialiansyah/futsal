<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * NOTE: Disabled for Infinity Free (no cron support).
     * Use external cron service like cron-job.org if needed.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('bookings:release-expired')
            ->everyMinute()
            ->withoutOverlapping();

        $schedule->command('bookings:process-expired-closure-decisions')
            ->everyMinute()
            ->withoutOverlapping();
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
