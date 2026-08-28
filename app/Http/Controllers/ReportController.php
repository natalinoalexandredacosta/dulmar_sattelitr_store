<?php

namespace App\Http\Controllers;

use App\Exports\InventoryReportExport;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan inventaris.
     */
    public function index(Request $request)
    {
        $validated =
            $this->validateDateFilter($request);

        $startDate =
            $validated['start_date'] ?? null;

        $endDate =
            $validated['end_date'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Ringkasan kondisi produk saat ini
        |--------------------------------------------------------------------------
        */

        $totalProducts =
            Product::count();

        $totalCurrentStock =
            (int) Product::sum('stock');

        $lowStockProducts =
            Product::whereBetween(
                'stock',
                [1, 5]
            )->count();

        $outOfStockProducts =
            Product::where(
                'stock',
                '<=',
                0
            )->count();

        /*
         * Nilai modal seluruh stok yang masih tersedia.
         */
        $currentInventoryValue =
            (float) Product::query()
                ->selectRaw(
                    'COALESCE(
                        SUM(stock * purchase_price),
                        0
                    ) as inventory_value'
                )
                ->value('inventory_value');

        /*
        |--------------------------------------------------------------------------
        | Ringkasan stok masuk dan stok keluar berdasarkan periode
        |--------------------------------------------------------------------------
        */

        $stockInQuery =
            StockIn::query();

        $stockOutQuery =
            StockOut::query();

        $this->applyDateFilter(
            $stockInQuery,
            $startDate,
            $endDate
        );

        $this->applyDateFilter(
            $stockOutQuery,
            $startDate,
            $endDate
        );

        $totalStockIn =
            (int) (clone $stockInQuery)
                ->sum('quantity');

        $totalStockOut =
            (int) (clone $stockOutQuery)
                ->sum('quantity');

        $totalStockInTransactions =
            (clone $stockInQuery)->count();

        $totalStockOutTransactions =
            (clone $stockOutQuery)->count();

        /*
        |--------------------------------------------------------------------------
        | Ringkasan produk
        |--------------------------------------------------------------------------
        */

        $products = Product::query()
            ->withSum([
                'stockIns as total_stock_in' =>
                    function ($query) use (
                        $startDate,
                        $endDate
                    ) {
                        $this->applyDateFilter(
                            $query,
                            $startDate,
                            $endDate
                        );
                    },
            ], 'quantity')
            ->withSum([
                'stockOuts as total_stock_out' =>
                    function ($query) use (
                        $startDate,
                        $endDate
                    ) {
                        $this->applyDateFilter(
                            $query,
                            $startDate,
                            $endDate
                        );
                    },
            ], 'quantity')
            ->orderBy('product_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Query transaksi penjualan berdasarkan periode
        |--------------------------------------------------------------------------
        */

        $salesBaseQuery = StockOut::query();

        $this->applyDateFilter(
            $salesBaseQuery,
            $startDate,
            $endDate
        );

        /*
        |--------------------------------------------------------------------------
        | Total keuangan seluruh hasil filter
        |--------------------------------------------------------------------------
        */

        $totalSales =
            (float) (clone $salesBaseQuery)
                ->sum('subtotal');

        $totalProfit =
            (float) (clone $salesBaseQuery)
                ->sum('total_profit');

        $totalCapital =
            (float) (clone $salesBaseQuery)
                ->selectRaw(
                    'COALESCE(
                        SUM(quantity * unit_purchase_price),
                        0
                    ) as total_capital'
                )
                ->value('total_capital');

        /*
         * Margin keuntungan dalam persentase.
         */
        $profitMargin =
            $totalSales > 0
                ? ($totalProfit / $totalSales) * 100
                : 0;

        /*
         * Nilai rata-rata setiap transaksi penjualan.
         */
        $averageTransaction =
            $totalStockOutTransactions > 0
                ? $totalSales / $totalStockOutTransactions
                : 0;

        /*
        |--------------------------------------------------------------------------
        | Daftar transaksi penjualan dengan pagination
        |--------------------------------------------------------------------------
        */

        $salesQuery = StockOut::query()
            ->with([
                'product',
                'customer',
            ]);

        $this->applyDateFilter(
            $salesQuery,
            $startDate,
            $endDate
        );

        $sales = $salesQuery
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate(
                10,
                ['*'],
                'sales_page'
            );

        /*
         * Mempertahankan filter tanggal ketika berpindah halaman.
         */
        $sales->appends($request->query());

        /*
        |--------------------------------------------------------------------------
        | Data grafik penjualan per tanggal
        |--------------------------------------------------------------------------
        */

        $dailySalesQuery = StockOut::query()
            ->select([
                DB::raw(
                    'DATE(transaction_date) as sale_date'
                ),
                DB::raw(
                    'SUM(quantity) as total_quantity'
                ),
                DB::raw(
                    'SUM(subtotal) as total_sales'
                ),
                DB::raw(
                    'SUM(total_profit) as total_profit'
                ),
            ]);

        $this->applyDateFilter(
            $dailySalesQuery,
            $startDate,
            $endDate
        );

        $dailySales = $dailySalesQuery
            ->groupBy(
                DB::raw('DATE(transaction_date)')
            )
            ->orderBy(
                DB::raw('DATE(transaction_date)')
            )
            ->get();

        $chartLabels = $dailySales
            ->map(function ($item) {
                return date(
                    'd-m-Y',
                    strtotime($item->sale_date)
                );
            })
            ->values();

        $chartSalesValues = $dailySales
            ->pluck('total_sales')
            ->map(function ($value) {
                return (float) $value;
            })
            ->values();

        $chartProfitValues = $dailySales
            ->pluck('total_profit')
            ->map(function ($value) {
                return (float) $value;
            })
            ->values();

        $chartQuantityValues = $dailySales
            ->pluck('total_quantity')
            ->map(function ($value) {
                return (int) $value;
            })
            ->values();

        return view('reports.index', compact(
            'totalProducts',
            'totalCurrentStock',
            'lowStockProducts',
            'outOfStockProducts',
            'currentInventoryValue',
            'totalStockIn',
            'totalStockOut',
            'totalStockInTransactions',
            'totalStockOutTransactions',
            'products',
            'sales',
            'totalSales',
            'totalCapital',
            'totalProfit',
            'profitMargin',
            'averageTransaction',
            'chartLabels',
            'chartSalesValues',
            'chartProfitValues',
            'chartQuantityValues',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Mengunduh laporan inventaris dalam format Excel.
     */
    public function exportExcel(Request $request)
    {
        $validated =
            $this->validateDateFilter($request);

        $startDate =
            $validated['start_date'] ?? null;

        $endDate =
            $validated['end_date'] ?? null;

        $fileName =
            'laporan-inventaris-'
            . now()->format('Y-m-d-His')
            . '.xlsx';

        return Excel::download(
            new InventoryReportExport(
                $startDate,
                $endDate
            ),
            $fileName
        );
    }

    /**
     * Validasi filter tanggal laporan.
     */
    private function validateDateFilter(
        Request $request
    ): array {
        return $request->validate([
            'start_date' => [
                'nullable',
                'date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
        ], [
            'start_date.date' =>
                'Tanggal mulai tidak valid.',

            'end_date.date' =>
                'Tanggal selesai tidak valid.',

            'end_date.after_or_equal' =>
                'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);
    }

    /**
     * Menerapkan filter tanggal pada query transaksi.
     */
    private function applyDateFilter(
        Builder $query,
        ?string $startDate,
        ?string $endDate
    ): void {
        if ($startDate) {
            $query->whereDate(
                'transaction_date',
                '>=',
                $startDate
            );
        }

        if ($endDate) {
            $query->whereDate(
                'transaction_date',
                '<=',
                $endDate
            );
        }
    }
}