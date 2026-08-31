<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StockTelegramService
{
    /**
     * Ambil token bot stok.
     */
    private function getToken(): ?string
    {
        return env(
            'TELEGRAM_STOCK_BOT_TOKEN'
        );
    }


    /**
     * Ambil chat ID default.
     */
    private function getDefaultChatId(): ?string
    {
        return env(
            'TELEGRAM_STOCK_CHAT_ID'
        );
    }


    /**
     * Kirim pesan biasa menggunakan
     * bot Telegram khusus stok barang.
     */
    public function send(
        string $message,
        ?string $chatId = null
    ): bool {
        $token =
            $this->getToken();

        $targetChatId =
            $chatId
            ?: $this->getDefaultChatId();


        if (!$token) {
            Log::warning(
                'TELEGRAM_STOCK_BOT_TOKEN belum tersedia.'
            );

            return false;
        }


        if (!$targetChatId) {
            Log::warning(
                'TELEGRAM_STOCK_CHAT_ID belum tersedia.'
            );

            return false;
        }


        try {
            $response =
                Http::timeout(15)
                    ->post(
                        "https://api.telegram.org/bot{$token}/sendMessage",
                        [
                            'chat_id' =>
                                $targetChatId,

                            'text' =>
                                $message,

                            'parse_mode' =>
                                'HTML',

                            'disable_web_page_preview' =>
                                true,
                        ]
                    );


            if (!$response->successful()) {
                Log::error(
                    'Gagal mengirim Telegram Stock Bot.',
                    [
                        'status' =>
                            $response->status(),

                        'response' =>
                            $response->body(),
                    ]
                );

                return false;
            }


            return true;

        } catch (\Throwable $e) {
            Log::error(
                'Stock Telegram Service Error: '
                . $e->getMessage()
            );

            return false;
        }
    }


    /**
     * Kirim pesan dengan inline keyboard.
     *
     * Contoh:
     *
     * [
     *     [
     *         [
     *             'text' => '✅ APPROVE',
     *             'callback_data' => 'cash_approve_15',
     *         ],
     *         [
     *             'text' => '❌ TOLAK',
     *             'callback_data' => 'cash_reject_15',
     *         ],
     *     ],
     * ]
     */
    public function sendWithButtons(
        string $message,
        array $buttons,
        ?string $chatId = null
    ): bool {
        $token =
            $this->getToken();

        $targetChatId =
            $chatId
            ?: $this->getDefaultChatId();


        if (!$token) {
            Log::warning(
                'TELEGRAM_STOCK_BOT_TOKEN belum tersedia.'
            );

            return false;
        }


        if (!$targetChatId) {
            Log::warning(
                'TELEGRAM_STOCK_CHAT_ID belum tersedia.'
            );

            return false;
        }


        try {
            $response =
                Http::timeout(15)
                    ->post(
                        "https://api.telegram.org/bot{$token}/sendMessage",
                        [
                            'chat_id' =>
                                $targetChatId,

                            'text' =>
                                $message,

                            'parse_mode' =>
                                'HTML',

                            'disable_web_page_preview' =>
                                true,

                            'reply_markup' => [
                                'inline_keyboard' =>
                                    $buttons,
                            ],
                        ]
                    );


            if (!$response->successful()) {
                Log::error(
                    'Gagal mengirim Telegram dengan tombol.',
                    [
                        'status' =>
                            $response->status(),

                        'response' =>
                            $response->body(),
                    ]
                );

                return false;
            }


            return true;

        } catch (\Throwable $e) {
            Log::error(
                'Telegram Inline Button Error: '
                . $e->getMessage()
            );

            return false;
        }
    }


    /**
     * Kirim request approval Kas.
     */
    public function sendCashApprovalRequest(
        int $transactionId,
        string $message,
        ?string $chatId = null
    ): bool {
        return $this->sendWithButtons(
            $message,
            [
                [
                    [
                        'text' =>
                            '✅ APPROVE',

                        'callback_data' =>
                            'cash_approve_'
                            . $transactionId,
                    ],

                    [
                        'text' =>
                            '❌ TOLAK',

                        'callback_data' =>
                            'cash_reject_'
                            . $transactionId,
                    ],
                ],
            ],
            $chatId
        );
    }


    /**
     * Jawab callback query.
     *
     * Ini penting supaya loading icon
     * pada tombol Telegram berhenti.
     */
    public function answerCallbackQuery(
        string $callbackQueryId,
        string $text = '',
        bool $showAlert = false
    ): bool {
        $token =
            $this->getToken();


        if (!$token) {
            Log::warning(
                'TELEGRAM_STOCK_BOT_TOKEN belum tersedia.'
            );

            return false;
        }


        try {
            $payload = [
                'callback_query_id' =>
                    $callbackQueryId,

                'show_alert' =>
                    $showAlert,
            ];


            if ($text !== '') {
                $payload['text'] =
                    $text;
            }


            $response =
                Http::timeout(15)
                    ->post(
                        "https://api.telegram.org/bot{$token}/answerCallbackQuery",
                        $payload
                    );


            if (!$response->successful()) {
                Log::error(
                    'Gagal answerCallbackQuery Telegram.',
                    [
                        'status' =>
                            $response->status(),

                        'response' =>
                            $response->body(),
                    ]
                );

                return false;
            }


            return true;

        } catch (\Throwable $e) {
            Log::error(
                'Telegram Callback Query Error: '
                . $e->getMessage()
            );

            return false;
        }
    }


    /**
     * Edit pesan Telegram.
     *
     * Berguna setelah transaksi sudah
     * Approved / Rejected agar tombol
     * tidak bisa ditekan lagi.
     */
    public function editMessage(
        string $chatId,
        int $messageId,
        string $message
    ): bool {
        $token =
            $this->getToken();


        if (!$token) {
            Log::warning(
                'TELEGRAM_STOCK_BOT_TOKEN belum tersedia.'
            );

            return false;
        }


        try {
            $response =
                Http::timeout(15)
                    ->post(
                        "https://api.telegram.org/bot{$token}/editMessageText",
                        [
                            'chat_id' =>
                                $chatId,

                            'message_id' =>
                                $messageId,

                            'text' =>
                                $message,

                            'parse_mode' =>
                                'HTML',

                            'disable_web_page_preview' =>
                                true,

                            /*
                             * Kosongkan inline keyboard.
                             * Tombol akan hilang.
                             */
                            'reply_markup' => [
                                'inline_keyboard' =>
                                    [],
                            ],
                        ]
                    );


            if (!$response->successful()) {
                Log::error(
                    'Gagal edit pesan Telegram.',
                    [
                        'status' =>
                            $response->status(),

                        'response' =>
                            $response->body(),
                    ]
                );

                return false;
            }


            return true;

        } catch (\Throwable $e) {
            Log::error(
                'Telegram Edit Message Error: '
                . $e->getMessage()
            );

            return false;
        }
    }
}