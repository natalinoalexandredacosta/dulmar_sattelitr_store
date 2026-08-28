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
        /*
         * Reminder otomatis TV Voucher yang belum disetor.
         * Dikirim setiap hari jam 08:00 waktu Timor-Leste.
         */
        $schedule
            ->command('telegram:unpaid-reminder')
            ->dailyAt('08:00')
            ->timezone('Asia/Dili')
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