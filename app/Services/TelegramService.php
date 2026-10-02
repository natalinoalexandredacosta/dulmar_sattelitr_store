<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    /**
     * Ambil Telegram Bot Token.
     */
    private function getToken(): ?string
    {
        return env('TELEGRAM_BOT_TOKEN');
    }

    /**
     * Ambil Group Chat ID.
     */
    private function getGroupChatId(): ?string
    {
        return env('TELEGRAM_GROUP_CHAT_ID');
    }

    /**
     * Validasi konfigurasi Telegram.
     */
    private function validateConfiguration(): bool
    {
        if (!$this->getToken()) {
            Log::error(
                'TELEGRAM_BOT_TOKEN belum tersedia.'
            );

            return false;
        }

        if (!$this->getGroupChatId()) {
            Log::error(
                'TELEGRAM_GROUP_CHAT_ID belum tersedia.'
            );

            return false;
        }

        return true;
    }

    /**
     * Kirim notifikasi text langsung ke Group Telegram.
     */
    public function send(string $message): bool
    {
        if (!$this->validateConfiguration()) {
            return false;
        }

        $token =
            $this->getToken();

        $groupChatId =
            $this->getGroupChatId();

        try {
            $response =
                Http::timeout(20)->post(
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
                    'Gagal mengirim pesan Telegram ke group.',
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
                'Error Telegram Service saat mengirim pesan.',
                [
                    'error' =>
                        $e->getMessage(),
                ]
            );

            return false;
        }
    }

    /**
     * Kirim document/file ke Group Telegram.
     *
     * Contoh:
     * $telegram->sendDocument(
     *     storage_path('app/reports/laporan.pdf'),
     *     'Laporan Kas Admin'
     * );
     */
    public function sendDocument(
        string $filePath,
        ?string $caption = null
    ): bool {
        if (!$this->validateConfiguration()) {
            return false;
        }

        if (!file_exists($filePath)) {
            Log::error(
                'File Telegram tidak ditemukan.',
                [
                    'file' =>
                        $filePath,
                ]
            );

            return false;
        }

        if (!is_readable($filePath)) {
            Log::error(
                'File Telegram tidak dapat dibaca.',
                [
                    'file' =>
                        $filePath,
                ]
            );

            return false;
        }

        $token =
            $this->getToken();

        $groupChatId =
            $this->getGroupChatId();

        try {
            $request =
                Http::timeout(60)
                    ->attach(
                        'document',
                        fopen($filePath, 'r'),
                        basename($filePath)
                    );

            $data = [
                'chat_id' =>
                    $groupChatId,
            ];

            if ($caption) {
                $data['caption'] =
                    $caption;

                $data['parse_mode'] =
                    'HTML';
            }

            $response =
                $request->post(
                    "https://api.telegram.org/bot{$token}/sendDocument",
                    $data
                );

            if (!$response->successful()) {
                Log::error(
                    'Gagal mengirim document Telegram ke group.',
                    [
                        'status' =>
                            $response->status(),

                        'file' =>
                            $filePath,

                        'response' =>
                            $response->body(),
                    ]
                );

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error(
                'Error Telegram Service saat mengirim document.',
                [
                    'file' =>
                        $filePath,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            return false;
        }
    }
}