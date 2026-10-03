<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SalesTarget;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesTargetController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->query('month', now('Asia/Dili')->month);
        $year = (int) $request->query('year', now('Asia/Dili')->year);

        if ($month < 1 || $month > 12) {
            $month = now('Asia/Dili')->month;
        }

        if ($year < 2020 || $year > 2100) {
            $year = now('Asia/Dili')->year;
        }

        $startDate = Carbon::create($year, $month, 1, 0, 0, 0, 'Asia/Dili')
            ->startOfMonth()
            ->toDateString();

        $endDate = Carbon::create($year, $month, 1, 0, 0, 0, 'Asia/Dili')
            ->endOfMonth()
            ->toDateString();

        $products = Product::query()
            ->orderBy('product_name')
            ->get();

        $targets = SalesTarget::query()
            ->with('product')
            ->where('month', $month)
            ->where('year', $year)
            ->orderBy('id')
            ->get();

        $targetProductIds = $targets
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();

        $actualSales = collect();

        if ($targetProductIds->isNotEmpty()) {
            $actualSales = DB::table('stock_outs')
                ->select('product_id')
                ->selectRaw('COALESCE(SUM(quantity), 0) AS sold_qty')
                ->selectRaw('COALESCE(SUM(staff_deposited_amount), 0) AS actual_revenue')
                ->selectRaw('COALESCE(SUM(unit_purchase_price * quantity), 0) AS actual_cost')
                ->selectRaw('
                    COALESCE(
                        SUM(
                            staff_deposited_amount
                            - (unit_purchase_price * quantity)
                        ),
                        0
                    ) AS net_profit
                ')
                ->whereIn('product_id', $targetProductIds)

                /*
                 * Hanya transaksi yang setoran petugas sudah lunas
                 * dan sudah diverifikasi Admin yang dihitung.
                 */
                ->where('staff_deposit_status', 'paid')
                ->whereNotNull('deposit_verified_by')
                ->whereNotNull('staff_deposited_at')

                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->groupBy('product_id')
                ->get()
                ->keyBy('product_id');
        }

        foreach ($targets as $target) {
            $actual = $actualSales->get($target->product_id);

            $soldQty = (int) ($actual->sold_qty ?? 0);
            $actualRevenue = (float) ($actual->actual_revenue ?? 0);
            $actualCost = (float) ($actual->actual_cost ?? 0);
            $netProfit = (float) ($actual->net_profit ?? 0);

            $targetQty = (int) $target->target_qty;
            $targetRevenue = (float) $target->target_revenue;

            $qtyProgress = $targetQty > 0
                ? min(100, ($soldQty / $targetQty) * 100)
                : 0;

            $unsoldQty = max($targetQty - $soldQty, 0);
            $unsoldPercentage = max(100 - $qtyProgress, 0);
            $excessQty = max($soldQty - $targetQty, 0);

            $revenueProgress = $targetRevenue > 0
                ? min(100, ($actualRevenue / $targetRevenue) * 100)
                : 0;

            $profitMargin = $actualRevenue > 0
                ? ($netProfit / $actualRevenue) * 100
                : 0;

            [$status, $statusClass] = $this->resolveStatus($qtyProgress);

            $target->setAttribute('sold_qty', $soldQty);
            $target->setAttribute('unsold_qty', $unsoldQty);
            $target->setAttribute('excess_qty', $excessQty);

            $target->setAttribute('qty_progress', round($qtyProgress, 2));
            $target->setAttribute('unsold_percentage', round($unsoldPercentage, 2));

            $target->setAttribute('actual_revenue', round($actualRevenue, 2));
            $target->setAttribute('actual_cost', round($actualCost, 2));
            $target->setAttribute('revenue_progress', round($revenueProgress, 2));

            $target->setAttribute('sales_profit', round($netProfit, 2));
            $target->setAttribute('profit_margin', round($profitMargin, 2));

            $target->setAttribute('target_status', $status);
            $target->setAttribute('target_status_class', $statusClass);
        }

        $totalTargetQty = (int) $targets->sum('target_qty');
        $totalSoldQty = (int) $targets->sum('sold_qty');
        $totalUnsoldQty = max($totalTargetQty - $totalSoldQty, 0);

        $totalTargetRevenue = (float) $targets->sum('target_revenue');
        $totalActualRevenue = (float) $targets->sum('actual_revenue');
        $totalSalesProfit = (float) $targets->sum('sales_profit');

        $overallQtyProgress = $totalTargetQty > 0
            ? min(100, ($totalSoldQty / $totalTargetQty) * 100)
            : 0;

        $overallUnsoldPercentage = max(100 - $overallQtyProgress, 0);

        $overallRevenueProgress = $totalTargetRevenue > 0
            ? min(100, ($totalActualRevenue / $totalTargetRevenue) * 100)
            : 0;

        $overallProfitMargin = $totalActualRevenue > 0
            ? ($totalSalesProfit / $totalActualRevenue) * 100
            : 0;

        return view('sales-targets.index', compact(
            'products',
            'targets',
            'month',
            'year',
            'startDate',
            'endDate',
            'totalTargetQty',
            'totalSoldQty',
            'totalUnsoldQty',
            'totalTargetRevenue',
            'totalActualRevenue',
            'totalSalesProfit',
            'overallQtyProgress',
            'overallUnsoldPercentage',
            'overallRevenueProgress',
            'overallProfitMargin'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'target_qty' => ['required', 'integer', 'min:1'],
            'target_revenue' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $exists = SalesTarget::query()
            ->where('product_id', $validated['product_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'Target untuk produk ini pada bulan tersebut sudah ada.');
        }

        SalesTarget::create($validated);

        return redirect()
            ->route('sales-targets.index', [
                'month' => $validated['month'],
                'year' => $validated['year'],
            ])
            ->with('success', 'Target penjualan berhasil ditambahkan.');
    }

    public function update(Request $request, SalesTarget $salesTarget)
    {
        $validated = $request->validate([
            'target_qty' => ['required', 'integer', 'min:1'],
            'target_revenue' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $salesTarget->update($validated);

        return redirect()
            ->route('sales-targets.index', [
                'month' => $salesTarget->month,
                'year' => $salesTarget->year,
            ])
            ->with('success', 'Target penjualan berhasil diperbarui.');
    }

    public function destroy(SalesTarget $salesTarget)
    {
        $month = $salesTarget->month;
        $year = $salesTarget->year;

        $salesTarget->delete();

        return redirect()
            ->route('sales-targets.index', [
                'month' => $month,
                'year' => $year,
            ])
            ->with('success', 'Target penjualan berhasil dihapus.');
    }

    private function resolveStatus(float $progress): array
    {
        if ($progress >= 100) {
            return ['Tercapai', 'status-success'];
        }

        if ($progress >= 75) {
            return ['Hampir Tercapai', 'status-near'];
        }

        if ($progress >= 50) {
            return ['Sedang Berjalan', 'status-progress'];
        }

        if ($progress > 0) {
            return ['Rendah', 'status-low'];
        }

        return ['Tidak Terjual', 'status-none'];
    }
}
