<?php

namespace App\Console\Commands;

use App\Exports\InventoryReportExport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TelegramMonthlyStockReportCommand extends Command
{
    protected $signature = 'telegram:stock-monthly-report';

    protected $description =
        'Kirim report inventory bulanan otomatis ke Telegram Stock Bot';

    public function handle(): int
    {
        $token =
            env('TELEGRAM_STOCK_BOT_TOKEN');

        $chatId =
            env('TELEGRAM_STOCK_CHAT_ID');

        if (!$token) {
            $this->error(
                'TELEGRAM_STOCK_BOT_TOKEN belum tersedia.'
            );

            return self::FAILURE;
        }

        if (!$chatId) {
            $this->error(
                'TELEGRAM_STOCK_CHAT_ID belum tersedia.'
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | PERIODE BULAN SEBELUMNYA
        |--------------------------------------------------------------------------
        |
        | Contoh:
        | Command jalan 1 September 2026
        | Report yang dikirim = 1 - 31 Agustus 2026
        |
        */

        $startDate =
            now()
                ->subMonthNoOverflow()
                ->startOfMonth()
                ->toDateString();

        $endDate =
            now()
                ->subMonthNoOverflow()
                ->endOfMonth()
                ->toDateString();

        $periodName =
            now()
                ->subMonthNoOverflow()
                ->translatedFormat('F Y');

        $fileName =
            'laporan-inventaris-'
            . now()
                ->subMonthNoOverflow()
                ->format('Y-m')
            . '.xlsx';

        $relativePath =
            'telegram-reports/'
            . $fileName;

        try {
            /*
            |--------------------------------------------------------------------------
            | GENERATE EXCEL DARI REPORT SISTEM
            |--------------------------------------------------------------------------
            */

            Excel::store(
                new InventoryReportExport(
                    $startDate,
                    $endDate
                ),
                $relativePath,
                'local'
            );

            $absolutePath =
                storage_path(
                    'app/' . $relativePath
                );

            if (
                !file_exists(
                    $absolutePath
                )
            ) {
                $this->error(
                    'File report Excel gagal dibuat.'
                );

                return self::FAILURE;
            }

            /*
            |--------------------------------------------------------------------------
            | KIRIM FILE KE TELEGRAM
            |--------------------------------------------------------------------------
            */

            $response =
                Http::timeout(60)
                    ->attach(
                        'document',
                        file_get_contents(
                            $absolutePath
                        ),
                        $fileName
                    )
                    ->post(
                        "https://api.telegram.org/bot{$token}/sendDocument",
                        [
                            'chat_id' =>
                                $chatId,

                            'caption' =>
                                "📊 DULMAR INVENTORY REPORT\n\n"
                                . "Periode: {$periodName}\n"
                                . "Tanggal: {$startDate} s/d {$endDate}\n\n"
                                . "✅ Report bulanan otomatis.",
                        ]
                    );

            if (
                !$response->successful()
            ) {
                $this->error(
                    'Gagal mengirim report Telegram: '
                    . $response->body()
                );

                return self::FAILURE;
            }

            $this->info(
                "Report {$periodName} berhasil dikirim."
            );

            return self::SUCCESS;

        } catch (\Throwable $e) {
            $this->error(
                'Monthly Report Error: '
                . $e->getMessage()
            );

            return self::FAILURE;

        } finally {
            if (
                Storage::disk('local')
                    ->exists(
                        $relativePath
                    )
            ) {
                Storage::disk('local')
                    ->delete(
                        $relativePath
                    );
            }
        }
    }
}