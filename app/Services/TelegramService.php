<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    /**
     * Kirim notifikasi sistem langsung ke Group Telegram.
     */
    public function send(string $message): bool
    {
        $token =
            env('TELEGRAM_BOT_TOKEN');

        $groupChatId =
            env('TELEGRAM_GROUP_CHAT_ID');

        if (!$token) {
            Log::error(
                'TELEGRAM_BOT_TOKEN belum tersedia.'
            );

            return false;
        }

        if (!$groupChatId) {
            Log::error(
                'TELEGRAM_GROUP_CHAT_ID belum tersedia.'
            );

            return false;
        }

        try {
            $response =
                Http::timeout(10)->post(
                    "https://api.telegram.org/bot{$token}/sendMessage",
                    [
                        'chat_id' =>
                            $groupChatId,

                        'text' =>
                            $message,

                        'parse_mode' =>
                            'HTML',
                    ]
                );

            if (!$response->successful()) {
                Log::error(
                    'Gagal mengirim Telegram ke group.',
                    [
                        'response' =>
                            $response->body(),
                    ]
                );

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error(
                'Error Telegram Service: '
                . $e->getMessage()
            );

            return false;
        }
    }
}