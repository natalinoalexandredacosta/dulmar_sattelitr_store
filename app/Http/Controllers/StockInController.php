<?php

namespace App\Http\Controllers;

use App\Models\CashTransaction;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\Supplier;
use App\Services\StockTelegramService;
use App\Services\TelegramService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockInController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

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

        $search =
            trim(
                $validated['search']
                ?? ''
            );

        $startDate =
            $validated['start_date']
            ?? null;

        $endDate =
            $validated['end_date']
            ?? null;


        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        $summaryQuery =
            StockIn::query();

        $this->applyIndexFilters(
            $summaryQuery,
            $search,
            $startDate,
            $endDate
        );

        $totalTransactions =
            (clone $summaryQuery)
                ->count();

        $totalStockIn =
            (int) (clone $summaryQuery)
                ->sum('quantity');


        $purchaseQuery =
            clone $summaryQuery;

        $totalPurchase =
            (float) $purchaseQuery
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
                ->value(
                    'total_purchase'
                );


        /*
        |--------------------------------------------------------------------------
        | TABLE
        |--------------------------------------------------------------------------
        */

        $stockInQuery =
            StockIn::query()
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
        $stockIns =
            $stockInQuery
                ->orderByDesc(
                    'transaction_date'
                )
                ->orderByDesc('id')
                ->paginate(10);

        $stockIns->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | CHART
        |--------------------------------------------------------------------------
        */

        $chartQuery =
            StockIn::query()
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

        $chartData =
            $chartQuery
                ->groupBy('product_id')
                ->orderByDesc(
                    'total_quantity'
                )
                ->get();

        $chartLabels =
            $chartData
                ->map(
                    function ($item) {
                        return
                            $item->product?->product_name
                            ?? 'Produk telah dihapus';
                    }
                )
                ->values();

        $chartValues =
            $chartData
                ->pluck(
                    'total_quantity'
                )
                ->map(
                    function ($quantity) {
                        return (int) $quantity;
                    }
                )
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


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $products =
            Product::orderBy(
                'product_name'
            )
                ->get();

        $suppliers =
            Supplier::orderBy(
                'supplier_name'
            )
                ->get();

        return view(
            'stock-ins.create',
            compact(
                'products',
                'suppliers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    |
    | Barang langsung masuk stok.
    |
    | Untuk produk biasa:
    | - sistem membuat Cash Keluar
    | - status cash = pending
    | - belum mengurangi saldo
    | - Admin harus Setujui / Tolak
    |
    | TV Voucher tidak memakai Kas Inventory pribadi.
    |
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validatedData =
            $this->validateStockIn(
                $request
            );


        $result =
            DB::transaction(
                function () use (
                    $validatedData
                ) {
                    /*
                    |--------------------------------------------------------------------------
                    | LOCK PRODUCT
                    |--------------------------------------------------------------------------
                    */

                    $product =
                        Product::where(
                            'id',
                            $validatedData[
                                'product_id'
                            ]
                        )
                            ->lockForUpdate()
                            ->firstOrFail();


                    /*
                    |--------------------------------------------------------------------------
                    | CREATE STOCK IN
                    |--------------------------------------------------------------------------
                    */

                    $stockIn =
                        StockIn::create(
                            $validatedData
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | TAMBAH STOK
                    |--------------------------------------------------------------------------
                    */

                    $product->increment(
                        'stock',
                        (int) $validatedData[
                            'quantity'
                        ]
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | CASH REQUEST
                    |--------------------------------------------------------------------------
                    */

                    $cashTransaction =
                        null;


                    if (
                        !$this->isTvVoucherProduct(
                            $product
                        )
                    ) {
                        $quantity =
                            (int) $validatedData[
                                'quantity'
                            ];


                        $purchasePrice =
                            (float) $product
                                ->purchase_price;


                        $totalPurchase =
                            $quantity
                            * $purchasePrice;


                        $supplierName =
                            '-';


                        if (
                            !empty(
                                $validatedData[
                                    'supplier_id'
                                ]
                            )
                        ) {
                            $supplierName =
                                Supplier::where(
                                    'id',
                                    $validatedData[
                                        'supplier_id'
                                    ]
                                )
                                    ->value(
                                        'supplier_name'
                                    )
                                ?? '-';
                        }


                        $createdBy =
                            auth()->user()?->name
                            ?? 'Administrator';


                        /*
                        |--------------------------------------------------------------------------
                        | CASH KELUAR PENDING
                        |--------------------------------------------------------------------------
                        |
                        | Belum mengurangi saldo.
                        |
                        | Saldo baru berkurang setelah Admin Setujui.
                        |
                        */

                        $cashTransaction =
                            CashTransaction::create([
                                'type' =>
                                    'expense',

                                'source' =>
                                    'stock_purchase',

                                'category' =>
                                    'Belanja Stok',

                                'borrower_name' =>
                                    null,

                                'loan_reference' =>
                                    null,

                                'reference_id' =>
                                    $stockIn->id,

                                'amount' =>
                                    $totalPurchase,

                                'description' =>
                                    'Pembelian stok '
                                    . $product->product_name
                                    . ' sebanyak '
                                    . $quantity
                                    . ' unit dari supplier '
                                    . $supplierName
                                    . ' - Stok Masuk #'
                                    . $stockIn->id,

                                'approval_status' =>
                                    'pending',

                                'approved_by' =>
                                    null,

                                'approved_at' =>
                                    null,

                                'rejection_reason' =>
                                    null,

                                'transaction_date' =>
                                    $validatedData[
                                        'transaction_date'
                                    ],

                                'created_by' =>
                                    $createdBy,
                            ]);
                    }


                    return [
                        'stock_in' =>
                            $stockIn,

                        'cash_transaction' =>
                            $cashTransaction,
                    ];
                }
            );


        /** @var StockIn $stockIn */
        $stockIn =
            $result[
                'stock_in'
            ];


        /** @var CashTransaction|null $cashTransaction */
        $cashTransaction =
            $result[
                'cash_transaction'
            ];


        /*
        |--------------------------------------------------------------------------
        | LOAD RELATION
        |--------------------------------------------------------------------------
        */

        $stockIn->load([
            'product',
            'supplier',
        ]);

        $productFresh =
            $stockIn->product?->fresh();


        /*
        |--------------------------------------------------------------------------
        | BOT STOK BARANG PRIBADI
        |--------------------------------------------------------------------------
        */

        if (
            $productFresh
            &&
            !$this->isTvVoucherProduct(
                $productFresh
            )
        ) {
            $stockTelegram =
                app(
                    StockTelegramService::class
                );


            $supplierName =
                $stockIn->supplier?->supplier_name
                ?? '-';


            $quantity =
                (int) $stockIn
                    ->quantity;


            $transactionDate =
                $stockIn->transaction_date
                    ? $stockIn
                        ->transaction_date
                        ->format(
                            'd-m-Y'
                        )
                    : '-';


            $notes =
                $stockIn->notes
                ?: '-';


            $purchasePrice =
                number_format(
                    (float) $productFresh
                        ->purchase_price,
                    2
                );


            $sellingPrice =
                number_format(
                    (float) $productFresh
                        ->selling_price,
                    2
                );


            $totalPurchase =
                (float) $productFresh
                    ->purchase_price
                * $quantity;


            $totalPurchaseFormatted =
                number_format(
                    $totalPurchase,
                    2
                );


            /*
            |--------------------------------------------------------------------------
            | NOTIFIKASI STOK MASUK
            |--------------------------------------------------------------------------
            */

            $stockTelegram->send(
                "<b>📥 STOK MASUK</b>\n\n"
                . "<b>Produk:</b> {$productFresh->product_name}\n"
                . "<b>Kategori:</b> "
                . (
                    $productFresh->category
                    ?: '-'
                )
                . "\n"
                . "<b>Jumlah Masuk:</b> +{$quantity} unit\n"
                . "<b>Supplier:</b> {$supplierName}\n"
                . "<b>Harga Beli:</b> \${$purchasePrice}\n"
                . "<b>Total Pembelian:</b> \${$totalPurchaseFormatted}\n"
                . "<b>Harga Jual:</b> \${$sellingPrice}\n"
                . "<b>Tanggal:</b> {$transactionDate}\n"
                . "<b>Stok Sekarang:</b> {$productFresh->stock} unit\n"
                . "<b>Catatan:</b> {$notes}\n\n"
                . "✅ Stok barang berhasil ditambahkan.\n"
                . "⏳ Pengeluaran kas masih menunggu persetujuan Admin."
            );


            /*
            |--------------------------------------------------------------------------
            | TELEGRAM APPROVAL CASH
            |--------------------------------------------------------------------------
            */

            if ($cashTransaction) {
                $cashId =
                    $cashTransaction->id;


                $cashBalance =
                    CashTransaction::currentBalance();


                $cashBalanceFormatted =
                    number_format(
                        $cashBalance,
                        2
                    );


                $approvalMessage =
                    "<b>💸 PERMINTAAN CASH KELUAR</b>\n\n"
                    . "<b>Cash ID:</b> #{$cashId}\n"
                    . "<b>Kategori:</b> Belanja Stok\n"
                    . "<b>Produk:</b> {$productFresh->product_name}\n"
                    . "<b>Jumlah:</b> {$quantity} unit\n"
                    . "<b>Harga Beli:</b> \${$purchasePrice}\n"
                    . "<b>Total Pengeluaran:</b> \${$totalPurchaseFormatted}\n"
                    . "<b>Supplier:</b> {$supplierName}\n"
                    . "<b>Saldo Kas Saat Ini:</b> \${$cashBalanceFormatted}\n"
                    . "<b>Status:</b> ⏳ MENUNGGU\n\n"
                    . "Silakan Setujui atau Tolak pengeluaran kas ini.";


                $stockTelegram
                    ->sendCashApprovalRequest(
                        $cashId,
                        $approvalMessage
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | BOT TV VOUCHER
        |--------------------------------------------------------------------------
        |
        | Tetap menggunakan bot TV Voucher lama.
        |
        |--------------------------------------------------------------------------
        */

        if (
            $this->isTvVoucherProduct(
                $stockIn->product
            )
        ) {
            $telegram =
                app(
                    TelegramService::class
                );


            $productName =
                $stockIn->product?->product_name
                ?? 'Produk tidak ditemukan';


            $supplierName =
                $stockIn->supplier?->supplier_name
                ?? '-';


            $quantity =
                (int) $stockIn
                    ->quantity;


            $transactionDate =
                $stockIn->transaction_date
                    ? $stockIn
                        ->transaction_date
                        ->format(
                            'd-m-Y'
                        )
                    : '-';


            $currentStock =
                $stockIn->product
                    ? (int) $stockIn
                        ->product
                        ->fresh()
                        ->stock
                    : 0;


            $notes =
                $stockIn->notes
                ?: '-';


            $telegram->send(
                "<b>📺 TV VOUCHER - STOK MASUK</b>\n\n"
                . "<b>Produk:</b> {$productName}\n"
                . "<b>Jumlah:</b> +{$quantity} unit\n"
                . "<b>Supplier:</b> {$supplierName}\n"
                . "<b>Tanggal:</b> {$transactionDate}\n"
                . "<b>Stok Sekarang:</b> {$currentStock} unit\n"
                . "<b>Catatan:</b> {$notes}\n\n"
                . "✅ Transaksi TV Voucher berhasil dicatat."
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        if ($cashTransaction) {
            return redirect()
                ->route(
                    'stock-ins.index'
                )
                ->with(
                    'success',
                    'Stok masuk berhasil ditambahkan. Pengeluaran kas untuk pembelian stok sedang menunggu persetujuan Admin.'
                );
        }


        return redirect()
            ->route(
                'stock-ins.index'
            )
            ->with(
                'success',
                'Stok masuk berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        StockIn $stockIn
    ) {
        /*
        |--------------------------------------------------------------------------
        | CEK CASH TRANSACTION
        |--------------------------------------------------------------------------
        |
        | Jika pembayaran stok sudah disetujui,
        | transaksi tidak boleh diubah karena Kas sudah berubah.
        |
        |--------------------------------------------------------------------------
        */

        $cashTransaction =
            CashTransaction::query()
                ->where(
                    'source',
                    'stock_purchase'
                )
                ->where(
                    'reference_id',
                    $stockIn->id
                )
                ->latest('id')
                ->first();


        if (
            $cashTransaction
            &&
            $cashTransaction
                ->approval_status
            === 'approved'
        ) {
            return redirect()
                ->route(
                    'stock-ins.index'
                )
                ->with(
                    'error',
                    'Transaksi stok masuk tidak dapat diedit karena pembayaran pembelian stok sudah disetujui dan Kas Inventory sudah berkurang.'
                );
        }


        $products =
            Product::orderBy(
                'product_name'
            )
                ->get();


        $suppliers =
            Supplier::orderBy(
                'supplier_name'
            )
                ->get();


        return view(
            'stock-ins.edit',
            compact(
                'stockIn',
                'products',
                'suppliers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        StockIn $stockIn
    ) {
        $validatedData =
            $this->validateStockIn(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | CEK CASH SUDAH APPROVED
        |--------------------------------------------------------------------------
        */

        $existingCash =
            CashTransaction::query()
                ->where(
                    'source',
                    'stock_purchase'
                )
                ->where(
                    'reference_id',
                    $stockIn->id
                )
                ->latest('id')
                ->first();


        if (
            $existingCash
            &&
            $existingCash
                ->approval_status
            === 'approved'
        ) {
            return redirect()
                ->route(
                    'stock-ins.index'
                )
                ->with(
                    'error',
                    'Transaksi stok masuk tidak dapat diubah karena pembayaran stok sudah disetujui dan Kas Inventory sudah berkurang.'
                );
        }


        $result =
            DB::transaction(
                function () use (
                    $validatedData,
                    $stockIn
                ) {
                    /*
                    |--------------------------------------------------------------------------
                    | LOCK STOCK IN
                    |--------------------------------------------------------------------------
                    */

                    $lockedStockIn =
                        StockIn::query()
                            ->lockForUpdate()
                            ->findOrFail(
                                $stockIn->id
                            );


                    /*
                    |--------------------------------------------------------------------------
                    | OLD PRODUCT
                    |--------------------------------------------------------------------------
                    */

                    $oldProduct =
                        Product::where(
                            'id',
                            $lockedStockIn
                                ->product_id
                        )
                            ->lockForUpdate()
                            ->firstOrFail();


                    $oldProductId =
                        (int) $lockedStockIn
                            ->product_id;


                    $newProductId =
                        (int) $validatedData[
                            'product_id'
                        ];


                    $oldQuantity =
                        (int) $lockedStockIn
                            ->quantity;


                    $newQuantity =
                        (int) $validatedData[
                            'quantity'
                        ];


                    /*
                    |--------------------------------------------------------------------------
                    | PRODUCT SAMA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $oldProductId
                        === $newProductId
                    ) {
                        $newStock =
                            (int) $oldProduct
                                ->stock
                            +
                            (
                                $newQuantity
                                - $oldQuantity
                            );


                        if (
                            $newStock < 0
                        ) {
                            throw ValidationException::withMessages([
                                'quantity' =>
                                    'Jumlah tidak dapat dikurangi karena sebagian stok sudah terjual.',
                            ]);
                        }


                        $oldProduct->update([
                            'stock' =>
                                $newStock,
                        ]);


                        $newProduct =
                            $oldProduct;

                    } else {

                        /*
                        |--------------------------------------------------------------------------
                        | PRODUCT BERUBAH
                        |--------------------------------------------------------------------------
                        */

                        if (
                            (int) $oldProduct
                                ->stock
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


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE STOCK IN
                    |--------------------------------------------------------------------------
                    */

                    $lockedStockIn->update(
                        $validatedData
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE CASH REQUEST
                    |--------------------------------------------------------------------------
                    */

                    $cashTransaction =
                        CashTransaction::query()
                            ->where(
                                'source',
                                'stock_purchase'
                            )
                            ->where(
                                'reference_id',
                                $lockedStockIn->id
                            )
                            ->latest('id')
                            ->lockForUpdate()
                            ->first();


                    /*
                    |--------------------------------------------------------------------------
                    | PRODUK TV VOUCHER
                    |--------------------------------------------------------------------------
                    |
                    | Jika berubah menjadi TV Voucher, hapus cash request
                    | yang belum approved.
                    |
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $this->isTvVoucherProduct(
                            $newProduct
                        )
                    ) {
                        if (
                            $cashTransaction
                            &&
                            $cashTransaction
                                ->approval_status
                            !== 'approved'
                        ) {
                            $cashTransaction
                                ->delete();

                            $cashTransaction =
                                null;
                        }


                        return [
                            'stock_in' =>
                                $lockedStockIn,

                            'cash_transaction' =>
                                null,

                            'resend_approval' =>
                                false,
                        ];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | HITUNG TOTAL PEMBELIAN
                    |--------------------------------------------------------------------------
                    */

                    $totalPurchase =
                        $newQuantity
                        *
                        (float) $newProduct
                            ->purchase_price;


                    $supplierName =
                        '-';


                    if (
                        !empty(
                            $validatedData[
                                'supplier_id'
                            ]
                        )
                    ) {
                        $supplierName =
                            Supplier::where(
                                'id',
                                $validatedData[
                                    'supplier_id'
                                ]
                            )
                                ->value(
                                    'supplier_name'
                                )
                            ?? '-';
                    }


                    $updatedBy =
                        auth()->user()?->name
                        ?? 'Administrator';


                    $description =
                        'Pembelian stok '
                        . $newProduct->product_name
                        . ' sebanyak '
                        . $newQuantity
                        . ' unit dari supplier '
                        . $supplierName
                        . ' - Stok Masuk #'
                        . $lockedStockIn->id;


                    /*
                    |--------------------------------------------------------------------------
                    | CASH BELUM ADA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !$cashTransaction
                    ) {
                        $cashTransaction =
                            CashTransaction::create([
                                'type' =>
                                    'expense',

                                'source' =>
                                    'stock_purchase',

                                'category' =>
                                    'Belanja Stok',

                                'borrower_name' =>
                                    null,

                                'loan_reference' =>
                                    null,

                                'reference_id' =>
                                    $lockedStockIn->id,

                                'amount' =>
                                    $totalPurchase,

                                'description' =>
                                    $description,

                                'approval_status' =>
                                    'pending',

                                'approved_by' =>
                                    null,

                                'approved_at' =>
                                    null,

                                'rejection_reason' =>
                                    null,

                                'transaction_date' =>
                                    $validatedData[
                                        'transaction_date'
                                    ],

                                'created_by' =>
                                    $updatedBy,
                            ]);

                    } else {

                        /*
                        |--------------------------------------------------------------------------
                        | PENDING / DITOLAK → KEMBALI MENUNGGU
                        |--------------------------------------------------------------------------
                        */

                        $cashTransaction->update([
                            'amount' =>
                                $totalPurchase,

                            'description' =>
                                $description,

                            'approval_status' =>
                                'pending',

                            'approved_by' =>
                                null,

                            'approved_at' =>
                                null,

                            'rejection_reason' =>
                                null,

                            'transaction_date' =>
                                $validatedData[
                                    'transaction_date'
                                ],
                        ]);
                    }


                    return [
                        'stock_in' =>
                            $lockedStockIn,

                        'cash_transaction' =>
                            $cashTransaction,

                        'resend_approval' =>
                            true,
                    ];
                }
            );


        $stockInFresh =
            $result[
                'stock_in'
            ];


        $cashTransaction =
            $result[
                'cash_transaction'
            ];


        $resendApproval =
            (bool) $result[
                'resend_approval'
            ];


        /*
        |--------------------------------------------------------------------------
        | RESEND TELEGRAM APPROVAL
        |--------------------------------------------------------------------------
        */

        if (
            $cashTransaction
            &&
            $resendApproval
        ) {
            $stockInFresh->load([
                'product',
                'supplier',
            ]);


            $product =
                $stockInFresh->product;


            if (
                $product
                &&
                !$this->isTvVoucherProduct(
                    $product
                )
            ) {
                $stockTelegram =
                    app(
                        StockTelegramService::class
                    );


                $supplierName =
                    $stockInFresh
                        ->supplier
                        ?->supplier_name
                    ?? '-';


                $quantity =
                    (int) $stockInFresh
                        ->quantity;


                $purchasePrice =
                    (float) $product
                        ->purchase_price;


                $totalPurchase =
                    (float) $cashTransaction
                        ->amount;


                $purchasePriceFormatted =
                    number_format(
                        $purchasePrice,
                        2
                    );


                $totalPurchaseFormatted =
                    number_format(
                        $totalPurchase,
                        2
                    );


                $cashBalanceFormatted =
                    number_format(
                        CashTransaction::currentBalance(),
                        2
                    );


                $stockTelegram
                    ->sendCashApprovalRequest(
                        $cashTransaction->id,
                        "<b>🔄 PERMINTAAN CASH KELUAR DIPERBARUI</b>\n\n"
                        . "<b>Cash ID:</b> #{$cashTransaction->id}\n"
                        . "<b>Kategori:</b> Belanja Stok\n"
                        . "<b>Produk:</b> {$product->product_name}\n"
                        . "<b>Jumlah:</b> {$quantity} unit\n"
                        . "<b>Harga Beli:</b> \${$purchasePriceFormatted}\n"
                        . "<b>Total Pengeluaran:</b> \${$totalPurchaseFormatted}\n"
                        . "<b>Supplier:</b> {$supplierName}\n"
                        . "<b>Saldo Kas Saat Ini:</b> \${$cashBalanceFormatted}\n"
                        . "<b>Status:</b> ⏳ MENUNGGU\n\n"
                        . "Data stok masuk telah diperbarui. Silakan Setujui atau Tolak kembali."
                    );
            }
        }


        return redirect()
            ->route(
                'stock-ins.index'
            )
            ->with(
                'success',
                $cashTransaction
                    ? 'Transaksi stok masuk berhasil diperbarui. Pengeluaran kas kembali menunggu persetujuan Admin.'
                    : 'Transaksi stok masuk berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        StockIn $stockIn
    ) {
        /*
        |--------------------------------------------------------------------------
        | CEK CASH
        |--------------------------------------------------------------------------
        */

        $cashTransaction =
            CashTransaction::query()
                ->where(
                    'source',
                    'stock_purchase'
                )
                ->where(
                    'reference_id',
                    $stockIn->id
                )
                ->latest('id')
                ->first();


        /*
        |--------------------------------------------------------------------------
        | SUDAH APPROVED → JANGAN HAPUS
        |--------------------------------------------------------------------------
        |
        | Karena Kas Inventory sudah berkurang.
        |
        |--------------------------------------------------------------------------
        */

        if (
            $cashTransaction
            &&
            $cashTransaction
                ->approval_status
            === 'approved'
        ) {
            return redirect()
                ->route(
                    'stock-ins.index'
                )
                ->with(
                    'error',
                    'Transaksi stok masuk tidak dapat dihapus karena pembayaran pembelian stok sudah disetujui dan Kas Inventory sudah berkurang.'
                );
        }


        DB::transaction(
            function () use (
                $stockIn
            ) {
                $lockedStockIn =
                    StockIn::query()
                        ->lockForUpdate()
                        ->findOrFail(
                            $stockIn->id
                        );


                $product =
                    Product::where(
                        'id',
                        $lockedStockIn
                            ->product_id
                    )
                        ->lockForUpdate()
                        ->first();


                /*
                |--------------------------------------------------------------------------
                | CEK STOK SUDAH TERJUAL
                |--------------------------------------------------------------------------
                */

                if ($product) {
                    if (
                        (int) $product
                            ->stock
                        <
                        (int) $lockedStockIn
                            ->quantity
                    ) {
                        throw ValidationException::withMessages([
                            'stock_in' =>
                                'Transaksi tidak dapat dihapus karena sebagian barang sudah terjual.',
                        ]);
                    }


                    $product->decrement(
                        'stock',
                        (int) $lockedStockIn
                            ->quantity
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | HAPUS CASH REQUEST YANG BELUM APPROVED
                |--------------------------------------------------------------------------
                */

                CashTransaction::query()
                    ->where(
                        'source',
                        'stock_purchase'
                    )
                    ->where(
                        'reference_id',
                        $lockedStockIn->id
                    )
                    ->where(
                        'approval_status',
                        '!=',
                        'approved'
                    )
                    ->delete();


                /*
                |--------------------------------------------------------------------------
                | DELETE STOCK IN
                |--------------------------------------------------------------------------
                */

                $lockedStockIn->delete();
            }
        );


        return redirect()
            ->route(
                'stock-ins.index'
            )
            ->with(
                'success',
                'Transaksi stok masuk berhasil dihapus. Permintaan pengeluaran kas yang belum disetujui juga dibatalkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */

    private function applyIndexFilters(
        Builder $query,
        string $search,
        ?string $startDate,
        ?string $endDate
    ): void {
        if (
            $search !== ''
        ) {
            $query->where(
                function (
                    Builder $query
                ) use (
                    $search
                ) {
                    $query
                        ->whereHas(
                            'product',
                            function (
                                Builder $productQuery
                            ) use (
                                $search
                            ) {
                                $productQuery->where(
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
                            ) use (
                                $search
                            ) {
                                $supplierQuery->where(
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


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | CHECK TV VOUCHER
    |--------------------------------------------------------------------------
    */

    private function isTvVoucherProduct(
        ?Product $product
    ): bool {
        if (!$product) {
            return false;
        }


        $category =
            strtolower(
                trim(
                    (string) (
                        $product->category
                        ?? ''
                    )
                )
            );


        $productName =
            strtolower(
                trim(
                    (string) (
                        $product->product_name
                        ?? ''
                    )
                )
            );


        return
            str_contains(
                $category,
                'tv voucher'
            )
            ||
            str_contains(
                $productName,
                'tv voucher'
            );
    }
}