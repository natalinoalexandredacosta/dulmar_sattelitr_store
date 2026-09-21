<?php

namespace App\Console\Commands;

use App\Exports\InventoryReportExport;
use App\Models\CashTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TelegramMonthlyStockReportCommand extends Command
{
    protected $signature = 'telegram:stock-monthly-report';

    protected $description =
        'Kirim report inventory dan Kas Inventory bulanan otomatis ke Telegram Stock Bot';

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
        */

        $reportMonth =
            now()
                ->subMonthNoOverflow();

        $startDate =
            $reportMonth
                ->copy()
                ->startOfMonth()
                ->toDateString();

        $endDate =
            $reportMonth
                ->copy()
                ->endOfMonth()
                ->toDateString();

        $periodName =
            $reportMonth
                ->copy()
                ->translatedFormat('F Y');

        $fileName =
            'laporan-inventaris-'
            . $reportMonth
                ->copy()
                ->format('Y-m')
            . '.xlsx';

        $relativePath =
            'telegram-reports/'
            . $fileName;

        try {

            /*
            |--------------------------------------------------------------------------
            | SALDO AWAL KAS
            |--------------------------------------------------------------------------
            */

            $openingIncome =
                (float) CashTransaction::query()
                    ->where(
                        'approval_status',
                        'approved'
                    )
                    ->where(
                        'type',
                        'income'
                    )
                    ->whereDate(
                        'transaction_date',
                        '<',
                        $startDate
                    )
                    ->sum('amount');

            $openingExpense =
                (float) CashTransaction::query()
                    ->where(
                        'approval_status',
                        'approved'
                    )
                    ->where(
                        'type',
                        'expense'
                    )
                    ->whereDate(
                        'transaction_date',
                        '<',
                        $startDate
                    )
                    ->sum('amount');

            $openingBalance =
                $openingIncome
                - $openingExpense;


            /*
            |--------------------------------------------------------------------------
            | TRANSAKSI APPROVED BULAN REPORT
            |--------------------------------------------------------------------------
            */

            $approvedBase =
                CashTransaction::query()
                    ->where(
                        'approval_status',
                        'approved'
                    )
                    ->whereBetween(
                        'transaction_date',
                        [
                            $startDate,
                            $endDate,
                        ]
                    );

            $totalIncome =
                (float) (clone $approvedBase)
                    ->where(
                        'type',
                        'income'
                    )
                    ->sum('amount');

            $totalExpense =
                (float) (clone $approvedBase)
                    ->where(
                        'type',
                        'expense'
                    )
                    ->sum('amount');

            $incomeCount =
                (clone $approvedBase)
                    ->where(
                        'type',
                        'income'
                    )
                    ->count();

            $expenseCount =
                (clone $approvedBase)
                    ->where(
                        'type',
                        'expense'
                    )
                    ->count();

            $closingBalance =
                $openingBalance
                + $totalIncome
                - $totalExpense;


            /*
            |--------------------------------------------------------------------------
            | TRANSAKSI PENDING BULAN REPORT
            |--------------------------------------------------------------------------
            */

            $pendingBase =
                CashTransaction::query()
                    ->where(
                        'approval_status',
                        'pending'
                    )
                    ->whereBetween(
                        'transaction_date',
                        [
                            $startDate,
                            $endDate,
                        ]
                    );

            $pendingIncome =
                (float) (clone $pendingBase)
                    ->where(
                        'type',
                        'income'
                    )
                    ->sum('amount');

            $pendingExpense =
                (float) (clone $pendingBase)
                    ->where(
                        'type',
                        'expense'
                    )
                    ->sum('amount');

            $pendingIncomeCount =
                (clone $pendingBase)
                    ->where(
                        'type',
                        'income'
                    )
                    ->count();

            $pendingExpenseCount =
                (clone $pendingBase)
                    ->where(
                        'type',
                        'expense'
                    )
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | BREAKDOWN CASH MASUK
            |--------------------------------------------------------------------------
            */

            $incomeCategories =
                CashTransaction::query()
                    ->select('category')
                    ->selectRaw(
                        'COUNT(*) AS transaction_count'
                    )
                    ->selectRaw(
                        'SUM(amount) AS total_amount'
                    )
                    ->where(
                        'approval_status',
                        'approved'
                    )
                    ->where(
                        'type',
                        'income'
                    )
                    ->whereBetween(
                        'transaction_date',
                        [
                            $startDate,
                            $endDate,
                        ]
                    )
                    ->groupBy('category')
                    ->orderByDesc(
                        'total_amount'
                    )
                    ->get();


            /*
            |--------------------------------------------------------------------------
            | BREAKDOWN CASH KELUAR
            |--------------------------------------------------------------------------
            */

            $expenseCategories =
                CashTransaction::query()
                    ->select('category')
                    ->selectRaw(
                        'COUNT(*) AS transaction_count'
                    )
                    ->selectRaw(
                        'SUM(amount) AS total_amount'
                    )
                    ->where(
                        'approval_status',
                        'approved'
                    )
                    ->where(
                        'type',
                        'expense'
                    )
                    ->whereBetween(
                        'transaction_date',
                        [
                            $startDate,
                            $endDate,
                        ]
                    )
                    ->groupBy('category')
                    ->orderByDesc(
                        'total_amount'
                    )
                    ->get();


            /*
            |--------------------------------------------------------------------------
            | FORMAT PESAN KAS
            |--------------------------------------------------------------------------
            */

            $cashMessage =
                "<b>📊 LAPORAN KAS INVENTORY BULANAN</b>\n\n"
                . "<b>Periode:</b> {$periodName}\n"
                . "<b>Tanggal:</b> {$startDate} s/d {$endDate}\n\n"
                . "<b>💰 RINGKASAN KAS</b>\n"
                . "Saldo Awal: <b>$"
                . number_format(
                    $openingBalance,
                    2
                )
                . "</b>\n"
                . "Cash Masuk: <b>+$"
                . number_format(
                    $totalIncome,
                    2
                )
                . "</b>\n"
                . "Cash Keluar: <b>-$"
                . number_format(
                    $totalExpense,
                    2
                )
                . "</b>\n"
                . "Saldo Akhir: <b>$"
                . number_format(
                    $closingBalance,
                    2
                )
                . "</b>\n\n"
                . "<b>✅ TRANSAKSI APPROVED</b>\n"
                . "Cash Masuk: {$incomeCount} transaksi\n"
                . "Cash Keluar: {$expenseCount} transaksi\n\n"
                . "<b>⏳ TRANSAKSI PENDING</b>\n"
                . "Cash Masuk: $"
                . number_format(
                    $pendingIncome,
                    2
                )
                . " ({$pendingIncomeCount} transaksi)\n"
                . "Cash Keluar: $"
                . number_format(
                    $pendingExpense,
                    2
                )
                . " ({$pendingExpenseCount} transaksi)\n";


            if ($incomeCategories->isNotEmpty()) {

                $cashMessage .=
                    "\n<b>📥 CASH MASUK PER KATEGORI</b>\n";

                foreach (
                    $incomeCategories
                    as $item
                ) {

                    $category =
                        $item->category
                        ?: 'Tanpa Kategori';

                    $cashMessage .=
                        "• "
                        . $category
                        . ": +$"
                        . number_format(
                            (float) $item->total_amount,
                            2
                        )
                        . " ("
                        . (int) $item->transaction_count
                        . " transaksi)\n";
                }
            }


            if ($expenseCategories->isNotEmpty()) {

                $cashMessage .=
                    "\n<b>📤 CASH KELUAR PER KATEGORI</b>\n";

                foreach (
                    $expenseCategories
                    as $item
                ) {

                    $category =
                        $item->category
                        ?: 'Tanpa Kategori';

                    $cashMessage .=
                        "• "
                        . $category
                        . ": -$"
                        . number_format(
                            (float) $item->total_amount,
                            2
                        )
                        . " ("
                        . (int) $item->transaction_count
                        . " transaksi)\n";
                }
            }


            $cashMessage .=
                "\n✅ Pending tidak memengaruhi saldo sebelum disetujui Admin.";


            /*
            |--------------------------------------------------------------------------
            | KIRIM CASH REPORT KE TELEGRAM
            |--------------------------------------------------------------------------
            */

            $cashResponse =
                Http::timeout(60)
                    ->post(
                        "https://api.telegram.org/bot{$token}/sendMessage",
                        [
                            'chat_id' =>
                                $chatId,

                            'text' =>
                                $cashMessage,

                            'parse_mode' =>
                                'HTML',
                        ]
                    );

            if (
                !$cashResponse->successful()
            ) {
                $this->error(
                    'Gagal mengirim laporan Kas Inventory ke Telegram: '
                    . $cashResponse->body()
                );

                return self::FAILURE;
            }


            /*
            |--------------------------------------------------------------------------
            | GENERATE EXCEL INVENTORY
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
            | KIRIM FILE INVENTORY KE TELEGRAM
            |--------------------------------------------------------------------------
            */

            $inventoryResponse =
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
                                "📦 DULMAR INVENTORY REPORT\n\n"
                                . "Periode: {$periodName}\n"
                                . "Tanggal: {$startDate} s/d {$endDate}\n\n"
                                . "✅ Report inventory bulanan otomatis.",
                        ]
                    );

            if (
                !$inventoryResponse->successful()
            ) {
                $this->error(
                    'Gagal mengirim report Inventory Telegram: '
                    . $inventoryResponse->body()
                );

                return self::FAILURE;
            }


            $this->info(
                "Laporan Kas Inventory dan Inventory {$periodName} berhasil dikirim."
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
