<?php

namespace App\Http\Controllers;

use App\Exports\InventoryReportExport;
use App\Models\CashTransaction;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $this->validateDateFilter($request);
        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;

        $cashFilter = $this->validateCashPeriodFilter($request);
        $cashMonth = (int) ($cashFilter['cash_month'] ?? now()->month);
        $cashYear = (int) ($cashFilter['cash_year'] ?? now()->year);

        $cashPeriodStart = Carbon::create($cashYear, $cashMonth, 1)->startOfMonth();
        $cashPeriodEnd = $cashPeriodStart->copy()->endOfMonth();
        $cashPeriodLabel = $this->getIndonesianMonthName($cashMonth) . ' ' . $cashYear;

        $totalProducts = Product::count();
        $totalCurrentStock = (int) Product::sum('stock');
        $lowStockProducts = Product::whereBetween('stock', [1, 5])->count();
        $outOfStockProducts = Product::where('stock', '<=', 0)->count();

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
                ->value('inventory_value');

        $stockInQuery = StockIn::query();
        $stockOutQuery = StockOut::query();

        $this->applyDateFilter($stockInQuery, $startDate, $endDate);
        $this->applyDateFilter($stockOutQuery, $startDate, $endDate);

        $totalStockIn = (int) (clone $stockInQuery)->sum('quantity');
        $totalStockOut = (int) (clone $stockOutQuery)->sum('quantity');
        $totalStockInTransactions = (clone $stockInQuery)->count();
        $totalStockOutTransactions = (clone $stockOutQuery)->count();

        $products =
            Product::query()
                ->withSum([
                    'stockIns as total_stock_in' => function ($query) use ($startDate, $endDate) {
                        $this->applyDateFilter($query, $startDate, $endDate);
                    },
                ], 'quantity')
                ->withSum([
                    'stockOuts as total_stock_out' => function ($query) use ($startDate, $endDate) {
                        $this->applyDateFilter($query, $startDate, $endDate);
                    },
                ], 'quantity')
                ->orderBy('product_name')
                ->get();

        $salesBaseQuery = StockOut::query();
        $this->applyDateFilter($salesBaseQuery, $startDate, $endDate);

        $totalQuantity = (int) (clone $salesBaseQuery)->sum('quantity');

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
                ->value('total_purchase_price');

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
                ->value('total_selling_price');

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
                ->value('total_sales');

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
                ->value('total_capital');

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
                ->value('total_deduction');

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
                ->value('total_gross_profit');

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
                ->value('net_profit');

        $profitMargin =
            $totalSales > 0
                ? ($totalProfit / $totalSales) * 100
                : 0;

        $averageTransaction =
            $totalStockOutTransactions > 0
                ? ($totalSales / $totalStockOutTransactions)
                : 0;

        $salesQuery =
            StockOut::query()
                ->select('stock_outs.*')
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

        $this->applyDateFilter($salesQuery, $startDate, $endDate);

        $sales =
            $salesQuery
                ->orderByDesc('transaction_date')
                ->orderByDesc('id')
                ->paginate(
                    10,
                    ['*'],
                    'sales_page'
                );

        $sales->appends($request->query());

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

        $this->applyDateFilter($dailySalesQuery, $startDate, $endDate);

        $dailySales =
            $dailySalesQuery
                ->groupBy(DB::raw('DATE(transaction_date)'))
                ->orderBy(DB::raw('DATE(transaction_date)'))
                ->get();

        $chartLabels =
            $dailySales
                ->map(function ($item) {
                    return date(
                        'd-m-Y',
                        strtotime($item->sale_date)
                    );
                })
                ->values();

        $chartSalesValues =
            $dailySales
                ->pluck('total_sales')
                ->map(function ($value) {
                    return (float) $value;
                })
                ->values();

        $chartProfitValues =
            $dailySales
                ->pluck('total_profit')
                ->map(function ($value) {
                    return (float) $value;
                })
                ->values();

        $chartQuantityValues =
            $dailySales
                ->pluck('total_quantity')
                ->map(function ($value) {
                    return (int) $value;
                })
                ->values();

        /*
        |--------------------------------------------------------------------------
        | LAPORAN KAS INVENTORY BULANAN
        |--------------------------------------------------------------------------
        */

        $cashOpeningIncome =
            (float) CashTransaction::query()
                ->where('approval_status', 'approved')
                ->where('type', 'income')
                ->whereDate(
                    'transaction_date',
                    '<',
                    $cashPeriodStart->toDateString()
                )
                ->sum('amount');

        $cashOpeningExpense =
            (float) CashTransaction::query()
                ->where('approval_status', 'approved')
                ->where('type', 'expense')
                ->whereDate(
                    'transaction_date',
                    '<',
                    $cashPeriodStart->toDateString()
                )
                ->sum('amount');

        $cashOpeningBalance =
            $cashOpeningIncome
            - $cashOpeningExpense;

        $approvedCashBase =
            CashTransaction::query()
                ->where('approval_status', 'approved')
                ->whereBetween(
                    'transaction_date',
                    [
                        $cashPeriodStart->toDateString(),
                        $cashPeriodEnd->toDateString(),
                    ]
                );

        $cashTotalIncome =
            (float) (clone $approvedCashBase)
                ->where('type', 'income')
                ->sum('amount');

        $cashTotalExpense =
            (float) (clone $approvedCashBase)
                ->where('type', 'expense')
                ->sum('amount');

        $cashClosingBalance =
            $cashOpeningBalance
            + $cashTotalIncome
            - $cashTotalExpense;

        $cashIncomeCount =
            (clone $approvedCashBase)
                ->where('type', 'income')
                ->count();

        $cashExpenseCount =
            (clone $approvedCashBase)
                ->where('type', 'expense')
                ->count();

        $pendingCashBase =
            CashTransaction::query()
                ->where('approval_status', 'pending')
                ->whereBetween(
                    'transaction_date',
                    [
                        $cashPeriodStart->toDateString(),
                        $cashPeriodEnd->toDateString(),
                    ]
                );

        $cashPendingIncome =
            (float) (clone $pendingCashBase)
                ->where('type', 'income')
                ->sum('amount');

        $cashPendingExpense =
            (float) (clone $pendingCashBase)
                ->where('type', 'expense')
                ->sum('amount');

        $cashPendingIncomeCount =
            (clone $pendingCashBase)
                ->where('type', 'income')
                ->count();

        $cashPendingExpenseCount =
            (clone $pendingCashBase)
                ->where('type', 'expense')
                ->count();

        $cashIncomeByCategory =
            CashTransaction::query()
                ->select('category')
                ->selectRaw('COUNT(*) AS transaction_count')
                ->selectRaw('SUM(amount) AS total_amount')
                ->where('approval_status', 'approved')
                ->where('type', 'income')
                ->whereBetween(
                    'transaction_date',
                    [
                        $cashPeriodStart->toDateString(),
                        $cashPeriodEnd->toDateString(),
                    ]
                )
                ->groupBy('category')
                ->orderByDesc('total_amount')
                ->get();

        $cashExpenseByCategory =
            CashTransaction::query()
                ->select('category')
                ->selectRaw('COUNT(*) AS transaction_count')
                ->selectRaw('SUM(amount) AS total_amount')
                ->where('approval_status', 'approved')
                ->where('type', 'expense')
                ->whereBetween(
                    'transaction_date',
                    [
                        $cashPeriodStart->toDateString(),
                        $cashPeriodEnd->toDateString(),
                    ]
                )
                ->groupBy('category')
                ->orderByDesc('total_amount')
                ->get();

        $cashTransactions =
            CashTransaction::query()
                ->whereBetween(
                    'transaction_date',
                    [
                        $cashPeriodStart->toDateString(),
                        $cashPeriodEnd->toDateString(),
                    ]
                )
                ->orderByDesc('transaction_date')
                ->orderByDesc('id')
                ->paginate(
                    10,
                    ['*'],
                    'cash_page'
                );

        $cashTransactions->appends(
            $request->query()
        );

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
                'endDate',

                'cashMonth',
                'cashYear',
                'cashPeriodStart',
                'cashPeriodEnd',
                'cashPeriodLabel',
                'cashOpeningBalance',
                'cashTotalIncome',
                'cashTotalExpense',
                'cashClosingBalance',
                'cashPendingIncome',
                'cashPendingExpense',
                'cashIncomeCount',
                'cashExpenseCount',
                'cashPendingIncomeCount',
                'cashPendingExpenseCount',
                'cashIncomeByCategory',
                'cashExpenseByCategory',
                'cashTransactions'
            )
        );
    }


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


    private function validateCashPeriodFilter(
        Request $request
    ): array {
        return $request->validate(
            [
                'cash_month' => [
                    'nullable',
                    'integer',
                    'between:1,12',
                ],

                'cash_year' => [
                    'nullable',
                    'integer',
                    'between:2020,2100',
                ],
            ],
            [
                'cash_month.integer' =>
                    'Bulan laporan Kas Inventory tidak valid.',

                'cash_month.between' =>
                    'Bulan laporan Kas Inventory harus antara 1 sampai 12.',

                'cash_year.integer' =>
                    'Tahun laporan Kas Inventory tidak valid.',

                'cash_year.between' =>
                    'Tahun laporan Kas Inventory tidak valid.',
            ]
        );
    }


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


    private function getIndonesianMonthName(
        int $month
    ): string {
        $months = [
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

        return $months[$month]
            ?? 'Tidak Diketahui';
    }
}
