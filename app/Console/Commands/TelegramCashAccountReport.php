<?php

namespace App\Console\Commands;

use App\Exports\CashMovementExport;
use App\Models\CashAccount;
use App\Models\CashMovement;
use App\Services\TelegramService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class TelegramCashAccountReport extends Command
{
    /**
     * Nama command Artisan.
     */
    protected $signature = 'telegram:cash-account-report';

    /**
     * Deskripsi command.
     */
    protected $description =
        'Generate laporan Kas Admin dalam Excel dan PDF lalu kirim ke Telegram';

    /**
     * Jalankan command.
     */
    public function handle(): int
    {
        $this->info('Membuat laporan Kas Admin...');

        $reportDirectory =
            storage_path('app/reports/cash-account');

        if (!File::exists($reportDirectory)) {
            File::makeDirectory(
                $reportDirectory,
                0755,
                true
            );
        }

        $timestamp =
            now('Asia/Dili')->format('Y-m-d_H-i-s');

        $excelFileName =
            "histori-pergerakan-uang-{$timestamp}.xlsx";

        $pdfFileName =
            "histori-pergerakan-uang-{$timestamp}.pdf";

        $excelPath =
            $reportDirectory
            . DIRECTORY_SEPARATOR
            . $excelFileName;

        $pdfPath =
            $reportDirectory
            . DIRECTORY_SEPARATOR
            . $pdfFileName;

        try {
            /*
            |--------------------------------------------------------------------------
            | DATA ACCOUNT
            |--------------------------------------------------------------------------
            */

            $adminAccount =
                CashAccount::firstOrCreate(
                    [
                        'account_type' =>
                            CashAccount::TYPE_ADMIN,
                    ],
                    [
                        'balance' => 0,
                        'bank_name' => null,
                        'notes' =>
                            'Saldo uang yang sedang berada di Admin.',
                    ]
                );

            $bankAccount =
                CashAccount::firstOrCreate(
                    [
                        'account_type' =>
                            CashAccount::TYPE_BANK,
                    ],
                    [
                        'balance' => 0,
                        'bank_name' => null,
                        'notes' =>
                            'Saldo uang yang sedang berada di Bank.',
                    ]
                );

            $mosanAccount =
                CashAccount::firstOrCreate(
                    [
                        'account_type' =>
                            CashAccount::TYPE_MOSAN,
                    ],
                    [
                        'balance' => 0,
                        'bank_name' => null,
                        'notes' =>
                            'Saldo pembayaran digital yang berada di aplikasi Mosan.',
                    ]
                );

            $totalMoney =
                (float) $adminAccount->balance
                + (float) $bankAccount->balance
                + (float) $mosanAccount->balance;

            /*
            |--------------------------------------------------------------------------
            | HISTORI
            |--------------------------------------------------------------------------
            */

            $movements =
                CashMovement::query()
                    ->with('creator')
                    ->latest()
                    ->get();

            /*
            |--------------------------------------------------------------------------
            | GENERATE EXCEL
            |--------------------------------------------------------------------------
            */

            Excel::store(
                new CashMovementExport(),
                'reports/cash-account/' . $excelFileName
            );

            if (!File::exists($excelPath)) {
                $this->error(
                    'File Excel gagal dibuat.'
                );

                return self::FAILURE;
            }

            $this->info(
                'Excel berhasil dibuat.'
            );

            /*
            |--------------------------------------------------------------------------
            | GENERATE PDF
            |--------------------------------------------------------------------------
            */

            $pdf =
                Pdf::loadView(
                    'cash-accounts.pdf',
                    compact(
                        'movements',
                        'adminAccount',
                        'bankAccount',
                        'mosanAccount',
                        'totalMoney'
                    )
                )
                ->setPaper(
                    'a4',
                    'landscape'
                );

            $pdf->save(
                $pdfPath
            );

            if (!File::exists($pdfPath)) {
                $this->error(
                    'File PDF gagal dibuat.'
                );

                return self::FAILURE;
            }

            $this->info(
                'PDF berhasil dibuat.'
            );

            /*
            |--------------------------------------------------------------------------
            | TELEGRAM
            |--------------------------------------------------------------------------
            */

            $telegram =
                app(TelegramService::class);

            $caption =
                "<b>📊 LAPORAN KAS ADMIN</b>\n\n"
                . "<b>Tanggal:</b> "
                . now('Asia/Dili')->format('d-m-Y H:i')
                . "\n\n"
                . "<b>Uang di Admin:</b> $"
                . number_format(
                    (float) $adminAccount->balance,
                    2
                )
                . "\n"
                . "<b>Saldo Mosan:</b> $"
                . number_format(
                    (float) $mosanAccount->balance,
                    2
                )
                . "\n"
                . "<b>Uang di Bank:</b> $"
                . number_format(
                    (float) $bankAccount->balance,
                    2
                )
                . "\n"
                . "<b>Total Uang:</b> $"
                . number_format(
                    $totalMoney,
                    2
                );

            /*
            |--------------------------------------------------------------------------
            | KIRIM PESAN RINGKAS
            |--------------------------------------------------------------------------
            */

            $messageSent =
                $telegram->send(
                    $caption
                );

            if (!$messageSent) {
                $this->warn(
                    'Pesan ringkas Telegram gagal dikirim.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | KIRIM EXCEL
            |--------------------------------------------------------------------------
            */

            $excelSent =
                $telegram->sendDocument(
                    $excelPath,
                    '📊 Histori Pergerakan Uang - Excel'
                );

            if ($excelSent) {
                $this->info(
                    'Excel berhasil dikirim ke Telegram.'
                );
            } else {
                $this->warn(
                    'Excel gagal dikirim ke Telegram.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | KIRIM PDF
            |--------------------------------------------------------------------------
            */

            $pdfSent =
                $telegram->sendDocument(
                    $pdfPath,
                    '📄 Histori Pergerakan Uang - PDF'
                );

            if ($pdfSent) {
                $this->info(
                    'PDF berhasil dikirim ke Telegram.'
                );
            } else {
                $this->warn(
                    'PDF gagal dikirim ke Telegram.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | HAPUS FILE SEMENTARA
            |--------------------------------------------------------------------------
            */

            if (File::exists($excelPath)) {
                File::delete(
                    $excelPath
                );
            }

            if (File::exists($pdfPath)) {
                File::delete(
                    $pdfPath
                );
            }

            /*
            |--------------------------------------------------------------------------
            | RESULT
            |--------------------------------------------------------------------------
            */

            if (!$excelSent || !$pdfSent) {
                return self::FAILURE;
            }

            $this->info(
                'Laporan Kas Admin selesai dikirim ke Telegram.'
            );

            return self::SUCCESS;

        } catch (\Throwable $e) {
            $this->error(
                'Gagal membuat atau mengirim laporan: '
                . $e->getMessage()
            );

            report($e);

            return self::FAILURE;
        }
    }
}