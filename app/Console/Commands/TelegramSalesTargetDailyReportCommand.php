<?php

namespace App\Console\Commands;

use App\Models\SalesTarget;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TelegramSalesTargetDailyReportCommand extends Command
{
    protected $signature = 'telegram:sales-target-daily-report';

    protected $description =
        'Kirim progress target penjualan bulan berjalan ke Telegram setiap pagi';

    public function handle(): int
    {
        /*
        |--------------------------------------------------------------------------
        | TELEGRAM CONFIG
        |--------------------------------------------------------------------------
        | Menggunakan Telegram Stock Bot yang sudah dipakai report inventory.
        */

        $token = env('TELEGRAM_STOCK_BOT_TOKEN');
        $chatId = env('TELEGRAM_STOCK_CHAT_ID');

        if (!$token || !$chatId) {
            $this->error(
                'TELEGRAM_STOCK_BOT_TOKEN atau TELEGRAM_STOCK_CHAT_ID belum tersedia.'
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | PERIODE BULAN BERJALAN - ASIA/DILI
        |--------------------------------------------------------------------------
        */

        $now = now('Asia/Dili');

        $month = (int) $now->month;
        $year = (int) $now->year;

        $startDate = $now
            ->copy()
            ->startOfMonth()
            ->toDateString();

        $endDate = $now
            ->copy()
            ->endOfMonth()
            ->toDateString();

        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $periodName =
            ($monthNames[$month] ?? (string) $month)
            . ' '
            . $year;

        /*
        |--------------------------------------------------------------------------
        | AMBIL TARGET BULAN BERJALAN
        |--------------------------------------------------------------------------
        */

        $targets = SalesTarget::query()
            ->with('product')
            ->where('month', $month)
            ->where('year', $year)
            ->orderBy('id')
            ->get();

        if ($targets->isEmpty()) {
            $message =
                "🎯 <b>PROGRESS TARGET PENJUALAN</b>\n\n"
                . "📅 Periode: <b>{$periodName}</b>\n"
                . "🕗 Update: "
                . $now->format('d-m-Y H:i')
                . " TL\n\n"
                . "ℹ️ Belum ada target penjualan untuk periode ini.";

            return $this->sendTelegram(
                (string) $token,
                (string) $chatId,
                $message
            );
        }

        $targetProductIds = $targets
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | REALISASI - HANYA SETORAN PAID + VERIFIED
        |--------------------------------------------------------------------------
        |
        | Sesuai logika Sales Target/Dashboard:
        | - staff_deposit_status = paid
        | - deposit_verified_by tidak null
        | - staff_deposited_at tidak null
        | - uang penjualan = staff_deposited_amount
        | - keuntungan = uang setoran - modal barang
        */

        $actualSales = collect();

        if ($targetProductIds->isNotEmpty()) {
            $actualSales = DB::table('stock_outs')
                ->select('product_id')
                ->selectRaw(
                    'COALESCE(SUM(quantity), 0) AS sold_qty'
                )
                ->selectRaw(
                    'COALESCE(SUM(staff_deposited_amount), 0) AS actual_revenue'
                )
                ->selectRaw(
                    'COALESCE(SUM(unit_purchase_price * quantity), 0) AS actual_cost'
                )
                ->selectRaw(
                    'COALESCE(
                        SUM(
                            staff_deposited_amount
                            - (unit_purchase_price * quantity)
                        ),
                        0
                    ) AS net_profit'
                )
                ->whereIn(
                    'product_id',
                    $targetProductIds
                )
                ->where(
                    'staff_deposit_status',
                    'paid'
                )
                ->whereNotNull(
                    'deposit_verified_by'
                )
                ->whereNotNull(
                    'staff_deposited_at'
                )
                ->whereBetween(
                    'transaction_date',
                    [
                        $startDate,
                        $endDate,
                    ]
                )
                ->groupBy(
                    'product_id'
                )
                ->get()
                ->keyBy(
                    'product_id'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | HITUNG TOTAL + DETAIL PER PRODUK
        |--------------------------------------------------------------------------
        */

        $totalTargetQty = 0;
        $totalSoldQty = 0;
        $totalTargetRevenue = 0.0;
        $totalActualRevenue = 0.0;
        $totalProfit = 0.0;

        $detailMessage = '';

        foreach ($targets as $target) {
            $actual = $actualSales->get(
                $target->product_id
            );

            $targetQty =
                (int) $target->target_qty;

            $soldQty =
                (int) ($actual->sold_qty ?? 0);

            $remainingQty =
                max(
                    $targetQty - $soldQty,
                    0
                );

            $targetRevenue =
                (float) $target->target_revenue;

            $actualRevenue =
                (float) (
                    $actual->actual_revenue
                    ?? 0
                );

            $netProfit =
                (float) (
                    $actual->net_profit
                    ?? 0
                );

            $qtyProgress =
                $targetQty > 0
                    ? min(
                        100,
                        ($soldQty / $targetQty)
                        * 100
                    )
                    : 0;

            $revenueProgress =
                $targetRevenue > 0
                    ? min(
                        100,
                        (
                            $actualRevenue
                            / $targetRevenue
                        ) * 100
                    )
                    : 0;

            $productName =
                $target->product?->product_name
                ?? 'Produk tidak ditemukan';

            $productName =
                htmlspecialchars(
                    $productName,
                    ENT_QUOTES
                    | ENT_SUBSTITUTE,
                    'UTF-8'
                );

            $status =
                $this->statusLabel(
                    $qtyProgress
                );

            $detailMessage .=
                "\n📦 <b>{$productName}</b>\n"
                . "Target: "
                . number_format(
                    $targetQty
                )
                . " unit\n"
                . "Sudah Terjual: <b>"
                . number_format(
                    $soldQty
                )
                . " unit</b>\n"
                . "Sisa Target: "
                . number_format(
                    $remainingQty
                )
                . " unit\n"
                . "Target Tercapai: <b>"
                . number_format(
                    $qtyProgress,
                    1
                )
                . "%</b>\n"
                . "Target Uang: $"
                . number_format(
                    $targetRevenue,
                    2
                )
                . "\n"
                . "Uang Penjualan: <b>$"
                . number_format(
                    $actualRevenue,
                    2
                )
                . "</b>\n"
                . "Target Uang Tercapai: "
                . number_format(
                    $revenueProgress,
                    1
                )
                . "%\n"
                . "Keuntungan: <b>$"
                . number_format(
                    $netProfit,
                    2
                )
                . "</b>\n"
                . "Status: <b>{$status}</b>\n";

            $totalTargetQty +=
                $targetQty;

            $totalSoldQty +=
                $soldQty;

            $totalTargetRevenue +=
                $targetRevenue;

            $totalActualRevenue +=
                $actualRevenue;

            $totalProfit +=
                $netProfit;
        }

        $totalRemainingQty =
            max(
                $totalTargetQty
                - $totalSoldQty,
                0
            );

        $totalQtyProgress =
            $totalTargetQty > 0
                ? min(
                    100,
                    (
                        $totalSoldQty
                        / $totalTargetQty
                    ) * 100
                )
                : 0;

        $totalRevenueProgress =
            $totalTargetRevenue > 0
                ? min(
                    100,
                    (
                        $totalActualRevenue
                        / $totalTargetRevenue
                    ) * 100
                )
                : 0;

        $profitMargin =
            $totalActualRevenue > 0
                ? (
                    $totalProfit
                    / $totalActualRevenue
                ) * 100
                : 0;

        $overallStatus =
            $this->statusLabel(
                $totalQtyProgress
            );

        /*
        |--------------------------------------------------------------------------
        | PESAN TELEGRAM
        |--------------------------------------------------------------------------
        */

        $message =
            "🎯 <b>PROGRESS TARGET PENJUALAN</b>\n\n"
            . "📅 Periode: <b>{$periodName}</b>\n"
            . "🕗 Update: "
            . $now->format('d-m-Y H:i')
            . " TL\n\n"
            . "━━━━━━━━━━━━━━━━━━\n"
            . "📊 <b>RINGKASAN</b>\n"
            . "━━━━━━━━━━━━━━━━━━\n"
            . "Target Barang: <b>"
            . number_format(
                $totalTargetQty
            )
            . " unit</b>\n"
            . "Sudah Terjual: <b>"
            . number_format(
                $totalSoldQty
            )
            . " unit</b>\n"
            . "Sisa Target: <b>"
            . number_format(
                $totalRemainingQty
            )
            . " unit</b>\n"
            . "Target Tercapai: <b>"
            . number_format(
                $totalQtyProgress,
                1
            )
            . "%</b>\n\n"
            . "Target Uang: <b>$"
            . number_format(
                $totalTargetRevenue,
                2
            )
            . "</b>\n"
            . "Uang Penjualan: <b>$"
            . number_format(
                $totalActualRevenue,
                2
            )
            . "</b>\n"
            . "Target Uang Tercapai: <b>"
            . number_format(
                $totalRevenueProgress,
                1
            )
            . "%</b>\n\n"
            . "Keuntungan: <b>$"
            . number_format(
                $totalProfit,
                2
            )
            . "</b>\n"
            . "Margin Untung: <b>"
            . number_format(
                $profitMargin,
                1
            )
            . "%</b>\n"
            . "Status: <b>"
            . $overallStatus
            . "</b>\n"
            . "\n━━━━━━━━━━━━━━━━━━\n"
            . "📋 <b>DETAIL PER PRODUK</b>\n"
            . "━━━━━━━━━━━━━━━━━━\n"
            . $detailMessage
            . "\nℹ️ Hanya transaksi dengan setoran "
            . "<b>paid dan sudah diverifikasi Admin</b> "
            . "yang dihitung.";

        return $this->sendTelegram(
            (string) $token,
            (string) $chatId,
            $message
        );
    }

    private function statusLabel(
        float $progress
    ): string {
        if ($progress >= 100) {
            return 'Target Tercapai';
        }

        if ($progress >= 75) {
            return 'Hampir Selesai';
        }

        if ($progress >= 50) {
            return 'Cukup Baik';
        }

        if ($progress > 0) {
            return 'Masih Rendah';
        }

        return 'Belum Ada Penjualan';
    }

    private function sendTelegram(
        string $token,
        string $chatId,
        string $message
    ): int {
        try {
            $response =
                Http::timeout(30)
                    ->post(
                        "https://api.telegram.org/bot{$token}/sendMessage",
                        [
                            'chat_id' =>
                                $chatId,

                            'text' =>
                                $message,

                            'parse_mode' =>
                                'HTML',

                            'disable_web_page_preview' =>
                                true,
                        ]
                    );

            if (
                !$response->successful()
            ) {
                $this->error(
                    'Telegram error: '
                    . $response->body()
                );

                return self::FAILURE;
            }

            $this->info(
                'Progress target penjualan berhasil dikirim ke Telegram.'
            );

            return self::SUCCESS;

        } catch (\Throwable $e) {
            $this->error(
                'Gagal mengirim progress target: '
                . $e->getMessage()
            );

            return self::FAILURE;
        }
    }
}
