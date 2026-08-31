<?php

namespace App\Http\Controllers;

use App\Models\CashTransaction;
use App\Models\Customer;
use App\Models\Product;
use App\Models\StockOut;
use App\Services\StockTelegramService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockOutController extends Controller
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

        $search = trim(
            $validated['search'] ?? ''
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
            StockOut::query();

        $this->applyIndexFilters(
            $summaryQuery,
            $search,
            $startDate,
            $endDate
        );

        $totalTransactions =
            (clone $summaryQuery)
                ->count();

        $totalSales =
            (float) (clone $summaryQuery)
                ->sum('subtotal');

        $totalProfit =
            (float) (clone $summaryQuery)
                ->sum('total_profit');

        $totalCustomerPaid =
            (float) (clone $summaryQuery)
                ->sum(
                    'customer_paid_amount'
                );

        $totalCustomerBalance =
            (float) (clone $summaryQuery)
                ->sum(
                    'customer_balance'
                );

        $totalStaffReceived =
            (float) (clone $summaryQuery)
                ->sum(
                    'staff_received_amount'
                );

        $totalDeposited =
            (float) (clone $summaryQuery)
                ->sum(
                    'staff_deposited_amount'
                );

        $totalNotDeposited =
            (float) (clone $summaryQuery)
                ->sum(
                    'staff_balance'
                );


        /*
        |--------------------------------------------------------------------------
        | TABLE
        |--------------------------------------------------------------------------
        */

        $stockOutQuery =
            StockOut::query()
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

        $stockOuts =
            $stockOutQuery
                ->orderByDesc(
                    'transaction_date'
                )
                ->orderByDesc('id')
                ->paginate(10);

        $stockOuts->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | CHART
        |--------------------------------------------------------------------------
        */

        $chartQuery =
            StockOut::query()
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

        $totalStockOut =
            $chartValues->sum();


        return view(
            'stock-outs.index',
            compact(
                'stockOuts',
                'chartLabels',
                'chartValues',
                'totalStockOut',
                'totalTransactions',
                'totalSales',
                'totalProfit',
                'totalCustomerPaid',
                'totalCustomerBalance',
                'totalStaffReceived',
                'totalDeposited',
                'totalNotDeposited',
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
            Product::where(
                'stock',
                '>',
                0
            )
                ->orderBy(
                    'product_name'
                )
                ->get();

        $customers =
            Customer::orderBy(
                'customer_name'
            )
                ->get();

        return view(
            'stock-outs.create',
            compact(
                'products',
                'customers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated =
            $this->validateStockOut(
                $request
            );

        $stockOut =
            DB::transaction(
                function () use (
                    $validated
                ) {
                    $product =
                        Product::where(
                            'id',
                            $validated[
                                'product_id'
                            ]
                        )
                            ->lockForUpdate()
                            ->firstOrFail();

                    $quantity =
                        (int) $validated[
                            'quantity'
                        ];

                    if (
                        $quantity
                        > (int) $product->stock
                    ) {
                        throw ValidationException::withMessages([
                            'quantity' =>
                                'Jumlah barang keluar melebihi stok tersedia. '
                                . 'Stok saat ini: '
                                . $product->stock
                                . ' unit.',
                        ]);
                    }

                    $hargaBeli =
                        (float) $product
                            ->purchase_price;

                    $hargaJual =
                        (float) $product
                            ->selling_price;

                    $subtotal =
                        $hargaJual
                        * $quantity;

                    $totalProfit =
                        (
                            $hargaJual
                            - $hargaBeli
                        )
                        * $quantity;

                    $stockOut =
                        StockOut::create([
                            'product_id' =>
                                $product->id,

                            'customer_id' =>
                                $validated[
                                    'customer_id'
                                ]
                                ?? null,

                            'quantity' =>
                                $quantity,

                            'unit_purchase_price' =>
                                $hargaBeli,

                            'unit_selling_price' =>
                                $hargaJual,

                            'subtotal' =>
                                $subtotal,

                            'total_profit' =>
                                $totalProfit,

                            'transaction_date' =>
                                $validated[
                                    'transaction_date'
                                ],

                            'notes' =>
                                $validated[
                                    'notes'
                                ]
                                ?? null,
                        ]);

                    $stockOut->sold_by =
                        auth()->user()?->name
                        ?? 'Tidak diketahui';

                    $stockOut
                        ->customer_paid_amount =
                        0;

                    $stockOut
                        ->customer_balance =
                        $subtotal;

                    $stockOut
                        ->customer_payment_status =
                        'unpaid';

                    $stockOut
                        ->customer_paid_at =
                        null;

                    $stockOut
                        ->staff_received_amount =
                        0;

                    $stockOut
                        ->staff_deposited_amount =
                        0;

                    $stockOut
                        ->staff_balance =
                        0;

                    $stockOut
                        ->staff_deposit_status =
                        'unpaid';

                    $stockOut
                        ->staff_deposited_at =
                        null;

                    $stockOut
                        ->deposit_verified_by =
                        null;

                    $stockOut->save();

                    $product->decrement(
                        'stock',
                        $quantity
                    );

                    return $stockOut;
                }
            );


        /*
        |--------------------------------------------------------------------------
        | TELEGRAM BARANG TERJUAL
        |--------------------------------------------------------------------------
        */

        $stockOut->load([
            'product',
            'customer',
        ]);

        $productFresh =
            $stockOut->product?->fresh();

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

            $productName =
                $productFresh->product_name
                ?: 'Produk tidak ditemukan';

            $category =
                $productFresh->category
                ?: '-';

            $customerName =
                $stockOut->customer?->customer_name
                ?? '-';

            $soldBy =
                $stockOut->sold_by
                ?: '-';

            $quantity =
                (int) $stockOut->quantity;

            $unitPrice =
                number_format(
                    (float) $stockOut
                        ->unit_selling_price,
                    2
                );

            $subtotal =
                number_format(
                    (float) $stockOut
                        ->subtotal,
                    2
                );

            $profit =
                number_format(
                    (float) $stockOut
                        ->total_profit,
                    2
                );

            $transactionDate =
                $stockOut->transaction_date
                    ? $stockOut
                        ->transaction_date
                        ->format(
                            'd-m-Y'
                        )
                    : '-';

            $notes =
                $stockOut->notes
                ?: '-';

            $stockTelegram->send(
                "<b>📤 BARANG TERJUAL</b>\n\n"
                . "<b>Produk:</b> {$productName}\n"
                . "<b>Kategori:</b> {$category}\n"
                . "<b>Jumlah Keluar:</b> -{$quantity} unit\n"
                . "<b>Harga Satuan:</b> \${$unitPrice}\n"
                . "<b>Total Penjualan:</b> \${$subtotal}\n"
                . "<b>Profit:</b> \${$profit}\n"
                . "<b>Customer:</b> {$customerName}\n"
                . "<b>Petugas:</b> {$soldBy}\n"
                . "<b>Tanggal:</b> {$transactionDate}\n"
                . "<b>Stok Sekarang:</b> "
                . (int) $productFresh->stock
                . " unit\n"
                . "<b>Catatan:</b> {$notes}\n\n"
                . "✅ Transaksi penjualan berhasil dicatat."
            );


            /*
            |--------------------------------------------------------------------------
            | LOW STOCK
            |--------------------------------------------------------------------------
            */

            if (
                (int) $productFresh->stock
                <= 0
            ) {
                $stockTelegram->send(
                    "<b>🔴 STOK HABIS</b>\n\n"
                    . "<b>Produk:</b> {$productName}\n"
                    . "<b>Kategori:</b> {$category}\n"
                    . "<b>Stok Sekarang:</b> 0 unit\n\n"
                    . "⚠️ Segera lakukan penambahan stok."
                );

            } elseif (
                (int) $productFresh->stock
                <= 3
            ) {
                $stockTelegram->send(
                    "<b>⚠️ STOK MENIPIS</b>\n\n"
                    . "<b>Produk:</b> {$productName}\n"
                    . "<b>Kategori:</b> {$category}\n"
                    . "<b>Sisa Stok:</b> "
                    . (int) $productFresh->stock
                    . " unit\n\n"
                    . "⚠️ Pertimbangkan untuk menambah stok."
                );
            }
        }

        return redirect()
            ->route(
                'stock-outs.index'
            )
            ->with(
                'success',
                'Transaksi penjualan berhasil disimpan. Pembayaran customer menunggu verifikasi.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        StockOut $stockOut
    ) {
        $products =
            Product::where(
                'stock',
                '>',
                0
            )
                ->orWhere(
                    'id',
                    $stockOut
                        ->product_id
                )
                ->orderBy(
                    'product_name'
                )
                ->get();

        $customers =
            Customer::orderBy(
                'customer_name'
            )
                ->get();

        return view(
            'stock-outs.edit',
            compact(
                'stockOut',
                'products',
                'customers'
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
        StockOut $stockOut
    ) {
        $validated =
            $this->validateStockOut(
                $request
            );

        DB::transaction(
            function () use (
                $validated,
                $stockOut
            ) {
                $oldProduct =
                    Product::where(
                        'id',
                        $stockOut
                            ->product_id
                    )
                        ->lockForUpdate()
                        ->firstOrFail();

                $newProductId =
                    (int) $validated[
                        'product_id'
                    ];

                $newQuantity =
                    (int) $validated[
                        'quantity'
                    ];

                $oldQuantity =
                    (int) $stockOut
                        ->quantity;


                /*
                |--------------------------------------------------------------------------
                | PRODUCT SAMA
                |--------------------------------------------------------------------------
                */

                if (
                    $newProductId
                    ===
                    (int) $oldProduct->id
                ) {
                    $availableStock =
                        (int) $oldProduct
                            ->stock
                        +
                        $oldQuantity;

                    if (
                        $newQuantity
                        > $availableStock
                    ) {
                        throw ValidationException::withMessages([
                            'quantity' =>
                                'Jumlah barang melebihi stok tersedia. '
                                . 'Stok yang dapat digunakan: '
                                . $availableStock
                                . ' unit.',
                        ]);
                    }

                    $newProduct =
                        $oldProduct;

                    $newStock =
                        $availableStock
                        - $newQuantity;

                    $newProduct->update([
                        'stock' =>
                            $newStock,
                    ]);

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | PRODUCT BERUBAH
                    |--------------------------------------------------------------------------
                    */

                    $oldProduct
                        ->increment(
                            'stock',
                            $oldQuantity
                        );

                    $newProduct =
                        Product::where(
                            'id',
                            $newProductId
                        )
                            ->lockForUpdate()
                            ->firstOrFail();

                    if (
                        $newQuantity
                        > $newProduct->stock
                    ) {
                        throw ValidationException::withMessages([
                            'quantity' =>
                                'Jumlah barang melebihi stok produk baru. '
                                . 'Stok tersedia: '
                                . $newProduct->stock
                                . ' unit.',
                        ]);
                    }

                    $newProduct
                        ->decrement(
                            'stock',
                            $newQuantity
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | RECALCULATE
                |--------------------------------------------------------------------------
                */

                $hargaBeli =
                    (float) $newProduct
                        ->purchase_price;

                $hargaJual =
                    (float) $newProduct
                        ->selling_price;

                $newSubtotal =
                    $hargaJual
                    * $newQuantity;

                $newProfit =
                    (
                        $hargaJual
                        - $hargaBeli
                    )
                    * $newQuantity;


                $customerPaid =
                    min(
                        (float) $stockOut
                            ->customer_paid_amount,
                        $newSubtotal
                    );

                $customerBalance =
                    max(
                        $newSubtotal
                        - $customerPaid,
                        0
                    );


                /*
                |--------------------------------------------------------------------------
                | CUSTOMER STATUS
                |--------------------------------------------------------------------------
                */

                if (
                    $customerPaid <= 0
                ) {
                    $customerStatus =
                        'unpaid';

                    $customerPaidAt =
                        null;

                } elseif (
                    $customerBalance <= 0
                ) {
                    $customerStatus =
                        'paid';

                    $customerPaidAt =
                        $stockOut
                            ->customer_paid_at
                        ?? now();

                } else {
                    $customerStatus =
                        'partial';

                    $customerPaidAt =
                        null;
                }


                /*
                |--------------------------------------------------------------------------
                | STAFF MONEY
                |--------------------------------------------------------------------------
                */

                $staffReceived =
                    $customerPaid;

                $staffDeposited =
                    min(
                        (float) $stockOut
                            ->staff_deposited_amount,
                        $staffReceived
                    );

                $staffBalance =
                    max(
                        $staffReceived
                        - $staffDeposited,
                        0
                    );


                if (
                    $staffReceived <= 0
                ) {
                    $staffDepositStatus =
                        'unpaid';

                    $staffDepositedAt =
                        null;

                    $depositVerifiedBy =
                        null;

                } elseif (
                    $staffBalance <= 0
                ) {
                    $staffDepositStatus =
                        'paid';

                    $staffDepositedAt =
                        $stockOut
                            ->staff_deposited_at
                        ?? now();

                    $depositVerifiedBy =
                        $stockOut
                            ->deposit_verified_by;

                } elseif (
                    $staffDeposited > 0
                ) {
                    $staffDepositStatus =
                        'partial';

                    $staffDepositedAt =
                        $stockOut
                            ->staff_deposited_at;

                    $depositVerifiedBy =
                        $stockOut
                            ->deposit_verified_by;

                } else {
                    $staffDepositStatus =
                        'unpaid';

                    $staffDepositedAt =
                        null;

                    $depositVerifiedBy =
                        null;
                }


                /*
                |--------------------------------------------------------------------------
                | SAVE
                |--------------------------------------------------------------------------
                */

                $stockOut->product_id =
                    $newProduct->id;

                $stockOut->customer_id =
                    $validated[
                        'customer_id'
                    ]
                    ?? null;

                $stockOut->quantity =
                    $newQuantity;

                $stockOut
                    ->unit_purchase_price =
                    $hargaBeli;

                $stockOut
                    ->unit_selling_price =
                    $hargaJual;

                $stockOut->subtotal =
                    $newSubtotal;

                $stockOut->total_profit =
                    $newProfit;

                $stockOut
                    ->transaction_date =
                    $validated[
                        'transaction_date'
                    ];

                $stockOut->notes =
                    $validated[
                        'notes'
                    ]
                    ?? null;

                $stockOut
                    ->customer_paid_amount =
                    $customerPaid;

                $stockOut
                    ->customer_balance =
                    $customerBalance;

                $stockOut
                    ->customer_payment_status =
                    $customerStatus;

                $stockOut
                    ->customer_paid_at =
                    $customerPaidAt;

                $stockOut
                    ->staff_received_amount =
                    $staffReceived;

                $stockOut
                    ->staff_deposited_amount =
                    $staffDeposited;

                $stockOut
                    ->staff_balance =
                    $staffBalance;

                $stockOut
                    ->staff_deposit_status =
                    $staffDepositStatus;

                $stockOut
                    ->staff_deposited_at =
                    $staffDepositedAt;

                $stockOut
                    ->deposit_verified_by =
                    $depositVerifiedBy;

                $stockOut->save();
            }
        );


        return redirect()
            ->route(
                'stock-outs.index'
            )
            ->with(
                'success',
                'Transaksi stok keluar berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFY CUSTOMER PAYMENT
    |--------------------------------------------------------------------------
    |
    | PENTING:
    |
    | Customer bayar kepada petugas.
    |
    | Uang BELUM MASUK Kas Inventory.
    |
    | Uang masih dianggap berada di tangan petugas sampai:
    |
    | Admin melakukan Konfirmasi Setoran.
    |
    |--------------------------------------------------------------------------
    */

    public function verifyCustomerPayment(
        Request $request,
        StockOut $stockOut
    ) {
        $validated =
            $request->validate([
                'payment_amount' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],
            ], [
                'payment_amount.required' =>
                    'Jumlah pembayaran wajib diisi.',

                'payment_amount.numeric' =>
                    'Jumlah pembayaran harus berupa angka.',

                'payment_amount.min' =>
                    'Jumlah pembayaran harus lebih dari 0.',
            ]);


        $paymentAmount =
            (float) $validated[
                'payment_amount'
            ];


        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION + LOCK
        |--------------------------------------------------------------------------
        */

        $result =
            DB::transaction(
                function () use (
                    $stockOut,
                    $paymentAmount
                ) {
                    $lockedStockOut =
                        StockOut::query()
                            ->lockForUpdate()
                            ->findOrFail(
                                $stockOut->id
                            );


                    $total =
                        (float) $lockedStockOut
                            ->subtotal;


                    $alreadyPaid =
                        (float) $lockedStockOut
                            ->customer_paid_amount;


                    $remaining =
                        max(
                            $total
                            - $alreadyPaid,
                            0
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | SUDAH LUNAS
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $remaining <= 0
                    ) {
                        throw ValidationException::withMessages([
                            'payment_amount' =>
                                'Pembayaran customer sudah lunas.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | JANGAN BAYAR LEBIH
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $paymentAmount
                        > $remaining
                    ) {
                        throw ValidationException::withMessages([
                            'payment_amount' =>
                                'Jumlah pembayaran melebihi sisa tagihan customer sebesar $'
                                . number_format(
                                    $remaining,
                                    2
                                )
                                . '.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | TOTAL CUSTOMER BAYAR
                    |--------------------------------------------------------------------------
                    */

                    $newPaid =
                        $alreadyPaid
                        + $paymentAmount;


                    $newCustomerBalance =
                        max(
                            $total
                            - $newPaid,
                            0
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | CUSTOMER STATUS
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $newCustomerBalance
                        <= 0
                    ) {
                        $customerStatus =
                            'paid';

                        $customerPaidAt =
                            now();

                    } else {
                        $customerStatus =
                            'partial';

                        $customerPaidAt =
                            null;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | UANG DI TANGAN PETUGAS
                    |--------------------------------------------------------------------------
                    |
                    | staff_received_amount = total customer yang sudah bayar
                    |
                    | staff_deposited_amount = yang sudah disetor & diverifikasi
                    |
                    | staff_balance = uang yang masih di tangan petugas
                    |
                    */

                    $staffReceived =
                        $newPaid;


                    $staffDeposited =
                        min(
                            (float) $lockedStockOut
                                ->staff_deposited_amount,
                            $staffReceived
                        );


                    $staffBalance =
                        max(
                            $staffReceived
                            - $staffDeposited,
                            0
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | STATUS SETORAN
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $staffBalance <= 0
                        &&
                        $staffReceived > 0
                    ) {
                        $staffDepositStatus =
                            'paid';

                    } elseif (
                        $staffDeposited > 0
                    ) {
                        $staffDepositStatus =
                            'partial';

                    } else {
                        $staffDepositStatus =
                            'unpaid';
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | SAVE
                    |--------------------------------------------------------------------------
                    */

                    $lockedStockOut
                        ->customer_paid_amount =
                        $newPaid;


                    $lockedStockOut
                        ->customer_balance =
                        $newCustomerBalance;


                    $lockedStockOut
                        ->customer_payment_status =
                        $customerStatus;


                    $lockedStockOut
                        ->customer_paid_at =
                        $customerPaidAt;


                    $lockedStockOut
                        ->staff_received_amount =
                        $staffReceived;


                    $lockedStockOut
                        ->staff_deposited_amount =
                        $staffDeposited;


                    $lockedStockOut
                        ->staff_balance =
                        $staffBalance;


                    $lockedStockOut
                        ->staff_deposit_status =
                        $staffDepositStatus;


                    $lockedStockOut->save();


                    return [
                        'stock_out' =>
                            $lockedStockOut,

                        'new_paid' =>
                            $newPaid,

                        'customer_balance' =>
                            $newCustomerBalance,

                        'staff_balance' =>
                            $staffBalance,
                    ];
                }
            );


        $stockOutFresh =
            $result[
                'stock_out'
            ];


        $newPaid =
            (float) $result[
                'new_paid'
            ];


        $newCustomerBalance =
            (float) $result[
                'customer_balance'
            ];


        $staffBalance =
            (float) $result[
                'staff_balance'
            ];


        /*
        |--------------------------------------------------------------------------
        | TELEGRAM
        |--------------------------------------------------------------------------
        */

        $stockOutFresh->load([
            'product',
            'customer',
        ]);


        $product =
            $stockOutFresh->product;


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


            $productName =
                $product->product_name
                ?: 'Produk tidak ditemukan';


            $customerName =
                $stockOutFresh
                    ->customer
                    ?->customer_name
                ?? '-';


            $paymentFormatted =
                number_format(
                    $paymentAmount,
                    2
                );


            $totalPaidFormatted =
                number_format(
                    $newPaid,
                    2
                );


            $remainingFormatted =
                number_format(
                    $newCustomerBalance,
                    2
                );


            $staffBalanceFormatted =
                number_format(
                    $staffBalance,
                    2
                );


            $statusText =
                $newCustomerBalance <= 0
                    ? 'LUNAS'
                    : 'BAYAR SEBAGIAN';


            $verifiedBy =
                auth()->user()?->name
                ?? 'Tidak diketahui';


            $stockTelegram->send(
                "<b>💳 VERIFIKASI PEMBAYARAN CUSTOMER</b>\n\n"
                . "<b>Produk:</b> {$productName}\n"
                . "<b>Customer:</b> {$customerName}\n"
                . "<b>Pembayaran Baru:</b> \${$paymentFormatted}\n"
                . "<b>Total Sudah Bayar:</b> \${$totalPaidFormatted}\n"
                . "<b>Sisa Tagihan:</b> \${$remainingFormatted}\n"
                . "<b>Status Customer:</b> {$statusText}\n"
                . "<b>Uang di Tangan Petugas:</b> \${$staffBalanceFormatted}\n"
                . "<b>Diverifikasi Oleh:</b> {$verifiedBy}\n\n"
                . "⏳ Uang belum masuk Kas Inventory.\n"
                . "Kas baru bertambah setelah setoran petugas dikonfirmasi Admin."
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        if (
            $newCustomerBalance
            <= 0
        ) {
            return redirect()
                ->route(
                    'stock-outs.index'
                )
                ->with(
                    'success',
                    'Pembayaran customer berhasil diverifikasi dan customer sudah lunas. Uang belum masuk Kas Inventory karena masih menunggu setoran petugas.'
                );
        }


        return redirect()
            ->route(
                'stock-outs.index'
            )
            ->with(
                'success',
                'Pembayaran customer berhasil diverifikasi sebagian. Uang belum masuk Kas Inventory. Sisa tagihan $'
                . number_format(
                    $newCustomerBalance,
                    2
                )
                . '.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CONFIRM DEPOSIT
    |--------------------------------------------------------------------------
    |
    | INI TITIK UANG MASUK KE KAS INVENTORY.
    |
    | Alur:
    |
    | Customer bayar
    |       ↓
    | Petugas memegang uang
    |       ↓
    | Admin konfirmasi setoran
    |       ↓
    | CashTransaction dibuat
    |       ↓
    | Kas Inventory bertambah
    |
    |--------------------------------------------------------------------------
    */

    public function confirmDeposit(
        StockOut $stockOut
    ) {
        $result =
            DB::transaction(
                function () use (
                    $stockOut
                ) {
                    /*
                    |--------------------------------------------------------------------------
                    | LOCK STOCK OUT
                    |--------------------------------------------------------------------------
                    */

                    $lockedStockOut =
                        StockOut::query()
                            ->lockForUpdate()
                            ->findOrFail(
                                $stockOut->id
                            );


                    $staffReceived =
                        (float) $lockedStockOut
                            ->staff_received_amount;


                    /*
                    |--------------------------------------------------------------------------
                    | BELUM ADA UANG
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $staffReceived <= 0
                    ) {
                        throw ValidationException::withMessages([
                            'deposit' =>
                                'Belum ada uang customer yang diterima petugas.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | BERAPA UANG YANG SUDAH MASUK KAS
                    |--------------------------------------------------------------------------
                    |
                    | Kita hitung berdasarkan CashTransaction.
                    |
                    | Ini penting untuk mencegah double count.
                    |
                    */

                    $alreadyRecordedCash =
                        (float) CashTransaction::query()
                            ->where(
                                'source',
                                'sale_deposit'
                            )
                            ->where(
                                'reference_id',
                                $lockedStockOut->id
                            )
                            ->where(
                                'approval_status',
                                'approved'
                            )
                            ->sum(
                                'amount'
                            );


                    /*
                    |--------------------------------------------------------------------------
                    | UANG BARU YANG BELUM MASUK KAS
                    |--------------------------------------------------------------------------
                    */

                    $cashToAdd =
                        max(
                            $staffReceived
                            - $alreadyRecordedCash,
                            0
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | SUDAH SEMUA MASUK
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $cashToAdd <= 0
                    ) {
                        throw ValidationException::withMessages([
                            'deposit' =>
                                'Seluruh setoran transaksi ini sudah dikonfirmasi dan sudah masuk Kas Inventory.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | PRODUCT
                    |--------------------------------------------------------------------------
                    */

                    $product =
                        Product::query()
                            ->find(
                                $lockedStockOut
                                    ->product_id
                            );


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE SETORAN STOCK OUT
                    |--------------------------------------------------------------------------
                    */

                    $lockedStockOut
                        ->staff_deposited_amount =
                        $staffReceived;


                    $lockedStockOut
                        ->staff_balance =
                        0;


                    $lockedStockOut
                        ->staff_deposit_status =
                        'paid';


                    $lockedStockOut
                        ->staff_deposited_at =
                        now();


                    $verifiedBy =
                        auth()->user()?->name
                        ?? 'Administrator';


                    $lockedStockOut
                        ->deposit_verified_by =
                        $verifiedBy;


                    $lockedStockOut->save();


                    /*
                    |--------------------------------------------------------------------------
                    | CASH INVENTORY
                    |--------------------------------------------------------------------------
                    |
                    | TV Voucher tidak masuk ke Kas Inventory private.
                    |
                    */

                    $cashTransaction =
                        null;


                    if (
                        $product
                        &&
                        !$this->isTvVoucherProduct(
                            $product
                        )
                    ) {
                        /*
                        |--------------------------------------------------------------------------
                        | CUSTOMER
                        |--------------------------------------------------------------------------
                        */

                        $customer =
                            $lockedStockOut
                                ->customer()
                                ->first();


                        $customerName =
                            $customer?->customer_name
                            ?? '-';


                        $productName =
                            $product->product_name
                            ?? 'Produk';


                        /*
                        |--------------------------------------------------------------------------
                        | CREATE CASH
                        |--------------------------------------------------------------------------
                        |
                        | Langsung approved karena tindakan confirmDeposit
                        | adalah verifikasi Admin bahwa uang fisik sudah diterima.
                        |
                        */

                        $cashTransaction =
                            CashTransaction::create([
                                'type' =>
                                    'income',

                                'source' =>
                                    'sale_deposit',

                                'category' =>
                                    'Penjualan Barang',

                                'borrower_name' =>
                                    null,

                                'loan_reference' =>
                                    null,

                                'reference_id' =>
                                    $lockedStockOut->id,

                                'amount' =>
                                    $cashToAdd,

                                'description' =>
                                    'Setoran penjualan '
                                    . $productName
                                    . ' - Customer '
                                    . $customerName
                                    . ' - Transaksi #'
                                    . $lockedStockOut->id,

                                'approval_status' =>
                                    'approved',

                                'approved_by' =>
                                    $verifiedBy,

                                'approved_at' =>
                                    now(),

                                'rejection_reason' =>
                                    null,

                                /*
                                |--------------------------------------------------------------------------
                                | TANGGAL CASH
                                |--------------------------------------------------------------------------
                                |
                                | Gunakan tanggal aktual saat uang fisik masuk Kas.
                                |
                                */

                                'transaction_date' =>
                                    now()
                                        ->toDateString(),

                                'created_by' =>
                                    $verifiedBy,
                            ]);
                    }


                    return [
                        'stock_out' =>
                            $lockedStockOut,

                        'product' =>
                            $product,

                        'cash_transaction' =>
                            $cashTransaction,

                        'cash_to_add' =>
                            $cashToAdd,

                        'already_recorded_cash' =>
                            $alreadyRecordedCash,
                    ];
                }
            );


        /*
        |--------------------------------------------------------------------------
        | RESULT
        |--------------------------------------------------------------------------
        */

        $stockOutFresh =
            $result[
                'stock_out'
            ];


        $cashTransaction =
            $result[
                'cash_transaction'
            ];


        $cashToAdd =
            (float) $result[
                'cash_to_add'
            ];


        /*
        |--------------------------------------------------------------------------
        | LOAD RELATION
        |--------------------------------------------------------------------------
        */

        $stockOutFresh->load([
            'product',
            'customer',
        ]);


        $product =
            $stockOutFresh->product;


        /*
        |--------------------------------------------------------------------------
        | TELEGRAM
        |--------------------------------------------------------------------------
        */

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


            $productName =
                $product->product_name
                ?: 'Produk tidak ditemukan';


            $customerName =
                $stockOutFresh
                    ->customer
                    ?->customer_name
                ?? '-';


            $soldBy =
                $stockOutFresh->sold_by
                ?: '-';


            $verifiedBy =
                $stockOutFresh
                    ->deposit_verified_by
                ?: (
                    auth()->user()?->name
                    ?? 'Administrator'
                );


            $received =
                number_format(
                    (float) $stockOutFresh
                        ->staff_received_amount,
                    2
                );


            $deposited =
                number_format(
                    (float) $stockOutFresh
                        ->staff_deposited_amount,
                    2
                );


            $balance =
                number_format(
                    (float) $stockOutFresh
                        ->staff_balance,
                    2
                );


            $cashAddedFormatted =
                number_format(
                    $cashToAdd,
                    2
                );


            $cashBalance =
                CashTransaction::currentBalance();


            $cashBalanceFormatted =
                number_format(
                    $cashBalance,
                    2
                );


            $depositedAt =
                $stockOutFresh
                    ->staff_deposited_at
                    ? $stockOutFresh
                        ->staff_deposited_at
                        ->format(
                            'd-m-Y H:i'
                        )
                    : now()->format(
                        'd-m-Y H:i'
                    );


            /*
            |--------------------------------------------------------------------------
            | TELEGRAM SETORAN
            |--------------------------------------------------------------------------
            */

            $stockTelegram->send(
                "<b>💵 SETORAN PETUGAS DIKONFIRMASI</b>\n\n"
                . "<b>Produk:</b> {$productName}\n"
                . "<b>Customer:</b> {$customerName}\n"
                . "<b>Petugas Penjualan:</b> {$soldBy}\n"
                . "<b>Uang Diterima Petugas:</b> \${$received}\n"
                . "<b>Sudah Disetor:</b> \${$deposited}\n"
                . "<b>Belum Disetor:</b> \${$balance}\n"
                . "<b>Cash Baru Masuk:</b> +\${$cashAddedFormatted}\n"
                . "<b>Status:</b> SUDAH SETOR\n"
                . "<b>Dikonfirmasi Oleh:</b> {$verifiedBy}\n"
                . "<b>Waktu Setoran:</b> {$depositedAt}\n\n"
                . "✅ Uang fisik sudah diterima dan diverifikasi Admin."
            );


            /*
            |--------------------------------------------------------------------------
            | TELEGRAM CASH INVENTORY
            |--------------------------------------------------------------------------
            */

            if ($cashTransaction) {
                $cashId =
                    $cashTransaction->id;


                $stockTelegram->send(
                    "<b>💰 CASH INVENTORY BERTAMBAH</b>\n\n"
                    . "<b>Cash ID:</b> #{$cashId}\n"
                    . "<b>Sumber:</b> Setoran Penjualan\n"
                    . "<b>Produk:</b> {$productName}\n"
                    . "<b>Customer:</b> {$customerName}\n"
                    . "<b>Cash Masuk:</b> +\${$cashAddedFormatted}\n"
                    . "<b>Saldo Kas Sekarang:</b> \${$cashBalanceFormatted}\n"
                    . "<b>Diverifikasi Oleh:</b> {$verifiedBy}\n\n"
                    . "✅ Setoran otomatis tercatat di Kas Inventory."
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'stock-outs.index'
            )
            ->with(
                'success',
                'Setoran dari '
                . (
                    $stockOutFresh->sold_by
                    ?: 'petugas'
                )
                . ' berhasil dikonfirmasi. Cash sebesar $'
                . number_format(
                    $cashToAdd,
                    2
                )
                . ' otomatis masuk ke Kas Inventory.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        StockOut $stockOut
    ) {
        if (
            (float) $stockOut
                ->customer_paid_amount
            > 0
            ||
            (float) $stockOut
                ->staff_deposited_amount
            > 0
        ) {
            return redirect()
                ->route(
                    'stock-outs.index'
                )
                ->with(
                    'error',
                    'Transaksi yang sudah memiliki pembayaran atau setoran tidak dapat dihapus.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | TAMBAHAN PROTEKSI CASH
        |--------------------------------------------------------------------------
        */

        $hasCashTransaction =
            CashTransaction::query()
                ->where(
                    'source',
                    'sale_deposit'
                )
                ->where(
                    'reference_id',
                    $stockOut->id
                )
                ->exists();


        if ($hasCashTransaction) {
            return redirect()
                ->route(
                    'stock-outs.index'
                )
                ->with(
                    'error',
                    'Transaksi tidak dapat dihapus karena sudah memiliki pencatatan Kas Inventory.'
                );
        }


        DB::transaction(
            function () use (
                $stockOut
            ) {
                $product =
                    Product::where(
                        'id',
                        $stockOut
                            ->product_id
                    )
                        ->lockForUpdate()
                        ->first();


                if ($product) {
                    $product->increment(
                        'stock',
                        $stockOut
                            ->quantity
                    );
                }


                $stockOut->delete();
            }
        );


        return redirect()
            ->route(
                'stock-outs.index'
            )
            ->with(
                'success',
                'Transaksi stok keluar berhasil dihapus dan stok barang telah dikembalikan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | APPLY FILTER
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
                            'customer',
                            function (
                                Builder $customerQuery
                            ) use (
                                $search
                            ) {
                                $customerQuery
                                    ->where(
                                        'customer_name',
                                        'like',
                                        '%'
                                        . $search
                                        . '%'
                                    );
                            }
                        )
                        ->orWhere(
                            'sold_by',
                            'like',
                            '%'
                            . $search
                            . '%'
                        )
                        ->orWhere(
                            'deposit_verified_by',
                            'like',
                            '%'
                            . $search
                            . '%'
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
    | VALIDATE STOCK OUT
    |--------------------------------------------------------------------------
    */

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