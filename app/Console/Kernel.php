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


        /*
         * Report inventory bulanan otomatis.
         * Dikirim setiap tanggal 1 jam 08:05 waktu Timor-Leste.
         *
         * Contoh:
         * 1 September 2026 -> kirim report Agustus 2026.
         */
        $schedule
            ->command('telegram:stock-monthly-report')
            ->monthlyOn(1, '08:05')
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