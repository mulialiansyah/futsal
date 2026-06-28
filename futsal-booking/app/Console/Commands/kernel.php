<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * 
     * Command ini akan jalan SETIAP MENIT otomatis
     */
    protected function schedule(Schedule $schedule): void
    {
        // Auto-release expired bookings setiap 1 menit
        $schedule->command('bookings:release-expired')
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