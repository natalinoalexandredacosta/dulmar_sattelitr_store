<?php

namespace App\Console\Commands;

use App\Models\TvVoucherTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class TelegramUnpaidReminderCommand extends Command
{
    protected $signature = 'telegram:unpaid-reminder';

    protected $description =
        'Mengirim reminder TV Voucher yang belum disetor petugas';

    public function handle(): int
    {
        $token =
            env('TELEGRAM_BOT_TOKEN');

        $chatId =
            env('TELEGRAM_CHAT_ID');

        if (!$token || !$chatId) {
            $this->error(
                'TELEGRAM_BOT_TOKEN atau TELEGRAM_CHAT_ID belum diisi.'
            );

            return SymfonyCommand::FAILURE;
        }

        /*
         * Ambil hanya transaksi yang masih mempunyai
         * sisa setoran petugas.
         */
        $transactions =
            TvVoucherTransaction::query()
                ->where(
                    'staff_balance',
                    '>',
                    0
                )
                ->orderBy(
                    'filled_by'
                )
                ->orderBy(
                    'transaction_date'
                )
                ->get();

        /*
         * Jika tidak ada setoran tertunda.
         */
        if ($transactions->isEmpty()) {
            $message =
                "✅ <b>SETORAN TV VOUCHER</b>\n\n"
                . "Semua uang yang diterima petugas "
                . "sudah disetor ke toko.\n\n"
                . "Tanggal: "
                . now()->format('d-m-Y');

            $this->sendTelegram(
                (string) $token,
                (string) $chatId,
                $message
            );

            $this->info(
                'Semua setoran petugas sudah lunas.'
            );

            return SymfonyCommand::SUCCESS;
        }

        /*
         * Kelompokkan berdasarkan orang yang mengisi voucher.
         */
        $grouped =
            $transactions->groupBy(
                function ($transaction) {
                    return $transaction->filled_by
                        ?: 'Tidak diketahui';
                }
            );

        $message =
            "⚠️ <b>REMINDER SETORAN TV VOUCHER</b>\n\n";

        foreach ($grouped as $name => $items) {
            $jumlahTransaksi =
                $items->count();

            $jumlahVoucher =
                (int) $items->sum(
                    'quantity'
                );

            /*
             * Total uang customer yang sudah diterima
             * oleh petugas.
             */
            $uangDiterima =
                (float) $items->sum(
                    'staff_received_amount'
                );

            /*
             * Total yang sudah disetor.
             */
            $sudahDisetor =
                (float) $items->sum(
                    'staff_deposited_amount'
                );

            /*
             * Total yang masih harus disetor.
             */
            $belumDisetor =
                (float) $items->sum(
                    'staff_balance'
                );

            $message .=
                "👤 <b>{$name}</b>\n"
                . "Transaksi: {$jumlahTransaksi}\n"
                . "Jumlah Voucher: {$jumlahVoucher}\n"
                . "Uang Diterima: $"
                . number_format(
                    $uangDiterima,
                    2
                )
                . "\n"
                . "Sudah Disetor: $"
                . number_format(
                    $sudahDisetor,
                    2
                )
                . "\n"
                . "⚠️ Belum Disetor: $"
                . number_format(
                    $belumDisetor,
                    2
                )
                . "\n\n";
        }

        /*
         * Grand total.
         */
        $grandReceived =
            (float) $transactions->sum(
                'staff_received_amount'
            );

        $grandDeposited =
            (float) $transactions->sum(
                'staff_deposited_amount'
            );

        $grandBalance =
            (float) $transactions->sum(
                'staff_balance'
            );

        $totalTransactions =
            $transactions->count();

        $totalVoucher =
            (int) $transactions->sum(
                'quantity'
            );

        $message .=
            "━━━━━━━━━━━━━━━━━━\n"
            . "💰 <b>TOTAL SETORAN PETUGAS</b>\n\n"
            . "Transaksi: {$totalTransactions}\n"
            . "Jumlah Voucher: {$totalVoucher}\n"
            . "Uang Diterima: $"
            . number_format(
                $grandReceived,
                2
            )
            . "\n"
            . "Sudah Disetor: $"
            . number_format(
                $grandDeposited,
                2
            )
            . "\n"
            . "⚠️ Belum Disetor: $"
            . number_format(
                $grandBalance,
                2
            )
            . "\n\n"
            . "📅 "
            . now()->format('d-m-Y')
            . "\n\n"
            . "⚠️ Mohon petugas segera melakukan setoran.";

        $this->sendTelegram(
            (string) $token,
            (string) $chatId,
            $message
        );

        $this->info(
            'Reminder setoran petugas berhasil dikirim.'
        );

        return SymfonyCommand::SUCCESS;
    }

    private function sendTelegram(
        string $token,
        string $chatId,
        string $message
    ): void {
        $response =
            Http::timeout(15)->post(
                "https://api.telegram.org/bot{$token}/sendMessage",
                [
                    'chat_id' =>
                        $chatId,

                    'text' =>
                        $message,

                    'parse_mode' =>
                        'HTML',
                ]
            );

        if (!$response->successful()) {
            throw new \RuntimeException(
                'Telegram error: '
                . $response->body()
            );
        }
    }
}