<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SalesTarget;
use App\Models\StockIn;
use App\Models\StockOut;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | RINGKASAN INVENTORY
        |--------------------------------------------------------------------------
        */

        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $totalStockIn = StockIn::sum('quantity');
        $totalStockOut = StockOut::sum('quantity');


        /*
        |--------------------------------------------------------------------------
        | GRAFIK STOK 7 HARI TERAKHIR
        |--------------------------------------------------------------------------
        */

        $chartLabels = [];
        $chartStockIn = [];
        $chartStockOut = [];

        for ($hari = 6; $hari >= 0; $hari--) {
            $tanggal = Carbon::today('Asia/Dili')->subDays($hari);

            $chartLabels[] = $tanggal->format('d-m-Y');

            $chartStockIn[] = StockIn::whereDate(
                'transaction_date',
                $tanggal->format('Y-m-d')
            )->sum('quantity');

            $chartStockOut[] = StockOut::whereDate(
                'transaction_date',
                $tanggal->format('Y-m-d')
            )->sum('quantity');
        }


        /*
        |--------------------------------------------------------------------------
        | TARGET PENJUALAN BULAN BERJALAN
        |--------------------------------------------------------------------------
        */

        $currentDate = now('Asia/Dili');
        $currentMonth = (int) $currentDate->month;
        $currentYear = (int) $currentDate->year;

        $startDate = $currentDate
            ->copy()
            ->startOfMonth()
            ->toDateString();

        $endDate = $currentDate
            ->copy()
            ->endOfMonth()
            ->toDateString();


        /*
        |--------------------------------------------------------------------------
        | AMBIL TARGET BULAN INI
        |--------------------------------------------------------------------------
        */

        $salesTargets = SalesTarget::query()
            ->with('product')
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->get();

        $targetProductIds = $salesTargets
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | PENJUALAN AKTUAL BULAN INI
        |--------------------------------------------------------------------------
        */

        $actualSales = collect();

        if ($targetProductIds->isNotEmpty()) {
            $actualSales = DB::table('stock_outs')
                ->select('product_id')
                ->selectRaw('COALESCE(SUM(quantity), 0) AS sold_qty')

                /*
                 * Uang yang benar-benar menjadi nilai penjualan toko:
                 * subtotal dikurangi deduction/potongan petugas.
                 */
                ->selectRaw('
                    COALESCE(
                        SUM(
                            subtotal - COALESCE(deduction_amount, 0)
                        ),
                        0
                    ) AS actual_revenue
                ')

                /*
                 * Keuntungan setelah potongan petugas:
                 * uang penjualan bersih dikurangi modal barang.
                 */
                ->selectRaw('
                    COALESCE(
                        SUM(
                            (
                                subtotal - COALESCE(deduction_amount, 0)
                            )
                            -
                            (
                                unit_purchase_price * quantity
                            )
                        ),
                        0
                    ) AS net_profit
                ')

                ->whereIn('product_id', $targetProductIds)
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->groupBy('product_id')
                ->get()
                ->keyBy('product_id');
        }


        /*
        |--------------------------------------------------------------------------
        | HITUNG TOTAL TARGET DAN REALISASI
        |--------------------------------------------------------------------------
        */

        $dashboardTargetQty = 0;
        $dashboardSoldQty = 0;
        $dashboardTargetRevenue = 0;
        $dashboardActualRevenue = 0;
        $dashboardProfit = 0;

        foreach ($salesTargets as $target) {
            $actual = $actualSales->get($target->product_id);

            $dashboardTargetQty += (int) $target->target_qty;

            $dashboardSoldQty += (int) (
                $actual->sold_qty ?? 0
            );

            $dashboardTargetRevenue += (float) $target->target_revenue;

            $dashboardActualRevenue += (float) (
                $actual->actual_revenue ?? 0
            );

            $dashboardProfit += (float) (
                $actual->net_profit ?? 0
            );
        }


        /*
        |--------------------------------------------------------------------------
        | HITUNG PERSENTASE
        |--------------------------------------------------------------------------
        */

        $dashboardRemainingQty = max(
            $dashboardTargetQty - $dashboardSoldQty,
            0
        );

        $dashboardTargetProgress = $dashboardTargetQty > 0
            ? min(
                100,
                ($dashboardSoldQty / $dashboardTargetQty) * 100
            )
            : 0;

        $dashboardRemainingPercent = max(
            100 - $dashboardTargetProgress,
            0
        );

        $dashboardRevenueProgress = $dashboardTargetRevenue > 0
            ? min(
                100,
                ($dashboardActualRevenue / $dashboardTargetRevenue) * 100
            )
            : 0;

        $dashboardProfitMargin = $dashboardActualRevenue > 0
            ? ($dashboardProfit / $dashboardActualRevenue) * 100
            : 0;


        /*
        |--------------------------------------------------------------------------
        | STATUS TARGET
        |--------------------------------------------------------------------------
        */

        if ($dashboardTargetProgress >= 100) {
            $dashboardTargetStatus = 'Target Tercapai';
        } elseif ($dashboardTargetProgress >= 75) {
            $dashboardTargetStatus = 'Hampir Selesai';
        } elseif ($dashboardTargetProgress >= 50) {
            $dashboardTargetStatus = 'Cukup Baik';
        } elseif ($dashboardTargetProgress > 0) {
            $dashboardTargetStatus = 'Masih Rendah';
        } else {
            $dashboardTargetStatus = 'Belum Ada Penjualan';
        }


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(
            'totalProducts',
            'totalStock',
            'totalStockIn',
            'totalStockOut',
            'chartLabels',
            'chartStockIn',
            'chartStockOut',

            'currentMonth',
            'currentYear',

            'dashboardTargetQty',
            'dashboardSoldQty',
            'dashboardRemainingQty',

            'dashboardTargetRevenue',
            'dashboardActualRevenue',
            'dashboardProfit',

            'dashboardTargetProgress',
            'dashboardRemainingPercent',
            'dashboardRevenueProgress',
            'dashboardProfitMargin',

            'dashboardTargetStatus'
        ));
    }
}
