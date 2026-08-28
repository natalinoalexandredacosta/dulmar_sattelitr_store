<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\TvVoucherTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class TelegramPollCommand extends Command
{
    protected $signature = 'telegram:poll';

    protected $description = 'Membaca command Telegram Dulmar Inventory';

    public function handle(): int
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        if (!$token) {
            $this->error('TELEGRAM_BOT_TOKEN belum diisi di .env');

            return SymfonyCommand::FAILURE;
        }

        $this->info('======================================');
        $this->info(' DULMAR INVENTORY TELEGRAM BOT');
        $this->info('======================================');
        $this->info('Bot aktif...');
        $this->info(
            'Command: /help /today /tvreport /receivable /unpaid /stock'
        );
        $this->info('Tekan CTRL + C untuk berhenti.');
        $this->newLine();

        $offset = null;

        while (true) {
            try {
                $response = Http::timeout(35)->get(
                    "https://api.telegram.org/bot{$token}/getUpdates",
                    [
                        'timeout' => 30,
                        'offset' => $offset,
                    ]
                );

                if (!$response->successful()) {
                    $this->error(
                        'Telegram API Error: '
                        . $response->body()
                    );

                    sleep(3);

                    continue;
                }

                $updates = $response->json(
                    'result',
                    []
                );

                foreach ($updates as $update) {
                    $offset =
                        $update['update_id'] + 1;

                    if (!isset($update['message'])) {
                        continue;
                    }

                    $message =
                        $update['message'];

                    $chatId =
                        $message['chat']['id']
                        ?? null;

                    $chatType =
                        $message['chat']['type']
                        ?? '-';

                    $chatTitle =
                        $message['chat']['title']
                        ?? 'Private Chat';

                    $text =
                        trim(
                            $message['text']
                            ?? ''
                        );

                    if (!$chatId || $text === '') {
                        continue;
                    }

                    /*
                     * Tampilkan Chat ID di terminal.
                     * Ini digunakan untuk mendapatkan
                     * TELEGRAM_GROUP_CHAT_ID.
                     */
                    $this->info(
                        "Command masuk: {$text}"
                        . " | Chat ID: {$chatId}"
                        . " | Type: {$chatType}"
                        . " | Chat: {$chatTitle}"
                    );

                    $this->processCommand(
                        $chatId,
                        $text
                    );
                }
            } catch (\Throwable $e) {
                $this->error(
                    'Error: '
                    . $e->getMessage()
                );

                sleep(3);
            }
        }
    }

    private function processCommand(
        int|string $chatId,
        string $text
    ): void {
        $allowedChatId =
            (string) env(
                'TELEGRAM_CHAT_ID'
            );

        /*
         * Command hanya boleh dijalankan
         * dari private Chat ID admin.
         */
        if (
            (string) $chatId
            !== $allowedChatId
        ) {
            /*
             * Gunakan sendDirectMessage agar pesan
             * "tidak memiliki akses" tidak ikut
             * dikirim ulang ke group lain.
             */
            $this->sendDirectMessage(
                $chatId,
                '⛔ Anda tidak memiliki akses ke Dulmar Inventory.'
            );

            return;
        }

        $command =
            preg_replace(
                '/@[\w_]+/',
                '',
                $text
            );

        $command =
            strtolower(
                trim($command ?? '')
            );

        if (
            $command === '/start'
            || $command === '/help'
        ) {
            $this->sendHelp(
                $chatId
            );

            return;
        }

        if ($command === '/today') {
            $this->sendTodaySummary(
                $chatId
            );

            return;
        }

        if ($command === '/tvreport') {
            $this->sendTvReport(
                $chatId
            );

            return;
        }

        if ($command === '/receivable') {
            $this->sendReceivableSummary(
                $chatId
            );

            return;
        }

        if ($command === '/unpaid') {
            $this->sendUnpaidSummary(
                $chatId
            );

            return;
        }

        if ($command === '/stock') {
            $this->sendStockSummary(
                $chatId
            );

            return;
        }

        $this->sendMessage(
            $chatId,
            "❓ Command tidak dikenal.\n\n"
            . "Ketik /help untuk melihat daftar command."
        );
    }

    private function sendHelp(
        int|string $chatId
    ): void {
        $message =
            "🤖 <b>DULMAR INVENTORY BOT</b>\n\n"
            . "Bot ini membantu monitoring stok, "
            . "TV Voucher, piutang customer dan setoran petugas.\n\n"

            . "<b>Command tersedia (admin only):</b>\n\n"

            . "📊 <b>/today</b>\n"
            . "Rekap TV Voucher hari ini per petugas.\n\n"

            . "📺 <b>/tvreport</b>\n"
            . "Laporan TV Voucher semua periode per petugas.\n\n"

            . "💰 <b>/receivable</b>\n"
            . "Lihat customer yang belum lunas dan sisa tagihannya.\n\n"

            . "⚠️ <b>/unpaid</b>\n"
            . "Lihat petugas dan total uang yang belum disetor.\n\n"

            . "📦 <b>/stock</b>\n"
            . "Lihat stok barang saat ini.\n\n"

            . "❓ <b>/help</b>\n"
            . "Tampilkan bantuan ini.";

        $this->sendMessage(
            $chatId,
            $message
        );
    }

    private function sendTodaySummary(
        int|string $chatId
    ): void {
        $transactions =
            TvVoucherTransaction::query()
                ->whereDate(
                    'transaction_date',
                    now()->toDateString()
                )
                ->orderBy(
                    'filled_by'
                )
                ->get();

        if ($transactions->isEmpty()) {
            $this->sendMessage(
                $chatId,
                '📊 Belum ada transaksi TV Voucher hari ini.'
            );

            return;
        }

        $grouped =
            $transactions->groupBy(
                function ($transaction) {
                    return $transaction->filled_by
                        ?: 'Tidak diketahui';
                }
            );

        $message =
            "📊 <b>REKAP TV VOUCHER HARI INI</b>\n"
            . now()->format('d-m-Y')
            . "\n\n";

        foreach ($grouped as $name => $items) {
            $transactionCount =
                $items->count();

            $voucherCount =
                (int) $items->sum(
                    'quantity'
                );

            $totalAmount =
                (float) $items->sum(
                    'total_amount'
                );

            $paidAmount =
                (float) $items->sum(
                    'staff_deposited_amount'
                );

            $unpaidAmount =
                (float) $items->sum(
                    'staff_balance'
                );

            $message .=
                "👤 <b>{$name}</b>\n"
                . "Jumlah Transaksi: {$transactionCount}\n"
                . "Jumlah Voucher: {$voucherCount}\n"
                . "Total Uang: $"
                . number_format(
                    $totalAmount,
                    2
                )
                . "\n"
                . "✅ Sudah Setor: $"
                . number_format(
                    $paidAmount,
                    2
                )
                . "\n"
                . "⚠️ Belum Setor: $"
                . number_format(
                    $unpaidAmount,
                    2
                )
                . "\n\n";
        }

        $grandTransactionCount =
            $transactions->count();

        $grandVoucherCount =
            (int) $transactions->sum(
                'quantity'
            );

        $grandTotal =
            (float) $transactions->sum(
                'total_amount'
            );

        $grandPaid =
            (float) $transactions->sum(
                'staff_deposited_amount'
            );

        $grandUnpaid =
            (float) $transactions->sum(
                'staff_balance'
            );

        $message .=
            "━━━━━━━━━━━━━━━━━━\n"
            . "💰 <b>TOTAL SEMUA</b>\n\n"
            . "Jumlah Transaksi: {$grandTransactionCount}\n"
            . "Jumlah Voucher: {$grandVoucherCount}\n"
            . "Subtotal: $"
            . number_format(
                $grandTotal,
                2
            )
            . "\n"
            . "✅ Sudah Setor: $"
            . number_format(
                $grandPaid,
                2
            )
            . "\n"
            . "⚠️ Belum Setor: $"
            . number_format(
                $grandUnpaid,
                2
            );

        $this->sendMessage(
            $chatId,
            $message
        );
    }

    private function sendTvReport(
        int|string $chatId
    ): void {
        $transactions =
            TvVoucherTransaction::query()
                ->orderBy(
                    'filled_by'
                )
                ->orderBy(
                    'transaction_date'
                )
                ->get();

        if ($transactions->isEmpty()) {
            $this->sendMessage(
                $chatId,
                '📺 Belum ada transaksi TV Voucher.'
            );

            return;
        }

        $grouped =
            $transactions->groupBy(
                function ($transaction) {
                    return $transaction->filled_by
                        ?: 'Tidak diketahui';
                }
            );

        $message =
            "📺 <b>LAPORAN TV VOUCHER</b>\n\n"
            . "Periode: Semua transaksi s/d "
            . now()->format('d-m-Y')
            . "\n\n";

        foreach ($grouped as $name => $items) {
            $transactionCount =
                $items->count();

            $voucherCount =
                (int) $items->sum(
                    'quantity'
                );

            $totalAmount =
                (float) $items->sum(
                    'total_amount'
                );

            $paidAmount =
                (float) $items->sum(
                    'staff_deposited_amount'
                );

            $unpaidAmount =
                (float) $items->sum(
                    'staff_balance'
                );

            $message .=
                "👤 <b>{$name}</b>\n"
                . "Jumlah Transaksi: {$transactionCount}\n"
                . "Jumlah Voucher: {$voucherCount}\n"
                . "Total Uang: $"
                . number_format(
                    $totalAmount,
                    2
                )
                . "\n"
                . "✅ Sudah Setor: $"
                . number_format(
                    $paidAmount,
                    2
                )
                . "\n"
                . "⚠️ Belum Setor: $"
                . number_format(
                    $unpaidAmount,
                    2
                )
                . "\n\n";
        }

        $grandTransactions =
            $transactions->count();

        $grandVouchers =
            (int) $transactions->sum(
                'quantity'
            );

        $grandTotal =
            (float) $transactions->sum(
                'total_amount'
            );

        $grandPaid =
            (float) $transactions->sum(
                'staff_deposited_amount'
            );

        $grandUnpaid =
            (float) $transactions->sum(
                'staff_balance'
            );

        $message .=
            "━━━━━━━━━━━━━━━━━━\n"
            . "💰 <b>TOTAL SEMUA</b>\n\n"
            . "Jumlah Transaksi: {$grandTransactions}\n"
            . "Jumlah Voucher: {$grandVouchers}\n"
            . "Total Uang: $"
            . number_format(
                $grandTotal,
                2
            )
            . "\n"
            . "✅ Sudah Setor: $"
            . number_format(
                $grandPaid,
                2
            )
            . "\n"
            . "⚠️ Belum Setor: $"
            . number_format(
                $grandUnpaid,
                2
            );

        $this->sendMessage(
            $chatId,
            $message
        );
    }

    private function sendReceivableSummary(
        int|string $chatId
    ): void {
        $transactions =
            TvVoucherTransaction::query()
                ->with('customer')
                ->where(
                    'customer_balance',
                    '>',
                    0
                )
                ->orderBy(
                    'transaction_date'
                )
                ->orderBy(
                    'id'
                )
                ->get();

        if ($transactions->isEmpty()) {
            $this->sendMessage(
                $chatId,
                "✅ <b>PIUTANG CUSTOMER</b>\n\n"
                . "Tidak ada customer yang masih memiliki tagihan."
            );

            return;
        }

        $message =
            "💰 <b>PIUTANG CUSTOMER TV VOUCHER</b>\n\n";

        foreach ($transactions as $transaction) {
            $customerName =
                $transaction->customer_name
                ?: (
                    $transaction->customer?->customer_name
                    ?? 'Tidak diketahui'
                );

            $customerPhone =
                $transaction->customer_phone
                ?: (
                    $transaction->customer?->phone
                    ?? '-'
                );

            $customerAddress =
                $transaction->customer_address
                ?: (
                    $transaction->customer?->address
                    ?? '-'
                );

            $filledBy =
                $transaction->filled_by
                ?: '-';

            $provider =
                $transaction->provider
                ?: '-';

            $receiverNumber =
                $transaction->receiver_number
                ?: '-';

            $packageName =
                $transaction->package_name
                ?: '-';

            $total =
                number_format(
                    (float) $transaction->total_amount,
                    2
                );

            $paid =
                number_format(
                    (float) $transaction->customer_paid_amount,
                    2
                );

            $balance =
                number_format(
                    (float) $transaction->customer_balance,
                    2
                );

            $status =
                $transaction->customer_payment_status
                    === TvVoucherTransaction::CUSTOMER_PAYMENT_PARTIAL
                    ? 'Bayar Sebagian'
                    : 'Belum Bayar';

            $message .=
                "👤 <b>{$customerName}</b>\n"
                . "No HP: {$customerPhone}\n"
                . "Tempat Tinggal: {$customerAddress}\n"
                . "Diisi Oleh: {$filledBy}\n"
                . "Provider: {$provider}\n"
                . "No Receiver: {$receiverNumber}\n"
                . "Paket: {$packageName}\n"
                . "Total: \${$total}\n"
                . "Sudah Bayar: \${$paid}\n"
                . "⚠️ Sisa: \${$balance}\n"
                . "Status: {$status}\n\n";

            if (strlen($message) >= 3500) {
                $this->sendMessage(
                    $chatId,
                    $message
                );

                $message =
                    "💰 <b>LANJUTAN PIUTANG CUSTOMER</b>\n\n";
            }
        }

        $grandBalance =
            (float) $transactions->sum(
                'customer_balance'
            );

        $message .=
            "━━━━━━━━━━━━━━━━━━\n"
            . "<b>TOTAL PIUTANG CUSTOMER</b>\n"
            . "$"
            . number_format(
                $grandBalance,
                2
            );

        $this->sendMessage(
            $chatId,
            $message
        );
    }

    private function sendUnpaidSummary(
        int|string $chatId
    ): void {
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
                ->get();

        if ($transactions->isEmpty()) {
            $this->sendMessage(
                $chatId,
                '✅ Tidak ada setoran TV Voucher yang tertunda.'
            );

            return;
        }

        $grouped =
            $transactions->groupBy(
                function ($transaction) {
                    return $transaction->filled_by
                        ?: 'Tidak diketahui';
                }
            );

        $message =
            "⚠️ <b>BELUM SETOR TV VOUCHER</b>\n\n";

        foreach ($grouped as $name => $items) {
            $transactionCount =
                $items->count();

            $voucherCount =
                (int) $items->sum(
                    'quantity'
                );

            $totalUnpaid =
                (float) $items->sum(
                    'staff_balance'
                );

            $message .=
                "👤 <b>{$name}</b>\n"
                . "Transaksi: {$transactionCount}\n"
                . "Voucher: {$voucherCount}\n"
                . "Belum Setor: $"
                . number_format(
                    $totalUnpaid,
                    2
                )
                . "\n\n";
        }

        $grandUnpaid =
            (float) $transactions->sum(
                'staff_balance'
            );

        $message .=
            "━━━━━━━━━━━━━━━━━━\n"
            . "💰 <b>TOTAL BELUM SETOR</b>\n"
            . "$"
            . number_format(
                $grandUnpaid,
                2
            );

        $this->sendMessage(
            $chatId,
            $message
        );
    }

    private function sendStockSummary(
        int|string $chatId
    ): void {
        $products =
            Product::query()
                ->orderBy(
                    'product_name'
                )
                ->get();

        if ($products->isEmpty()) {
            $this->sendMessage(
                $chatId,
                '📦 Belum ada produk di Dulmar Inventory.'
            );

            return;
        }

        $message =
            "📦 <b>STOK BARANG DULMAR</b>\n\n";

        foreach ($products as $index => $product) {
            $number =
                $index + 1;

            $stock =
                (int) $product->stock;

            if ($stock <= 0) {
                $status = '🔴';
            } elseif ($stock <= 3) {
                $status = '⚠️';
            } else {
                $status = '✅';
            }

            $message .=
                "{$number}. {$status} "
                . "<b>{$product->product_name}</b>\n"
                . "Stok: {$stock} unit\n\n";

            if (strlen($message) >= 3500) {
                $this->sendMessage(
                    $chatId,
                    $message
                );

                $message =
                    "📦 <b>LANJUTAN STOK BARANG</b>\n\n";
            }
        }

        $totalProducts =
            $products->count();

        $totalStock =
            (int) $products->sum(
                'stock'
            );

        $outOfStock =
            $products
                ->filter(
                    function ($product) {
                        return (int) $product->stock
                            <= 0;
                    }
                )
                ->count();

        $lowStock =
            $products
                ->filter(
                    function ($product) {
                        $stock =
                            (int) $product->stock;

                        return $stock > 0
                            && $stock <= 3;
                    }
                )
                ->count();

        $message .=
            "━━━━━━━━━━━━━━━━━━\n"
            . "📊 <b>RINGKASAN STOK</b>\n\n"
            . "Jenis Produk: {$totalProducts}\n"
            . "Total Unit: {$totalStock}\n"
            . "⚠️ Stok Rendah: {$lowStock}\n"
            . "🔴 Stok Habis: {$outOfStock}";

        $this->sendMessage(
            $chatId,
            $message
        );
    }

    /**
     * Kirim response ke private admin
     * lalu copy response yang sama ke group.
     */
    private function sendMessage(
        int|string $chatId,
        string $message
    ): void {
        /*
         * Pertama kirim ke private/admin.
         */
        $this->sendDirectMessage(
            $chatId,
            $message
        );

        /*
         * Setelah itu kirim copy ke group.
         */
        $groupChatId =
            env('TELEGRAM_GROUP_CHAT_ID');

        if (!$groupChatId) {
            $this->warn(
                'TELEGRAM_GROUP_CHAT_ID belum diisi di .env'
            );

            return;
        }

        /*
         * Hindari pesan terkirim dua kali apabila
         * chat sumber ternyata sama dengan group.
         */
        if (
            (string) $groupChatId
            === (string) $chatId
        ) {
            return;
        }

        $this->sendDirectMessage(
            $groupChatId,
            $message
        );

        $this->info(
            "✓ Laporan juga dikirim ke group: {$groupChatId}"
        );
    }

    /**
     * Kirim langsung ke satu Chat ID.
     * Method ini TIDAK melakukan copy ke group.
     */
    private function sendDirectMessage(
        int|string $chatId,
        string $message
    ): void {
        $token =
            env('TELEGRAM_BOT_TOKEN');

        if (!$token) {
            $this->error(
                'TELEGRAM_BOT_TOKEN belum tersedia.'
            );

            return;
        }

        $response =
            Http::timeout(10)->post(
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
            $this->error(
                "Gagal kirim Telegram ke Chat ID {$chatId}: "
                . $response->body()
            );

            return;
        }

        $this->info(
            "✓ Pesan berhasil dikirim ke Chat ID: {$chatId}"
        );
    }
}