<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\Supplier;
use App\Services\TelegramService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockInController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ], [
            'search.max' => 'Kata pencarian maksimal 100 karakter.',
            'start_date.date' => 'Tanggal mulai tidak valid.',
            'end_date.date' => 'Tanggal selesai tidak valid.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);

        $search = trim($validated['search'] ?? '');
        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;

        $summaryQuery = StockIn::query();

        $this->applyIndexFilters(
            $summaryQuery,
            $search,
            $startDate,
            $endDate
        );

        $totalTransactions = (clone $summaryQuery)->count();

        $totalStockIn = (int) (clone $summaryQuery)
            ->sum('quantity');

        /*
         * Nilai pembelian dihitung memakai harga beli produk saat ini.
         * Jika nanti harga historis diperlukan, tambahkan kolom
         * unit_purchase_price dan subtotal pada tabel stock_ins.
         */

        $purchaseQuery = clone $summaryQuery;

        $totalPurchase = (float) $purchaseQuery
            ->join(
                'products',
                'stock_ins.product_id',
                '=',
                'products.id'
            )
            ->selectRaw(
                'COALESCE(
                    SUM(
                        stock_ins.quantity
                        * products.purchase_price
                    ),
                    0
                ) as total_purchase'
            )
            ->value('total_purchase');

        $stockInQuery = StockIn::query()
            ->with([
                'product',
                'supplier',
            ]);

        $this->applyIndexFilters(
            $stockInQuery,
            $search,
            $startDate,
            $endDate
        );

        /** @var LengthAwarePaginator $stockIns */
        $stockIns = $stockInQuery
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate(10);

        $stockIns->withQueryString();

        $chartQuery = StockIn::query()
            ->select(
                'product_id',
                DB::raw(
                    'SUM(quantity) as total_quantity'
                )
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

        return view(
            'stock-ins.index',
            compact(
                'stockIns',
                'chartLabels',
                'chartValues',
                'totalStockIn',
                'totalTransactions',
                'totalPurchase',
                'search',
                'startDate',
                'endDate'
            )
        );
    }

    public function create()
    {
        $products = Product::orderBy(
            'product_name'
        )->get();

        $suppliers = Supplier::orderBy(
            'supplier_name'
        )->get();

        return view(
            'stock-ins.create',
            compact(
                'products',
                'suppliers'
            )
        );
    }

    public function store(Request $request)
    {
        $validatedData =
            $this->validateStockIn($request);

        /*
         * Simpan transaksi stok masuk dan update stok produk.
         *
         * $stockIn dikembalikan dari transaction agar setelah
         * transaction berhasil commit kita bisa mengirim Telegram.
         */
        $stockIn = DB::transaction(
            function () use ($validatedData) {

                $product = Product::where(
                    'id',
                    $validatedData['product_id']
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                $stockIn = StockIn::create(
                    $validatedData
                );

                $product->increment(
                    'stock',
                    (int) $validatedData['quantity']
                );

                return $stockIn;
            }
        );

        /*
         * Load relasi setelah transaksi database berhasil.
         */
        $stockIn->load([
            'product',
            'supplier',
        ]);

        /*
         * Kirim notifikasi Telegram.
         */
        $telegram = app(
            TelegramService::class
        );

        $productName =
            $stockIn->product?->product_name
            ?? 'Produk tidak ditemukan';

        $supplierName =
            $stockIn->supplier?->supplier_name
            ?? '-';

        $quantity =
            (int) $stockIn->quantity;

        $transactionDate =
            $stockIn->transaction_date
                ? $stockIn->transaction_date
                    ->format('d-m-Y')
                : '-';

        $currentStock =
            $stockIn->product
                ? (int) $stockIn->product->fresh()->stock
                : 0;

        $notes =
            $stockIn->notes ?: '-';

        $telegram->send(
            "<b>📦 STOK MASUK</b>\n\n"
            . "<b>Produk:</b> {$productName}\n"
            . "<b>Jumlah:</b> +{$quantity} unit\n"
            . "<b>Supplier:</b> {$supplierName}\n"
            . "<b>Tanggal:</b> {$transactionDate}\n"
            . "<b>Stok Sekarang:</b> {$currentStock} unit\n"
            . "<b>Catatan:</b> {$notes}\n\n"
            . "✅ Transaksi stok masuk berhasil dicatat."
        );

        return redirect()
            ->route('stock-ins.index')
            ->with(
                'success',
                'Stok masuk berhasil ditambahkan.'
            );
    }

    public function edit(StockIn $stockIn)
    {
        $products = Product::orderBy(
            'product_name'
        )->get();

        $suppliers = Supplier::orderBy(
            'supplier_name'
        )->get();

        return view(
            'stock-ins.edit',
            compact(
                'stockIn',
                'products',
                'suppliers'
            )
        );
    }

    public function update(
        Request $request,
        StockIn $stockIn
    ) {
        $validatedData =
            $this->validateStockIn($request);

        DB::transaction(
            function () use (
                $validatedData,
                $stockIn
            ) {
                $oldProduct = Product::where(
                    'id',
                    $stockIn->product_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                $oldProductId =
                    (int) $stockIn->product_id;

                $newProductId =
                    (int) $validatedData['product_id'];

                $oldQuantity =
                    (int) $stockIn->quantity;

                $newQuantity =
                    (int) $validatedData['quantity'];

                if (
                    $oldProductId
                    === $newProductId
                ) {
                    $newStock =
                        (int) $oldProduct->stock
                        + (
                            $newQuantity
                            - $oldQuantity
                        );

                    if ($newStock < 0) {
                        throw ValidationException::withMessages([
                            'quantity' =>
                                'Jumlah tidak dapat dikurangi karena sebagian stok sudah terjual.',
                        ]);
                    }

                    $oldProduct->update([
                        'stock' => $newStock,
                    ]);
                } else {
                    if (
                        (int) $oldProduct->stock
                        < $oldQuantity
                    ) {
                        throw ValidationException::withMessages([
                            'product_id' =>
                                'Produk tidak dapat diganti karena sebagian stok lama sudah terjual.',
                        ]);
                    }

                    $newProduct =
                        Product::where(
                            'id',
                            $newProductId
                        )
                            ->lockForUpdate()
                            ->firstOrFail();

                    $oldProduct->decrement(
                        'stock',
                        $oldQuantity
                    );

                    $newProduct->increment(
                        'stock',
                        $newQuantity
                    );
                }

                $stockIn->update(
                    $validatedData
                );
            }
        );

        return redirect()
            ->route('stock-ins.index')
            ->with(
                'success',
                'Transaksi stok masuk berhasil diperbarui.'
            );
    }

    public function destroy(
        StockIn $stockIn
    ) {
        DB::transaction(
            function () use ($stockIn) {

                $product = Product::where(
                    'id',
                    $stockIn->product_id
                )
                    ->lockForUpdate()
                    ->first();

                if ($product) {
                    if (
                        (int) $product->stock
                        < (int) $stockIn->quantity
                    ) {
                        throw ValidationException::withMessages([
                            'stock_in' =>
                                'Transaksi tidak dapat dihapus karena sebagian barang sudah terjual.',
                        ]);
                    }

                    $product->decrement(
                        'stock',
                        (int) $stockIn->quantity
                    );
                }

                $stockIn->delete();
            }
        );

        return redirect()
            ->route('stock-ins.index')
            ->with(
                'success',
                'Transaksi stok masuk berhasil dihapus.'
            );
    }

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
                                $productQuery
                                    ->where(
                                        'product_name',
                                        'like',
                                        '%'
                                        . $search
                                        . '%'
                                    );
                            }
                        )
                        ->orWhereHas(
                            'supplier',
                            function (
                                Builder $supplierQuery
                            ) use ($search) {
                                $supplierQuery
                                    ->where(
                                        'supplier_name',
                                        'like',
                                        '%'
                                        . $search
                                        . '%'
                                    );
                            }
                        )
                        ->orWhere(
                            'notes',
                            'like',
                            '%'
                            . $search
                            . '%'
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

    private function validateStockIn(
        Request $request
    ): array {
        return $request->validate([
            'product_id' => [
                'required',
                'exists:products,id',
            ],

            'supplier_id' => [
                'nullable',
                'exists:suppliers,id',
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
                'Produk tidak ditemukan.',

            'supplier_id.exists' =>
                'Supplier tidak ditemukan.',

            'quantity.required' =>
                'Jumlah stok masuk wajib diisi.',

            'quantity.integer' =>
                'Jumlah harus berupa angka bulat.',

            'quantity.min' =>
                'Jumlah minimal 1 unit.',

            'transaction_date.required' =>
                'Tanggal transaksi wajib diisi.',

            'transaction_date.date' =>
                'Format tanggal tidak valid.',

            'notes.max' =>
                'Catatan maksimal 1.000 karakter.',
        ]);
    }
}