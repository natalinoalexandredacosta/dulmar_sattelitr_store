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
    /**
     * Daftar transaksi Stok Keluar.
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
        | RINGKASAN TRANSAKSI
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


        /*
        |--------------------------------------------------------------------------
        | RINGKASAN PEMBAYARAN CUSTOMER
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | RINGKASAN SETORAN PETUGAS
        |--------------------------------------------------------------------------
        */

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
        | DAFTAR TRANSAKSI
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


        /** @var \Illuminate\Pagination\LengthAwarePaginator $stockOuts */
        $stockOuts =
            $stockOutQuery
                ->orderByDesc(
                    'transaction_date'
                )
                ->orderByDesc('id')
                ->paginate(10);

        $stockOuts
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | DATA GRAFIK
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
                        return
                            (int) $quantity;
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


    /**
     * Form tambah transaksi.
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


    /**
     * Simpan transaksi penjualan.
     */
    public function store(Request $request)
    {
        $validated =
            $this->validateStockOut(
                $request
            );


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
                    > $product->stock
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


                /*
                |--------------------------------------------------------------------------
                | SIMPAN TRANSAKSI DASAR
                |--------------------------------------------------------------------------
                */

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


                /*
                |--------------------------------------------------------------------------
                | DATA PETUGAS & PEMBAYARAN
                |--------------------------------------------------------------------------
                |
                | Transaksi baru dianggap customer belum diverifikasi membayar.
                |
                */

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


                /*
                |--------------------------------------------------------------------------
                | SETORAN PETUGAS
                |--------------------------------------------------------------------------
                */

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


                /*
                |--------------------------------------------------------------------------
                | KURANGI STOK
                |--------------------------------------------------------------------------
                */

                $product->decrement(
                    'stock',
                    $quantity
                );
            }
        );


        return redirect()
            ->route(
                'stock-outs.index'
            )
            ->with(
                'success',
                'Transaksi penjualan berhasil disimpan. Pembayaran customer menunggu verifikasi.'
            );
    }


    /**
     * Form edit transaksi.
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


    /**
     * Update transaksi.
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
                | PENYESUAIAN STOK
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
                | HITUNG ULANG TRANSAKSI
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


                /*
                |--------------------------------------------------------------------------
                | PEMBAYARAN CUSTOMER YANG SUDAH ADA
                |--------------------------------------------------------------------------
                */

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
                | UANG PETUGAS
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
                | UPDATE TRANSAKSI
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


                /*
                |--------------------------------------------------------------------------
                | UPDATE PEMBAYARAN
                |--------------------------------------------------------------------------
                */

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


                /*
                |--------------------------------------------------------------------------
                | UPDATE SETORAN
                |--------------------------------------------------------------------------
                */

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


    /**
     * Verifikasi pembayaran customer.
     *
     * Digunakan oleh petugas yang menerima uang customer.
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


        $total =
            (float) $stockOut
                ->subtotal;


        $alreadyPaid =
            (float) $stockOut
                ->customer_paid_amount;


        $paymentAmount =
            (float) $validated[
                'payment_amount'
            ];


        $remaining =
            max(
                $total
                - $alreadyPaid,
                0
            );


        if ($remaining <= 0) {
            return redirect()
                ->route(
                    'stock-outs.index'
                )
                ->with(
                    'error',
                    'Pembayaran customer sudah lunas.'
                );
        }


        if (
            $paymentAmount
            > $remaining
        ) {
            return redirect()
                ->route(
                    'stock-outs.index'
                )
                ->with(
                    'error',
                    'Jumlah pembayaran melebihi sisa tagihan customer.'
                );
        }


        $newPaid =
            $alreadyPaid
            + $paymentAmount;


        $newCustomerBalance =
            max(
                $total
                - $newPaid,
                0
            );


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
        | UANG CUSTOMER DIANGGAP DITERIMA PETUGAS
        |--------------------------------------------------------------------------
        */

        $staffReceived =
            $newPaid;


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
            $staffReceived > 0
            &&
            $staffBalance <= 0
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


        DB::transaction(
            function () use (
                $stockOut,
                $newPaid,
                $newCustomerBalance,
                $customerStatus,
                $customerPaidAt,
                $staffReceived,
                $staffDeposited,
                $staffBalance,
                $staffDepositStatus
            ) {

                $stockOut
                    ->customer_paid_amount =
                    $newPaid;


                $stockOut
                    ->customer_balance =
                    $newCustomerBalance;


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


                $stockOut->save();
            }
        );


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
                    'Pembayaran customer berhasil diverifikasi. Customer sudah lunas dan uang menunggu konfirmasi setoran Admin.'
                );
        }


        return redirect()
            ->route(
                'stock-outs.index'
            )
            ->with(
                'success',
                'Pembayaran customer berhasil diverifikasi sebagian. Sisa tagihan $'
                . number_format(
                    $newCustomerBalance,
                    2
                )
                . '.'
            );
    }


    /**
     * Konfirmasi setoran petugas.
     *
     * Hanya Admin / user dengan permission
     * stock-outs.confirm-deposit.
     */
    public function confirmDeposit(
        StockOut $stockOut
    ) {
        $staffReceived =
            (float) $stockOut
                ->staff_received_amount;


        if (
            $staffReceived
            <= 0
        ) {
            return redirect()
                ->route(
                    'stock-outs.index'
                )
                ->with(
                    'error',
                    'Belum ada uang customer yang diterima petugas.'
                );
        }


        if (
            (float) $stockOut
                ->staff_balance
            <= 0
            &&
            $stockOut
                ->staff_deposit_status
            === 'paid'
        ) {
            return redirect()
                ->route(
                    'stock-outs.index'
                )
                ->with(
                    'error',
                    'Setoran transaksi ini sudah dikonfirmasi sebelumnya.'
                );
        }


        DB::transaction(
            function () use (
                $stockOut,
                $staffReceived
            ) {

                $stockOut
                    ->staff_deposited_amount =
                    $staffReceived;


                $stockOut
                    ->staff_balance =
                    0;


                $stockOut
                    ->staff_deposit_status =
                    'paid';


                $stockOut
                    ->staff_deposited_at =
                    now();


                $stockOut
                    ->deposit_verified_by =
                    auth()->user()?->name
                    ?? 'Administrator';


                $stockOut->save();
            }
        );


        return redirect()
            ->route(
                'stock-outs.index'
            )
            ->with(
                'success',
                'Setoran dari '
                . (
                    $stockOut->sold_by
                    ?: 'petugas'
                )
                . ' berhasil dikonfirmasi lunas.'
            );
    }


    /**
     * Hapus transaksi.
     */
    public function destroy(
        StockOut $stockOut
    ) {
        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI YANG SUDAH MEMILIKI PEMBAYARAN TIDAK BOLEH DIHAPUS
        |--------------------------------------------------------------------------
        */

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


    /**
     * Filter index.
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


    /**
     * Validasi transaksi stok keluar.
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
}