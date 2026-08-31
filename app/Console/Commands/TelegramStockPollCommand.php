<?php

namespace App\Console\Commands;

use App\Exports\InventoryReportExport;
use App\Models\CashTransaction;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Services\StockTelegramService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TelegramStockPollCommand extends Command
{
    protected $signature = 'telegram:stock-poll';

    protected $description =
        'Telegram bot khusus stok barang, laporan, dan approval kas Dulmar';


    /**
     * Menyimpan sementara transaksi yang sedang
     * menunggu alasan penolakan dari Telegram.
     *
     * Format:
     *
     * [
     *     'chat_id' => [
     *         'transaction_id' => 10,
     *         'message_id' => 123,
     *         'rejected_by' => 'Telegram: Admin',
     *     ],
     * ]
     */
    private array $pendingRejections = [];


    /**
     * Jalankan long polling Telegram.
     */
    public function handle()
    {
        $token =
            env(
                'TELEGRAM_STOCK_BOT_TOKEN'
            );


        if (!$token) {
            $this->error(
                'TELEGRAM_STOCK_BOT_TOKEN belum diisi di .env'
            );

            return self::FAILURE;
        }


        $this->info(
            '======================================'
        );

        $this->info(
            ' DULMAR STOCK TELEGRAM BOT'
        );

        $this->info(
            '======================================'
        );

        $this->info(
            'Command: /help /chatid /stock /lowstock /stockin /stockout /sales /report'
        );

        $this->info(
            'Approval Kas: Telegram Inline Button'
        );

        $this->info(
            'Tekan CTRL + C untuk berhenti.'
        );


        $offset = 0;


        while (true) {
            try {
                $response =
                    Http::timeout(40)
                        ->get(
                            "https://api.telegram.org/bot{$token}/getUpdates",
                            [
                                'timeout' =>
                                    30,

                                'offset' =>
                                    $offset,

                                'allowed_updates' => [
                                    'message',
                                    'callback_query',
                                ],
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


                $updates =
                    $response->json(
                        'result',
                        []
                    );


                foreach ($updates as $update) {
                    $offset =
                        (
                            $update['update_id']
                            ?? 0
                        )
                        + 1;


                    /*
                    |--------------------------------------------------------------------------
                    | CALLBACK QUERY
                    |--------------------------------------------------------------------------
                    |
                    | Untuk tombol:
                    |
                    | ✅ APPROVE
                    | ❌ TOLAK
                    |
                    */

                    if (
                        isset(
                            $update['callback_query']
                        )
                    ) {
                        $this->handleCallbackQuery(
                            $update['callback_query']
                        );

                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | MESSAGE
                    |--------------------------------------------------------------------------
                    */

                    $message =
                        $update['message']
                        ?? null;


                    if (!$message) {
                        continue;
                    }


                    $chatId =
                        $message['chat']['id']
                        ?? null;


                    $text =
                        trim(
                            $message['text']
                            ?? ''
                        );


                    if (
                        !$chatId
                        ||
                        $text === ''
                    ) {
                        continue;
                    }


                    $chatId =
                        (string) $chatId;


                    $this->info(
                        "Message masuk: {$text}"
                        . " | Chat ID: {$chatId}"
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | MENUNGGU ALASAN PENOLAKAN
                    |--------------------------------------------------------------------------
                    */

                    if (
                        isset(
                            $this->pendingRejections[
                                $chatId
                            ]
                        )
                    ) {
                        $this->handleRejectionReason(
                            $text,
                            $chatId
                        );

                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | COMMAND BIASA
                    |--------------------------------------------------------------------------
                    */

                    $this->handleCommand(
                        $text,
                        $chatId
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


    /*
    |--------------------------------------------------------------------------
    | CALLBACK APPROVE / REJECT
    |--------------------------------------------------------------------------
    */

    private function handleCallbackQuery(
        array $callbackQuery
    ): void {
        $callbackId =
            (string) (
                $callbackQuery['id']
                ?? ''
            );


        $data =
            trim(
                (string) (
                    $callbackQuery['data']
                    ?? ''
                )
            );


        $chatId =
            (string) (
                $callbackQuery[
                    'message'
                ][
                    'chat'
                ][
                    'id'
                ]
                ?? ''
            );


        $messageId =
            (int) (
                $callbackQuery[
                    'message'
                ][
                    'message_id'
                ]
                ?? 0
            );


        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        |
        | Hanya Telegram admin yang terdaftar
        | pada TELEGRAM_STOCK_CHAT_ID yang boleh
        | memproses transaksi kas.
        |
        */

        if (
            !$this->isAuthorizedAdminChat(
                $chatId
            )
        ) {
            $this->telegram()
                ->answerCallbackQuery(
                    $callbackId,
                    'Anda tidak memiliki akses untuk approval Kas.',
                    true
                );

            return;
        }


        $adminName =
            $this->telegramUserName(
                $callbackQuery['from']
                ?? []
            );


        /*
        |--------------------------------------------------------------------------
        | APPROVE
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                '/^cash_approve_(\d+)$/',
                $data,
                $matches
            )
        ) {
            $transactionId =
                (int) $matches[1];


            $this->approveCashFromTelegram(
                $transactionId,
                $chatId,
                $messageId,
                $callbackId,
                $adminName
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | REJECT
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                '/^cash_reject_(\d+)$/',
                $data,
                $matches
            )
        ) {
            $transactionId =
                (int) $matches[1];


            $this->startRejectCashFromTelegram(
                $transactionId,
                $chatId,
                $messageId,
                $callbackId,
                $adminName
            );

            return;
        }


        $this->telegram()
            ->answerCallbackQuery(
                $callbackId,
                'Action Telegram tidak dikenal.',
                true
            );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE CASH VIA TELEGRAM
    |--------------------------------------------------------------------------
    */

    private function approveCashFromTelegram(
        int $transactionId,
        string $chatId,
        int $messageId,
        string $callbackId,
        string $adminName
    ): void {
        try {
            $result =
                DB::transaction(
                    function () use (
                        $transactionId,
                        $adminName
                    ) {
                        $transaction =
                            CashTransaction::query()
                                ->lockForUpdate()
                                ->find(
                                    $transactionId
                                );


                        if (!$transaction) {
                            return [
                                'status' =>
                                    'not_found',

                                'transaction' =>
                                    null,
                            ];
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | CEK STATUS
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $transaction
                                ->approval_status
                            !== 'pending'
                        ) {
                            return [
                                'status' =>
                                    'already_processed',

                                'transaction' =>
                                    $transaction,
                            ];
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | CEK SALDO
                        |--------------------------------------------------------------------------
                        |
                        | Cash keluar baru boleh approved kalau
                        | saldo saat ini masih cukup.
                        |
                        */

                        if (
                            $transaction->type
                            === 'expense'
                        ) {
                            $balance =
                                CashTransaction::currentBalance();


                            if (
                                (float) $transaction->amount
                                > $balance
                            ) {
                                return [
                                    'status' =>
                                        'insufficient_balance',

                                    'transaction' =>
                                        $transaction,

                                    'balance' =>
                                        $balance,
                                ];
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | APPROVE
                        |--------------------------------------------------------------------------
                        */

                        $transaction
                            ->approval_status =
                            'approved';


                        $transaction
                            ->approved_by =
                            $adminName;


                        $transaction
                            ->approved_at =
                            now();


                        $transaction
                            ->rejection_reason =
                            null;


                        $transaction->save();


                        return [
                            'status' =>
                                'approved',

                            'transaction' =>
                                $transaction,
                        ];
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | TIDAK DITEMUKAN
            |--------------------------------------------------------------------------
            */

            if (
                $result['status']
                === 'not_found'
            ) {
                $this->telegram()
                    ->answerCallbackQuery(
                        $callbackId,
                        'Transaksi tidak ditemukan.',
                        true
                    );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | SUDAH DIPROSES
            |--------------------------------------------------------------------------
            */

            if (
                $result['status']
                === 'already_processed'
            ) {
                $transaction =
                    $result[
                        'transaction'
                    ];


                $this->telegram()
                    ->answerCallbackQuery(
                        $callbackId,
                        'Transaksi sudah diproses sebelumnya.',
                        true
                    );


                if (
                    $transaction
                    &&
                    $messageId > 0
                ) {
                    $this->telegram()
                        ->editMessage(
                            $chatId,
                            $messageId,
                            $this->buildProcessedCashMessage(
                                $transaction
                            )
                        );
                }

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | SALDO TIDAK CUKUP
            |--------------------------------------------------------------------------
            */

            if (
                $result['status']
                === 'insufficient_balance'
            ) {
                $balance =
                    (float) (
                        $result['balance']
                        ?? 0
                    );


                $this->telegram()
                    ->answerCallbackQuery(
                        $callbackId,
                        'Saldo kas tidak mencukupi.',
                        true
                    );


                $this->sendMessage(
                    $chatId,
                    "<b>⚠️ APPROVAL GAGAL</b>\n\n"
                    . "Saldo kas saat ini hanya <b>$"
                    . number_format(
                        $balance,
                        2
                    )
                    . "</b>.\n\n"
                    . "Transaksi belum disetujui."
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | BERHASIL
            |--------------------------------------------------------------------------
            */

            /** @var CashTransaction $transaction */
            $transaction =
                $result[
                    'transaction'
                ];


            $transaction->refresh();


            $this->telegram()
                ->answerCallbackQuery(
                    $callbackId,
                    'Transaksi berhasil disetujui.',
                    false
                );


            /*
            |--------------------------------------------------------------------------
            | EDIT PESAN ASLI + HAPUS BUTTON
            |--------------------------------------------------------------------------
            */

            if ($messageId > 0) {
                $this->telegram()
                    ->editMessage(
                        $chatId,
                        $messageId,
                        $this->buildProcessedCashMessage(
                            $transaction
                        )
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | KONFIRMASI
            |--------------------------------------------------------------------------
            */

            $this->sendMessage(
                $chatId,
                $this->buildApprovedCashMessage(
                    $transaction
                )
            );


            $this->info(
                'Cash transaction #'
                . $transaction->id
                . ' approved via Telegram.'
            );

        } catch (\Throwable $e) {
            $this->error(
                'Telegram cash approve error: '
                . $e->getMessage()
            );


            $this->telegram()
                ->answerCallbackQuery(
                    $callbackId,
                    'Terjadi error saat approval.',
                    true
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | MULAI PROSES REJECT
    |--------------------------------------------------------------------------
    */

    private function startRejectCashFromTelegram(
        int $transactionId,
        string $chatId,
        int $messageId,
        string $callbackId,
        string $adminName
    ): void {
        $transaction =
            CashTransaction::find(
                $transactionId
            );


        if (!$transaction) {
            $this->telegram()
                ->answerCallbackQuery(
                    $callbackId,
                    'Transaksi tidak ditemukan.',
                    true
                );

            return;
        }


        if (
            $transaction
                ->approval_status
            !== 'pending'
        ) {
            $this->telegram()
                ->answerCallbackQuery(
                    $callbackId,
                    'Transaksi sudah diproses sebelumnya.',
                    true
                );


            if ($messageId > 0) {
                $this->telegram()
                    ->editMessage(
                        $chatId,
                        $messageId,
                        $this->buildProcessedCashMessage(
                            $transaction
                        )
                    );
            }

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN STATE SEMENTARA
        |--------------------------------------------------------------------------
        */

        $this->pendingRejections[
            $chatId
        ] = [
            'transaction_id' =>
                $transactionId,

            'message_id' =>
                $messageId,

            'rejected_by' =>
                $adminName,
        ];


        $this->telegram()
            ->answerCallbackQuery(
                $callbackId,
                'Silakan kirim alasan penolakan.',
                false
            );


        $amount =
            number_format(
                (float) $transaction->amount,
                2
            );


        $this->sendMessage(
            $chatId,
            "<b>❌ ALASAN PENOLAKAN DIPERLUKAN</b>\n\n"
            . "Transaksi: <b>#{$transaction->id}</b>\n"
            . "Kategori: <b>"
            . $this->escape(
                $transaction->category
                ?: '-'
            )
            . "</b>\n"
            . "Jumlah: <b>\${$amount}</b>\n\n"
            . "Silakan kirim alasan kenapa transaksi ini ditolak.\n\n"
            . "Contoh:\n"
            . "<i>Pengeluaran belum mendapatkan persetujuan.</i>\n\n"
            . "Ketik <b>/cancel</b> untuk membatalkan proses penolakan."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROSES ALASAN REJECT
    |--------------------------------------------------------------------------
    */

    private function handleRejectionReason(
        string $text,
        string $chatId
    ): void {
        /*
        |--------------------------------------------------------------------------
        | CANCEL
        |--------------------------------------------------------------------------
        */

        if (
            strtolower(
                trim($text)
            )
            === '/cancel'
        ) {
            unset(
                $this->pendingRejections[
                    $chatId
                ]
            );


            $this->sendMessage(
                $chatId,
                "✅ Proses penolakan dibatalkan.\n\n"
                . "Transaksi masih berstatus Pending."
            );

            return;
        }


        $reason =
            trim($text);


        if (
            mb_strlen(
                $reason
            )
            < 3
        ) {
            $this->sendMessage(
                $chatId,
                "⚠️ Alasan penolakan terlalu pendek.\n\n"
                . "Silakan tulis alasan yang jelas."
            );

            return;
        }


        if (
            mb_strlen(
                $reason
            )
            > 1000
        ) {
            $this->sendMessage(
                $chatId,
                "⚠️ Alasan penolakan maksimal 1000 karakter."
            );

            return;
        }


        $pending =
            $this->pendingRejections[
                $chatId
            ];


        $transactionId =
            (int) (
                $pending[
                    'transaction_id'
                ]
                ?? 0
            );


        $messageId =
            (int) (
                $pending[
                    'message_id'
                ]
                ?? 0
            );


        $rejectedBy =
            (string) (
                $pending[
                    'rejected_by'
                ]
                ?? 'Telegram Admin'
            );


        /*
        |--------------------------------------------------------------------------
        | HAPUS STATE
        |--------------------------------------------------------------------------
        |
        | Dihapus dulu agar pesan berikutnya tidak dianggap alasan lagi.
        |
        */

        unset(
            $this->pendingRejections[
                $chatId
            ]
        );


        try {
            $result =
                DB::transaction(
                    function () use (
                        $transactionId,
                        $reason,
                        $rejectedBy
                    ) {
                        $transaction =
                            CashTransaction::query()
                                ->lockForUpdate()
                                ->find(
                                    $transactionId
                                );


                        if (!$transaction) {
                            return [
                                'status' =>
                                    'not_found',

                                'transaction' =>
                                    null,
                            ];
                        }


                        if (
                            $transaction
                                ->approval_status
                            !== 'pending'
                        ) {
                            return [
                                'status' =>
                                    'already_processed',

                                'transaction' =>
                                    $transaction,
                            ];
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | REJECT
                        |--------------------------------------------------------------------------
                        */

                        $transaction
                            ->approval_status =
                            'rejected';


                        $transaction
                            ->approved_by =
                            $rejectedBy;


                        $transaction
                            ->approved_at =
                            now();


                        $transaction
                            ->rejection_reason =
                            $reason;


                        $transaction->save();


                        return [
                            'status' =>
                                'rejected',

                            'transaction' =>
                                $transaction,
                        ];
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | NOT FOUND
            |--------------------------------------------------------------------------
            */

            if (
                $result['status']
                === 'not_found'
            ) {
                $this->sendMessage(
                    $chatId,
                    '❌ Transaksi tidak ditemukan.'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | ALREADY PROCESSED
            |--------------------------------------------------------------------------
            */

            if (
                $result['status']
                === 'already_processed'
            ) {
                $transaction =
                    $result[
                        'transaction'
                    ];


                $this->sendMessage(
                    $chatId,
                    "⚠️ Transaksi sudah diproses sebelumnya."
                );


                if (
                    $transaction
                    &&
                    $messageId > 0
                ) {
                    $this->telegram()
                        ->editMessage(
                            $chatId,
                            $messageId,
                            $this->buildProcessedCashMessage(
                                $transaction
                            )
                        );
                }

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | SUCCESS
            |--------------------------------------------------------------------------
            */

            /** @var CashTransaction $transaction */
            $transaction =
                $result[
                    'transaction'
                ];


            $transaction->refresh();


            /*
            |--------------------------------------------------------------------------
            | EDIT PESAN REQUEST
            |--------------------------------------------------------------------------
            */

            if ($messageId > 0) {
                $this->telegram()
                    ->editMessage(
                        $chatId,
                        $messageId,
                        $this->buildProcessedCashMessage(
                            $transaction
                        )
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | KIRIM HASIL REJECT
            |--------------------------------------------------------------------------
            */

            $this->sendMessage(
                $chatId,
                $this->buildRejectedCashMessage(
                    $transaction
                )
            );


            $this->info(
                'Cash transaction #'
                . $transaction->id
                . ' rejected via Telegram.'
            );

        } catch (\Throwable $e) {
            $this->error(
                'Telegram cash reject error: '
                . $e->getMessage()
            );


            $this->sendMessage(
                $chatId,
                "❌ Gagal menolak transaksi.\n\n"
                . $this->escape(
                    $e->getMessage()
                )
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | COMMAND
    |--------------------------------------------------------------------------
    */

    private function handleCommand(
        string $text,
        string $chatId
    ): void {
        $command =
            strtolower(
                trim(
                    explode(
                        ' ',
                        $text
                    )[0]
                )
            );


        if (
            $command === '/start'
            ||
            $command === '/help'
        ) {
            $this->sendHelp(
                $chatId
            );

            return;
        }


        if (
            $command === '/chatid'
        ) {
            $this->sendMessage(
                $chatId,
                "<b>🆔 CHAT ID BOT STOK</b>\n\n"
                . "Chat ID Anda:\n"
                . "<code>{$chatId}</code>"
            );

            return;
        }


        if (
            $command === '/stock'
        ) {
            $this->sendStock(
                $chatId
            );

            return;
        }


        if (
            $command === '/lowstock'
        ) {
            $this->sendLowStock(
                $chatId
            );

            return;
        }


        if (
            $command === '/stockin'
        ) {
            $this->sendStockIn(
                $chatId
            );

            return;
        }


        if (
            $command === '/stockout'
        ) {
            $this->sendStockOut(
                $chatId
            );

            return;
        }


        if (
            $command === '/sales'
        ) {
            $this->sendSales(
                $chatId
            );

            return;
        }


        if (
            $command === '/report'
        ) {
            $this->sendExcelReport(
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


    /*
    |--------------------------------------------------------------------------
    | HELP
    |--------------------------------------------------------------------------
    */

    private function sendHelp(
        string $chatId
    ): void {
        $message =
            "<b>📦 DULMAR INVENTORY BOT</b>\n\n"

            . "<b>/stock</b>\n"
            . "Lihat stok semua barang.\n\n"

            . "<b>/lowstock</b>\n"
            . "Lihat barang yang stoknya menipis atau habis.\n\n"

            . "<b>/stockin</b>\n"
            . "Lihat stok masuk dan transaksi terbaru.\n\n"

            . "<b>/stockout</b>\n"
            . "Lihat barang keluar / barang terjual.\n\n"

            . "<b>/sales</b>\n"
            . "Lihat total penjualan dan profit.\n\n"

            . "<b>/report</b>\n"
            . "Download laporan inventaris dalam format Excel.\n\n"

            . "<b>/chatid</b>\n"
            . "Lihat Chat ID Telegram Anda.\n\n"

            . "<b>/cancel</b>\n"
            . "Batalkan input alasan penolakan Kas.\n\n"

            . "<b>💰 APPROVAL KAS</b>\n"
            . "Request Cash Keluar / Pinjaman akan mempunyai tombol:\n"
            . "✅ APPROVE\n"
            . "❌ TOLAK\n\n"

            . "<b>/help</b>\n"
            . "Lihat daftar command.";


        $this->sendMessage(
            $chatId,
            $message
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STOCK
    |--------------------------------------------------------------------------
    */

    private function sendStock(
        string $chatId
    ): void {
        $products =
            Product::query()
                ->orderBy('category')
                ->orderBy('product_name')
                ->get();


        if ($products->isEmpty()) {
            $this->sendMessage(
                $chatId,
                '📦 Belum ada data produk.'
            );

            return;
        }


        $totalStock =
            (int) $products->sum(
                'stock'
            );


        $message =
            "<b>📦 STOK BARANG DULMAR</b>\n\n"
            . "<b>Total Stok:</b> "
            . $totalStock
            . " unit\n\n";


        foreach ($products as $product) {
            $stock =
                (int) $product->stock;


            if ($stock <= 0) {
                $status =
                    '🔴';

            } elseif ($stock <= 3) {
                $status =
                    '🟠';

            } else {
                $status =
                    '🟢';
            }


            $productName =
                $this->escape(
                    $product->product_name
                );


            $category =
                $this->escape(
                    $product->category
                    ?: '-'
                );


            $message .=
                "{$status} <b>{$productName}</b>\n"
                . "Kategori: {$category}\n"
                . "Stok: {$stock} unit\n"
                . "Harga Jual: $"
                . number_format(
                    (float) $product
                        ->selling_price,
                    2
                )
                . "\n\n";


            if (
                strlen(
                    $message
                )
                >= 3500
            ) {
                $this->sendMessage(
                    $chatId,
                    $message
                );


                $message =
                    "<b>📦 LANJUTAN STOK BARANG</b>\n\n";
            }
        }


        if (
            trim(
                $message
            )
            !== ''
        ) {
            $this->sendMessage(
                $chatId,
                $message
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOW STOCK
    |--------------------------------------------------------------------------
    */

    private function sendLowStock(
        string $chatId
    ): void {
        $products =
            Product::query()
                ->where(
                    'stock',
                    '<=',
                    3
                )
                ->orderBy('stock')
                ->orderBy('product_name')
                ->get();


        if ($products->isEmpty()) {
            $this->sendMessage(
                $chatId,
                '✅ Tidak ada barang dengan stok menipis.'
            );

            return;
        }


        $message =
            "<b>⚠️ STOK MENIPIS / HABIS</b>\n\n";


        foreach ($products as $product) {
            $stock =
                (int) $product->stock;


            $status =
                $stock <= 0
                    ? '🔴 HABIS'
                    : '🟠 MENIPIS';


            $productName =
                $this->escape(
                    $product->product_name
                );


            $category =
                $this->escape(
                    $product->category
                    ?: '-'
                );


            $message .=
                "{$status}\n"
                . "<b>{$productName}</b>\n"
                . "Kategori: {$category}\n"
                . "Sisa Stok: {$stock} unit\n\n";
        }


        $this->sendMessage(
            $chatId,
            $message
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STOCK IN
    |--------------------------------------------------------------------------
    */

    private function sendStockIn(
        string $chatId
    ): void {
        $totalTransactions =
            StockIn::count();


        $totalQuantity =
            (int) StockIn::sum(
                'quantity'
            );


        $latest =
            StockIn::query()
                ->with([
                    'product',
                    'supplier',
                ])
                ->orderByDesc(
                    'transaction_date'
                )
                ->orderByDesc(
                    'id'
                )
                ->limit(10)
                ->get();


        $message =
            "<b>📥 STOK MASUK</b>\n\n"
            . "<b>Total Transaksi:</b> "
            . $totalTransactions
            . "\n"
            . "<b>Total Barang Masuk:</b> "
            . $totalQuantity
            . " unit\n\n";


        if ($latest->isEmpty()) {
            $message .=
                'Belum ada transaksi stok masuk.';

        } else {
            $message .=
                "<b>10 Transaksi Terbaru:</b>\n\n";


            foreach ($latest as $item) {
                $productName =
                    $this->escape(
                        $item->product
                            ?->product_name
                        ?? 'Produk tidak ditemukan'
                    );


                $supplierName =
                    $this->escape(
                        $item->supplier
                            ?->supplier_name
                        ?? '-'
                    );


                $date =
                    $item->transaction_date
                        ? $item
                            ->transaction_date
                            ->format(
                                'd-m-Y'
                            )
                        : '-';


                $message .=
                    "• <b>{$productName}</b>\n"
                    . "Jumlah: +"
                    . (int) $item->quantity
                    . " unit\n"
                    . "Supplier: {$supplierName}\n"
                    . "Tanggal: {$date}\n\n";
            }
        }


        $this->sendMessage(
            $chatId,
            $message
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STOCK OUT
    |--------------------------------------------------------------------------
    */

    private function sendStockOut(
        string $chatId
    ): void {
        $totalTransactions =
            StockOut::count();


        $totalQuantity =
            (int) StockOut::sum(
                'quantity'
            );


        $totalSales =
            (float) StockOut::sum(
                'subtotal'
            );


        $latest =
            StockOut::query()
                ->with([
                    'product',
                    'customer',
                ])
                ->orderByDesc(
                    'transaction_date'
                )
                ->orderByDesc(
                    'id'
                )
                ->limit(10)
                ->get();


        $message =
            "<b>📤 STOK KELUAR / PENJUALAN</b>\n\n"

            . "<b>Total Transaksi:</b> "
            . $totalTransactions
            . "\n"

            . "<b>Total Barang Terjual:</b> "
            . $totalQuantity
            . " unit\n"

            . "<b>Total Penjualan:</b> $"
            . number_format(
                $totalSales,
                2
            )
            . "\n\n";


        if ($latest->isEmpty()) {
            $message .=
                'Belum ada transaksi barang keluar.';

        } else {
            $message .=
                "<b>10 Penjualan Terbaru:</b>\n\n";


            foreach ($latest as $item) {
                $productName =
                    $this->escape(
                        $item->product
                            ?->product_name
                        ?? 'Produk tidak ditemukan'
                    );


                $customerName =
                    $this->escape(
                        $item->customer
                            ?->customer_name
                        ?? '-'
                    );


                $subtotal =
                    number_format(
                        (float) $item->subtotal,
                        2
                    );


                $date =
                    $item->transaction_date
                        ? $item
                            ->transaction_date
                            ->format(
                                'd-m-Y'
                            )
                        : '-';


                $message .=
                    "• <b>{$productName}</b>\n"
                    . "Jumlah: "
                    . (int) $item->quantity
                    . " unit\n"
                    . "Customer: {$customerName}\n"
                    . "Total: \${$subtotal}\n"
                    . "Tanggal: {$date}\n\n";
            }
        }


        $this->sendMessage(
            $chatId,
            $message
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SALES
    |--------------------------------------------------------------------------
    */

    private function sendSales(
        string $chatId
    ): void {
        $totalTransactions =
            StockOut::count();


        $totalQuantity =
            (int) StockOut::sum(
                'quantity'
            );


        $totalSales =
            (float) StockOut::sum(
                'subtotal'
            );


        $totalProfit =
            (float) StockOut::sum(
                'total_profit'
            );


        $totalCustomerPaid =
            (float) StockOut::sum(
                'customer_paid_amount'
            );


        $totalCustomerBalance =
            (float) StockOut::sum(
                'customer_balance'
            );


        $message =
            "<b>💰 REPORT PENJUALAN BARANG</b>\n\n"

            . "<b>Jumlah Transaksi:</b> "
            . $totalTransactions
            . "\n"

            . "<b>Barang Terjual:</b> "
            . $totalQuantity
            . " unit\n\n"

            . "<b>Total Penjualan:</b> $"
            . number_format(
                $totalSales,
                2
            )
            . "\n"

            . "<b>Total Profit:</b> $"
            . number_format(
                $totalProfit,
                2
            )
            . "\n\n"

            . "<b>Pembayaran Customer:</b> $"
            . number_format(
                $totalCustomerPaid,
                2
            )
            . "\n"

            . "<b>Piutang Customer:</b> $"
            . number_format(
                $totalCustomerBalance,
                2
            );


        $this->sendMessage(
            $chatId,
            $message
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXCEL REPORT
    |--------------------------------------------------------------------------
    */

    private function sendExcelReport(
        string $chatId
    ): void {
        $token =
            env(
                'TELEGRAM_STOCK_BOT_TOKEN'
            );


        if (!$token) {
            $this->error(
                'TELEGRAM_STOCK_BOT_TOKEN belum tersedia.'
            );

            return;
        }


        $startDate = null;

        $endDate = null;


        $fileName =
            'laporan-inventaris-'
            . now()->format(
                'Y-m-d-His'
            )
            . '.xlsx';


        $relativePath =
            'telegram-reports/'
            . $fileName;


        try {
            /*
            |--------------------------------------------------------------------------
            | BUAT EXCEL
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
                    'app/'
                    . $relativePath
                );


            if (
                !file_exists(
                    $absolutePath
                )
            ) {
                $this->sendMessage(
                    $chatId,
                    '❌ File report Excel gagal dibuat.'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | INFO
            |--------------------------------------------------------------------------
            */

            $this->sendMessage(
                $chatId,
                "<b>📊 DULMAR INVENTORY REPORT</b>\n\n"
                . "Periode: Semua transaksi sampai "
                . now()->format(
                    'd-m-Y'
                )
                . "\n\n"
                . "⏳ File Excel sedang dikirim..."
            );


            /*
            |--------------------------------------------------------------------------
            | SEND DOCUMENT
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
                                "📊 DULMAR INVENTORY REPORT\n"
                                . "Tanggal: "
                                . now()->format(
                                    'd-m-Y H:i'
                                ),
                        ]
                    );


            if (
                !$response->successful()
            ) {
                $this->error(
                    'Gagal mengirim Excel: '
                    . $response->body()
                );


                $this->sendMessage(
                    $chatId,
                    '❌ Gagal mengirim file Excel.'
                );

                return;
            }


            $this->info(
                '✓ Report Excel berhasil dikirim ke Telegram.'
            );

        } catch (\Throwable $e) {
            $this->error(
                'Report Excel Error: '
                . $e->getMessage()
            );


            $this->sendMessage(
                $chatId,
                "❌ Gagal membuat report Excel.\n\n"
                . $this->escape(
                    $e->getMessage()
                )
            );

        } finally {
            if (
                Storage::disk(
                    'local'
                )->exists(
                    $relativePath
                )
            ) {
                Storage::disk(
                    'local'
                )->delete(
                    $relativePath
                );
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD APPROVED MESSAGE
    |--------------------------------------------------------------------------
    */

    private function buildApprovedCashMessage(
        CashTransaction $transaction
    ): string {
        $amount =
            number_format(
                (float) $transaction->amount,
                2
            );


        $balance =
            number_format(
                CashTransaction::currentBalance(),
                2
            );


        $category =
            $this->escape(
                $transaction->category
                ?: '-'
            );


        $approvedBy =
            $this->escape(
                $transaction->approved_by
                ?: 'Telegram Admin'
            );


        $borrower =
            $this->escape(
                $transaction->borrower_name
                ?: '-'
            );


        if (
            $transaction->category
            === 'Pinjaman Keluar'
        ) {
            $title =
                '✅ PINJAMAN KAS DISETUJUI';

        } elseif (
            $transaction->category
            === 'Pengembalian Pinjaman'
        ) {
            $title =
                '✅ PENGEMBALIAN PINJAMAN DISETUJUI';

        } elseif (
            $transaction->type
            === 'expense'
        ) {
            $title =
                '✅ CASH KELUAR DISETUJUI';

        } else {
            $title =
                '✅ CASH MASUK DISETUJUI';
        }


        $message =
            "<b>{$title}</b>\n\n"
            . "<b>ID:</b> #{$transaction->id}\n"
            . "<b>Kategori:</b> {$category}\n"
            . "<b>Jumlah:</b> \${$amount}\n";


        if (
            $transaction->isLoanTransaction()
        ) {
            $message .=
                "<b>Peminjam:</b> {$borrower}\n";
        }


        $message .=
            "<b>Disetujui Oleh:</b> {$approvedBy}\n"
            . "<b>Saldo Kas Sekarang:</b> \${$balance}\n\n"
            . "✅ Transaksi berhasil diproses.";


        return $message;
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD REJECTED MESSAGE
    |--------------------------------------------------------------------------
    */

    private function buildRejectedCashMessage(
        CashTransaction $transaction
    ): string {
        $amount =
            number_format(
                (float) $transaction->amount,
                2
            );


        $category =
            $this->escape(
                $transaction->category
                ?: '-'
            );


        $rejectedBy =
            $this->escape(
                $transaction->approved_by
                ?: 'Telegram Admin'
            );


        $reason =
            $this->escape(
                $transaction->rejection_reason
                ?: '-'
            );


        $borrower =
            $this->escape(
                $transaction->borrower_name
                ?: '-'
            );


        $message =
            "<b>❌ PERMINTAAN KAS DITOLAK</b>\n\n"
            . "<b>ID:</b> #{$transaction->id}\n"
            . "<b>Kategori:</b> {$category}\n"
            . "<b>Jumlah:</b> \${$amount}\n";


        if (
            $transaction->isLoanTransaction()
        ) {
            $message .=
                "<b>Peminjam:</b> {$borrower}\n";
        }


        $message .=
            "<b>Ditolak Oleh:</b> {$rejectedBy}\n"
            . "<b>Alasan:</b> {$reason}\n\n"
            . "Saldo kas tidak berubah.";


        return $message;
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD PROCESSED MESSAGE
    |--------------------------------------------------------------------------
    |
    | Digunakan untuk mengganti pesan request awal
    | sehingga tombol Approve / Tolak hilang.
    |
    */

    private function buildProcessedCashMessage(
        CashTransaction $transaction
    ): string {
        $amount =
            number_format(
                (float) $transaction->amount,
                2
            );


        $category =
            $this->escape(
                $transaction->category
                ?: '-'
            );


        $description =
            $this->escape(
                $transaction->description
                ?: '-'
            );


        $borrower =
            $this->escape(
                $transaction->borrower_name
                ?: '-'
            );


        $processedBy =
            $this->escape(
                $transaction->approved_by
                ?: '-'
            );


        if (
            $transaction
                ->approval_status
            === 'approved'
        ) {
            $status =
                '✅ APPROVED';

        } elseif (
            $transaction
                ->approval_status
            === 'rejected'
        ) {
            $status =
                '❌ REJECTED';

        } else {
            $status =
                '⏳ PENDING';
        }


        $message =
            "<b>💰 TRANSAKSI KAS</b>\n\n"
            . "<b>ID:</b> #{$transaction->id}\n"
            . "<b>Kategori:</b> {$category}\n"
            . "<b>Jumlah:</b> \${$amount}\n";


        if (
            $transaction->isLoanTransaction()
        ) {
            $message .=
                "<b>Peminjam:</b> {$borrower}\n";
        }


        $message .=
            "<b>Keterangan:</b> {$description}\n\n"
            . "<b>Status:</b> {$status}\n"
            . "<b>Diproses Oleh:</b> {$processedBy}";


        if (
            $transaction
                ->approval_status
            === 'rejected'
        ) {
            $reason =
                $this->escape(
                    $transaction
                        ->rejection_reason
                    ?: '-'
                );


            $message .=
                "\n<b>Alasan Penolakan:</b> {$reason}";
        }


        return $message;
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHORIZED ADMIN CHAT
    |--------------------------------------------------------------------------
    */

    private function isAuthorizedAdminChat(
        string $chatId
    ): bool {
        $adminChatId =
            trim(
                (string) env(
                    'TELEGRAM_STOCK_CHAT_ID',
                    ''
                )
            );


        if (
            $adminChatId === ''
        ) {
            return false;
        }


        return hash_equals(
            $adminChatId,
            trim(
                $chatId
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TELEGRAM USER NAME
    |--------------------------------------------------------------------------
    */

    private function telegramUserName(
        array $from
    ): string {
        $firstName =
            trim(
                (string) (
                    $from['first_name']
                    ?? ''
                )
            );


        $lastName =
            trim(
                (string) (
                    $from['last_name']
                    ?? ''
                )
            );


        $username =
            trim(
                (string) (
                    $from['username']
                    ?? ''
                )
            );


        $name =
            trim(
                $firstName
                . ' '
                . $lastName
            );


        if (
            $name === ''
            &&
            $username !== ''
        ) {
            $name =
                '@'
                . $username;
        }


        if (
            $name === ''
        ) {
            $name =
                'Admin';
        }


        return
            'Telegram: '
            . $name;
    }


    /*
    |--------------------------------------------------------------------------
    | HTML ESCAPE
    |--------------------------------------------------------------------------
    */

    private function escape(
        ?string $value
    ): string {
        return htmlspecialchars(
            (string) $value,
            ENT_QUOTES
            | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SERVICE
    |--------------------------------------------------------------------------
    */

    private function telegram(): StockTelegramService
    {
        return app(
            StockTelegramService::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SEND MESSAGE
    |--------------------------------------------------------------------------
    */

    private function sendMessage(
        string $chatId,
        string $message
    ): void {
        $this->telegram()
            ->send(
                $message,
                $chatId
            );
    }
}