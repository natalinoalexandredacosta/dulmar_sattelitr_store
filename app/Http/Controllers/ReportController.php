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
        | RINGKASAN KONDISI PRODUK SAAT INI
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
        |--------------------------------------------------------------------------
        | NILAI MODAL STOK SAAT INI
        |--------------------------------------------------------------------------
        */

        $currentInventoryValue =
            (float) Product::query()
                ->selectRaw(
                    '
                    COALESCE(
                        SUM(
                            stock
                            *
                            purchase_price
                        ),
                        0
                    ) AS inventory_value
                    '
                )
                ->value(
                    'inventory_value'
                );


        /*
        |--------------------------------------------------------------------------
        | RINGKASAN STOK MASUK & KELUAR
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
            (clone $stockInQuery)
                ->count();

        $totalStockOutTransactions =
            (clone $stockOutQuery)
                ->count();


        /*
        |--------------------------------------------------------------------------
        | RINGKASAN PRODUK
        |--------------------------------------------------------------------------
        */

        $products =
            Product::query()

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

                ->orderBy(
                    'product_name'
                )

                ->get();


        /*
        |--------------------------------------------------------------------------
        | QUERY DASAR PENJUALAN
        |--------------------------------------------------------------------------
        */

        $salesBaseQuery =
            StockOut::query();


        $this->applyDateFilter(
            $salesBaseQuery,
            $startDate,
            $endDate
        );


        /*
        |--------------------------------------------------------------------------
        | TOTAL JUMLAH BARANG TERJUAL
        |--------------------------------------------------------------------------
        */

        $totalQuantity =
            (int) (clone $salesBaseQuery)
                ->sum(
                    'quantity'
                );


        /*
        |--------------------------------------------------------------------------
        | TOTAL HARGA BELI
        |--------------------------------------------------------------------------
        |
        | Menjumlahkan harga beli per unit dari seluruh transaksi.
        |
        */

        $totalPurchasePrice =
            (float) (clone $salesBaseQuery)
                ->selectRaw(
                    '
                    COALESCE(
                        SUM(
                            COALESCE(
                                unit_purchase_price,
                                0
                            )
                        ),
                        0
                    ) AS total_purchase_price
                    '
                )
                ->value(
                    'total_purchase_price'
                );


        /*
        |--------------------------------------------------------------------------
        | TOTAL HARGA JUAL
        |--------------------------------------------------------------------------
        |
        | Menjumlahkan harga jual per unit dari seluruh transaksi.
        |
        */

        $totalSellingPrice =
            (float) (clone $salesBaseQuery)
                ->selectRaw(
                    '
                    COALESCE(
                        SUM(
                            COALESCE(
                                unit_selling_price,
                                0
                            )
                        ),
                        0
                    ) AS total_selling_price
                    '
                )
                ->value(
                    'total_selling_price'
                );


        /*
        |--------------------------------------------------------------------------
        | TOTAL PENJUALAN
        |--------------------------------------------------------------------------
        |
        | subtotal = total nilai penjualan setelah diskon customer.
        |
        */

        $totalSales =
            (float) (clone $salesBaseQuery)
                ->selectRaw(
                    '
                    COALESCE(
                        SUM(
                            COALESCE(
                                subtotal,
                                0
                            )
                        ),
                        0
                    ) AS total_sales
                    '
                )
                ->value(
                    'total_sales'
                );


        /*
        |--------------------------------------------------------------------------
        | TOTAL MODAL
        |--------------------------------------------------------------------------
        |
        | Modal = quantity x harga beli per unit.
        |
        */

        $totalCapital =
            (float) (clone $salesBaseQuery)

                ->selectRaw(
                    '
                    COALESCE(
                        SUM(
                            quantity
                            *
                            unit_purchase_price
                        ),
                        0
                    ) AS total_capital
                    '
                )

                ->value(
                    'total_capital'
                );


        /*
        |--------------------------------------------------------------------------
        | TOTAL BIAYA / POTONGAN PETUGAS
        |--------------------------------------------------------------------------
        */

        $totalDeduction =
            (float) (clone $salesBaseQuery)

                ->selectRaw(
                    '
                    COALESCE(
                        SUM(
                            COALESCE(
                                deduction_amount,
                                0
                            )
                        ),
                        0
                    ) AS total_deduction
                    '
                )

                ->value(
                    'total_deduction'
                );


        /*
        |--------------------------------------------------------------------------
        | TOTAL KEUNTUNGAN KOTOR
        |--------------------------------------------------------------------------
        */

        $totalGrossProfit =
            (float) (clone $salesBaseQuery)

                ->selectRaw(
                    '
                    COALESCE(
                        SUM(
                            COALESCE(
                                total_profit,
                                0
                            )
                        ),
                        0
                    ) AS total_gross_profit
                    '
                )

                ->value(
                    'total_gross_profit'
                );


        /*
        |--------------------------------------------------------------------------
        | TOTAL KEUNTUNGAN BERSIH
        |--------------------------------------------------------------------------
        |
        | Keuntungan Bersih
        | = Keuntungan Kotor
        | - Biaya Petugas
        |
        */

        $totalProfit =
            (float) (clone $salesBaseQuery)

                ->selectRaw(
                    '
                    COALESCE(
                        SUM(
                            COALESCE(
                                total_profit,
                                0
                            )
                            -
                            COALESCE(
                                deduction_amount,
                                0
                            )
                        ),
                        0
                    ) AS net_profit
                    '
                )

                ->value(
                    'net_profit'
                );


        /*
        |--------------------------------------------------------------------------
        | MARGIN KEUNTUNGAN BERSIH
        |--------------------------------------------------------------------------
        */

        $profitMargin =
            $totalSales > 0
                ? (
                    $totalProfit
                    /
                    $totalSales
                ) * 100
                : 0;


        /*
        |--------------------------------------------------------------------------
        | RATA-RATA NILAI TRANSAKSI
        |--------------------------------------------------------------------------
        */

        $averageTransaction =
            $totalStockOutTransactions > 0
                ? (
                    $totalSales
                    /
                    $totalStockOutTransactions
                )
                : 0;


        /*
        |--------------------------------------------------------------------------
        | DAFTAR TRANSAKSI PENJUALAN
        |--------------------------------------------------------------------------
        */

        $salesQuery =
            StockOut::query()

                ->select(
                    'stock_outs.*'
                )

                ->selectRaw(
                    '
                    (
                        COALESCE(
                            total_profit,
                            0
                        )
                        -
                        COALESCE(
                            deduction_amount,
                            0
                        )
                    ) AS net_profit
                    '
                )

                ->with([
                    'product',
                    'customer',
                ]);


        $this->applyDateFilter(
            $salesQuery,
            $startDate,
            $endDate
        );


        $sales =
            $salesQuery

                ->orderByDesc(
                    'transaction_date'
                )

                ->orderByDesc(
                    'id'
                )

                ->paginate(
                    10,
                    ['*'],
                    'sales_page'
                );


        /*
         * Mempertahankan filter saat pindah halaman.
         */
        $sales->appends(
            $request->query()
        );


        /*
        |--------------------------------------------------------------------------
        | DATA GRAFIK PENJUALAN PER TANGGAL
        |--------------------------------------------------------------------------
        */

        $dailySalesQuery =
            StockOut::query()

                ->select([

                    DB::raw(
                        '
                        DATE(
                            transaction_date
                        ) AS sale_date
                        '
                    ),

                    DB::raw(
                        '
                        SUM(
                            quantity
                        ) AS total_quantity
                        '
                    ),

                    DB::raw(
                        '
                        SUM(
                            subtotal
                        ) AS total_sales
                        '
                    ),

                    DB::raw(
                        '
                        SUM(
                            COALESCE(
                                total_profit,
                                0
                            )
                            -
                            COALESCE(
                                deduction_amount,
                                0
                            )
                        ) AS total_profit
                        '
                    ),

                    DB::raw(
                        '
                        SUM(
                            COALESCE(
                                deduction_amount,
                                0
                            )
                        ) AS total_deduction
                        '
                    ),

                ]);


        $this->applyDateFilter(
            $dailySalesQuery,
            $startDate,
            $endDate
        );


        $dailySales =
            $dailySalesQuery

                ->groupBy(
                    DB::raw(
                        'DATE(transaction_date)'
                    )
                )

                ->orderBy(
                    DB::raw(
                        'DATE(transaction_date)'
                    )
                )

                ->get();


        /*
        |--------------------------------------------------------------------------
        | LABEL GRAFIK
        |--------------------------------------------------------------------------
        */

        $chartLabels =
            $dailySales

                ->map(
                    function ($item) {

                        return date(
                            'd-m-Y',
                            strtotime(
                                $item->sale_date
                            )
                        );
                    }
                )

                ->values();


        /*
        |--------------------------------------------------------------------------
        | NILAI PENJUALAN GRAFIK
        |--------------------------------------------------------------------------
        */

        $chartSalesValues =
            $dailySales

                ->pluck(
                    'total_sales'
                )

                ->map(
                    function ($value) {

                        return (float) $value;
                    }
                )

                ->values();


        /*
        |--------------------------------------------------------------------------
        | NILAI KEUNTUNGAN BERSIH GRAFIK
        |--------------------------------------------------------------------------
        */

        $chartProfitValues =
            $dailySales

                ->pluck(
                    'total_profit'
                )

                ->map(
                    function ($value) {

                        return (float) $value;
                    }
                )

                ->values();


        /*
        |--------------------------------------------------------------------------
        | JUMLAH PRODUK TERJUAL
        |--------------------------------------------------------------------------
        */

        $chartQuantityValues =
            $dailySales

                ->pluck(
                    'total_quantity'
                )

                ->map(
                    function ($value) {

                        return (int) $value;
                    }
                )

                ->values();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'reports.index',
            compact(

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

                'totalQuantity',

                'totalPurchasePrice',

                'totalSellingPrice',

                'totalSales',

                'totalCapital',

                'totalDeduction',

                'totalGrossProfit',

                'totalProfit',

                'profitMargin',

                'averageTransaction',

                'chartLabels',

                'chartSalesValues',

                'chartProfitValues',

                'chartQuantityValues',

                'startDate',

                'endDate'
            )
        );
    }


    /**
     * Mengunduh laporan inventaris dalam format Excel.
     */
    public function exportExcel(
        Request $request
    ) {

        $validated =
            $this->validateDateFilter(
                $request
            );


        $startDate =
            $validated[
                'start_date'
            ] ?? null;


        $endDate =
            $validated[
                'end_date'
            ] ?? null;


        $fileName =
            'laporan-inventaris-'
            . now()->format(
                'Y-m-d-His'
            )
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

        return $request->validate(
            [

                'start_date' => [
                    'nullable',
                    'date',
                ],

                'end_date' => [
                    'nullable',
                    'date',
                    'after_or_equal:start_date',
                ],

            ],
            [

                'start_date.date' =>
                    'Tanggal mulai tidak valid.',

                'end_date.date' =>
                    'Tanggal selesai tidak valid.',

                'end_date.after_or_equal' =>
                    'Tanggal selesai harus sama atau setelah tanggal mulai.',

            ]
        );
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