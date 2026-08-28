<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\StockOut;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockOutController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],
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
            'search.max' =>
                'Kata pencarian maksimal 100 karakter.',
            'start_date.date' =>
                'Tanggal mulai tidak valid.',
            'end_date.date' =>
                'Tanggal selesai tidak valid.',
            'end_date.after_or_equal' =>
                'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);

        $search = trim($validated['search'] ?? '');
        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Ringkasan transaksi berdasarkan filter
        |--------------------------------------------------------------------------
        */

        $summaryQuery = StockOut::query();

        $this->applyIndexFilters(
            $summaryQuery,
            $search,
            $startDate,
            $endDate
        );

        $totalTransactions =
            (clone $summaryQuery)->count();

        $totalSales =
            (float) (clone $summaryQuery)
                ->sum('subtotal');

        $totalProfit =
            (float) (clone $summaryQuery)
                ->sum('total_profit');

        /*
        |--------------------------------------------------------------------------
        | Daftar transaksi stok keluar
        |--------------------------------------------------------------------------
        */

        $stockOutQuery = StockOut::query()
            ->with([
                'product',
                'customer',
            ]);

        $this->applyIndexFilters(
            $stockOutQuery,
            $search,
            $startDate,
            $endDate
        );

       /** @var \Illuminate\Pagination\LengthAwarePaginator $stockOuts */
$stockOuts = $stockOutQuery
    ->orderByDesc('transaction_date')
    ->orderByDesc('id')
    ->paginate(10);

