<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Backup otomatis setiap hari pukul 02:00
        $schedule->command('backup:full --cleanup --no-confirm')
                 ->dailyAt('02:00')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Backup database setiap 6 jam
        $schedule->command('backup:database --cleanup')
                 ->everySixHours()
                 ->withoutOverlapping()
                 ->runInBackground();

        // Backup files setiap 12 jam
        $schedule->command('backup:files --cleanup')
                 ->twiceDaily(6, 18) // pukul 06:00 dan 18:00
                 ->withoutOverlapping()
                 ->runInBackground();

        // $schedule->command('inspire')->hourly();
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