$stockOuts->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Data grafik berdasarkan filter
        |--------------------------------------------------------------------------
        */

        $chartQuery = StockOut::query()
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->with('product');

        $this->applyIndexFilters(
            $chartQuery,
            $search,
            $startDate,
            $endDate
        );

        $chartData = $chartQuery
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->get();

        $chartLabels = $chartData
            ->map(function ($item) {
                return $item->product?->product_name
                    ?? 'Produk telah dihapus';
            })
            ->values();

        $chartValues = $chartData
            ->pluck('total_quantity')
            ->map(function ($quantity) {
                return (int) $quantity;
            })
            ->values();

        $totalStockOut = $chartValues->sum();

        return view('stock-outs.index', compact(
            'stockOuts',
            'chartLabels',
            'chartValues',
            'totalStockOut',
            'totalTransactions',
            'totalSales',
            'totalProfit',
            'search',
            'startDate',
            'endDate'
        ));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)
            ->orderBy('product_name')
            ->get();

        $customers = Customer::orderBy('customer_name')
            ->get();

        return view('stock-outs.create', compact(
            'products',
            'customers'
        ));
    }

    public function store(Request $request)
    {
        $validated =
            $this->validateStockOut($request);

        DB::transaction(function () use ($validated) {
            $product = Product::where(
                'id',
                $validated['product_id']
            )
                ->lockForUpdate()
                ->firstOrFail();

            $quantity =
                (int) $validated['quantity'];

            if ($quantity > $product->stock) {
                throw ValidationException::withMessages([
                    'quantity' =>
                        'Jumlah barang keluar melebihi stok tersedia. '
                        . 'Stok saat ini: '
                        . $product->stock
                        . ' unit.',
                ]);
            }

            $hargaBeli =
                (float) $product->purchase_price;

            $hargaJual =
                (float) $product->selling_price;

            StockOut::create([
                'product_id' =>
                    $product->id,
                'customer_id' =>
                    $validated['customer_id'] ?? null,
                'quantity' =>
                    $quantity,
                'unit_purchase_price' =>
                    $hargaBeli,
                'unit_selling_price' =>
                    $hargaJual,
                'subtotal' =>
                    $hargaJual * $quantity,
                'total_profit' =>
                    ($hargaJual - $hargaBeli)
                    * $quantity,
                'transaction_date' =>
                    $validated['transaction_date'],
                'notes' =>
                    $validated['notes'] ?? null,
            ]);

            $product->decrement(
                'stock',
                $quantity
            );
        });

        return redirect()
            ->route('stock-outs.index')
            ->with(
                'success',
                'Transaksi penjualan berhasil disimpan.'
            );
    }

    public function edit(StockOut $stockOut)
    {
        $products = Product::where('stock', '>', 0)
            ->orWhere('id', $stockOut->product_id)
            ->orderBy('product_name')
            ->get();

        $customers = Customer::orderBy('customer_name')
            ->get();

        return view('stock-outs.edit', compact(
            'stockOut',
            'products',
            'customers'
        ));
    }

    public function update(
        Request $request,
        StockOut $stockOut
    ) {
        $validated =
            $this->validateStockOut($request);

        DB::transaction(function () use (
            $validated,
            $stockOut
        ) {
            $oldProduct = Product::where(
                'id',
                $stockOut->product_id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $newProductId =
                (int) $validated['product_id'];

            $newQuantity =
                (int) $validated['quantity'];

            $oldQuantity =
                (int) $stockOut->quantity;

            if (
                $newProductId
                === (int) $oldProduct->id
            ) {
                $availableStock =
                    (int) $oldProduct->stock
                    + $oldQuantity;

                if ($newQuantity > $availableStock) {
                    throw ValidationException::withMessages([
                        'quantity' =>
                            'Jumlah barang melebihi stok tersedia. '
                            . 'Stok yang dapat digunakan: '
                            . $availableStock
                            . ' unit.',
                    ]);
                }

                $newProduct = $oldProduct;

                $newStock =
                    $availableStock - $newQuantity;

                $newProduct->update([
                    'stock' => $newStock,
                ]);
            } else {
                $oldProduct->increment(
                    'stock',
                    $oldQuantity
                );

                $newProduct = Product::where(
                    'id',
                    $newProductId
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($newQuantity > $newProduct->stock) {
                    throw ValidationException::withMessages([
                        'quantity' =>
                            'Jumlah barang melebihi stok produk baru. '
                            . 'Stok tersedia: '
                            . $newProduct->stock
                            . ' unit.',
                    ]);
                }

                $newProduct->decrement(
                    'stock',
                    $newQuantity
                );
            }

            $hargaBeli =
                (float) $newProduct->purchase_price;

            $hargaJual =
                (float) $newProduct->selling_price;

            $stockOut->update([
                'product_id' =>
                    $newProduct->id,
                'customer_id' =>
                    $validated['customer_id'] ?? null,
                'quantity' =>
                    $newQuantity,
                'unit_purchase_price' =>
                    $hargaBeli,
                'unit_selling_price' =>
                    $hargaJual,
                'subtotal' =>
                    $hargaJual * $newQuantity,
                'total_profit' =>
                    ($hargaJual - $hargaBeli)
                    * $newQuantity,
                'transaction_date' =>
                    $validated['transaction_date'],
                'notes' =>
                    $validated['notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('stock-outs.index')
            ->with(
                'success',
                'Transaksi stok keluar berhasil diperbarui.'
            );
    }

    public function destroy(StockOut $stockOut)
    {
        DB::transaction(function () use ($stockOut) {
            $product = Product::where(
                'id',
                $stockOut->product_id
            )
                ->lockForUpdate()
                ->first();

            if ($product) {
                $product->increment(
                    'stock',
                    $stockOut->quantity
                );
            }

            $stockOut->delete();
        });

        return redirect()
            ->route('stock-outs.index')
            ->with(
                'success',
                'Transaksi stok keluar berhasil dihapus dan stok barang telah dikembalikan.'
            );
    }

    /**
     * Menerapkan pencarian dan filter tanggal.
     */
    private function applyIndexFilters(
        Builder $query,
        string $search,
        ?string $startDate,
        ?string $endDate
    ): void {
        if ($search !== '') {
            $query->where(
                function (
                    Builder $query
                ) use ($search) {
                    $query
                        ->whereHas(
                            'product',
                            function (
                                Builder $productQuery
                            ) use ($search) {
                                $productQuery->where(
                                    'product_name',
                                    'like',
                                    '%' . $search . '%'
                                );
                            }
                        )
                        ->orWhereHas(
                            'customer',
                            function (
                                Builder $customerQuery
                            ) use ($search) {
                                $customerQuery->where(
                                    'customer_name',
                                    'like',
                                    '%' . $search . '%'
                                );
                            }
                        )
                        ->orWhere(
                            'notes',
                            'like',
                            '%' . $search . '%'
                        );
                }
            );
        }

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

    private function validateStockOut(
        Request $request
    ): array {
        return $request->validate([
            'product_id' => [
                'required',
                'exists:products,id',
            ],
            'customer_id' => [
                'nullable',
                'exists:customers,id',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'transaction_date' => [
                'required',
                'date',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'product_id.required' =>
                'Produk wajib dipilih.',
            'product_id.exists' =>
                'Produk yang dipilih tidak ditemukan.',
            'customer_id.exists' =>
                'Pelanggan yang dipilih tidak ditemukan.',
            'quantity.required' =>
                'Jumlah barang wajib diisi.',
            'quantity.integer' =>
                'Jumlah barang harus berupa angka bulat.',
            'quantity.min' =>
                'Jumlah barang minimal 1 unit.',
            'transaction_date.required' =>
                'Tanggal transaksi wajib diisi.',
            'transaction_date.date' =>
                'Format tanggal tidak valid.',
            'notes.max' =>
                'Catatan maksimal 1.000 karakter.',
        ]);
    }
}