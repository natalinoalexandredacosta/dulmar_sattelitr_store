<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TvVoucherTransaction;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TvVoucherTransactionController extends Controller
{
    /**
     * Daftar transaksi TV Voucher.
     */
    public function index(Request $request)
    {
        $validatedFilter = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:255',
            ],

            'provider' => [
                'nullable',
                'in:K-Vision,Nex Parabola,Nusantara HD',
            ],

            'recharge_status' => [
                'nullable',
                'in:pending,success,failed',
            ],

            'payment_status' => [
                'nullable',
                'in:unpaid,paid',
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
            'provider.in' =>
                'Provider TV Voucher tidak valid.',

            'recharge_status.in' =>
                'Status isi ulang tidak valid.',

            'payment_status.in' =>
                'Status setoran tidak valid.',

            'start_date.date' =>
                'Tanggal mulai tidak valid.',

            'end_date.date' =>
                'Tanggal selesai tidak valid.',

            'end_date.after_or_equal' =>
                'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);

        $search =
            $validatedFilter['search'] ?? null;

        $provider =
            $validatedFilter['provider'] ?? null;

        $rechargeStatus =
            $validatedFilter['recharge_status'] ?? null;

        $paymentStatus =
            $validatedFilter['payment_status'] ?? null;

        $startDate =
            $validatedFilter['start_date'] ?? null;

        $endDate =
            $validatedFilter['end_date'] ?? null;

        $query =
            TvVoucherTransaction::query()
                ->with('customer');

        if ($search) {
            $query->where(
                function ($searchQuery) use ($search) {
                    $searchQuery
                        ->where(
                            'customer_name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'receiver_number',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'package_name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'filled_by',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'customer_phone',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'customer_address',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'notes',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhereHas(
                            'customer',
                            function ($customerQuery) use ($search) {
                                $customerQuery->where(
                                    'customer_name',
                                    'like',
                                    '%' . $search . '%'
                                );
                            }
                        );
                }
            );
        }

        if ($provider) {
            $query->where(
                'provider',
                $provider
            );
        }

        if ($rechargeStatus) {
            $query->where(
                'recharge_status',
                $rechargeStatus
            );
        }

        if ($paymentStatus) {
            $query->where(
                'payment_status',
                $paymentStatus
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

        $totalTransactions =
            (clone $query)->count();

        $totalQuantity =
            (int) (clone $query)
                ->sum('quantity');

        $totalAmount =
            (float) (clone $query)
                ->sum('total_amount');

        $totalPaid =
            (float) (clone $query)
                ->sum('staff_deposited_amount');

        $totalUnpaid =
            (float) (clone $query)
                ->sum('staff_balance');

        $tvVouchers =
            $query
                ->latest('transaction_date')
                ->latest('id')
                ->paginate(10);

        $tvVouchers->appends(
            $request->query()
        );

        return view(
            'tv-vouchers.index',
            compact(
                'tvVouchers',
                'totalTransactions',
                'totalQuantity',
                'totalAmount',
                'totalPaid',
                'totalUnpaid',
                'search',
                'provider',
                'rechargeStatus',
                'paymentStatus',
                'startDate',
                'endDate'
            )
        );
    }

    /**
     * Laporan TV Voucher.
     */
    public function report(Request $request)
    {
        $validatedFilter =
            $request->validate([
                'start_date' => [
                    'nullable',
                    'date',
                ],

                'end_date' => [
                    'nullable',
                    'date',
                    'after_or_equal:start_date',
                ],

                'provider' => [
                    'nullable',
                    'in:K-Vision,Nex Parabola,Nusantara HD',
                ],

                'payment_status' => [
                    'nullable',
                    'in:unpaid,paid',
                ],

                'filled_by' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ], [
                'start_date.date' =>
                    'Tanggal mulai tidak valid.',

                'end_date.date' =>
                    'Tanggal selesai tidak valid.',

                'end_date.after_or_equal' =>
                    'Tanggal selesai harus sama atau setelah tanggal mulai.',

                'provider.in' =>
                    'Provider tidak valid.',

                'payment_status.in' =>
                    'Status setoran tidak valid.',

                'filled_by.max' =>
                    'Nama pengisi maksimal 255 karakter.',
            ]);

        $startDate =
            $validatedFilter['start_date'] ?? null;

        $endDate =
            $validatedFilter['end_date'] ?? null;

        $provider =
            $validatedFilter['provider'] ?? null;

        $paymentStatus =
            $validatedFilter['payment_status'] ?? null;

        $filledBy =
            $validatedFilter['filled_by'] ?? null;

        $query =
            TvVoucherTransaction::query()
                ->with('customer');

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

        if ($provider) {
            $query->where(
                'provider',
                $provider
            );
        }

        if ($paymentStatus) {
            $query->where(
                'payment_status',
                $paymentStatus
            );
        }

        if ($filledBy) {
            $query->where(
                'filled_by',
                'like',
                '%' . $filledBy . '%'
            );
        }

        $transactions =
            $query
                ->orderBy('transaction_date')
                ->orderBy('id')
                ->get();

        $totalTransactions =
            $transactions->count();

        $totalQuantity =
            (int) $transactions
                ->sum('quantity');

        $totalAmount =
            (float) $transactions
                ->sum('total_amount');

        $totalDeposited =
            (float) $transactions
                ->sum('staff_deposited_amount');

        $totalNotDeposited =
            (float) $transactions
                ->sum('staff_balance');

        $summaryByFiller =
            $transactions
                ->groupBy(
                    function ($transaction) {
                        return $transaction->filled_by
                            ?: 'Tidak diketahui';
                    }
                )
                ->map(
                    function ($items, $name) {
                        return [
                            'name' =>
                                $name,

                            'transactions' =>
                                $items->count(),

                            'quantity' =>
                                (int) $items->sum('quantity'),

                            'total_amount' =>
                                (float) $items->sum('total_amount'),

                            'deposited' =>
                                (float) $items->sum(
                                    'staff_deposited_amount'
                                ),

                            'not_deposited' =>
                                (float) $items->sum(
                                    'staff_balance'
                                ),
                        ];
                    }
                )
                ->values();

        return view(
            'tv-vouchers.report',
            compact(
                'transactions',
                'summaryByFiller',
                'totalTransactions',
                'totalQuantity',
                'totalAmount',
                'totalDeposited',
                'totalNotDeposited',
                'startDate',
                'endDate',
                'provider',
                'paymentStatus',
                'filledBy'
            )
        );
    }

    /**
     * Form tambah transaksi.
     */
    public function create()
    {
        return view(
            'tv-vouchers.create'
        );
    }

    /**
     * Simpan transaksi baru.
     */
    public function store(Request $request)
    {
        $validatedData =
            $this->validateTransaction(
                $request
            );

        /*
         * Karena customer sekarang diisi manual,
         * customer_id tidak digunakan untuk transaksi baru.
         */
        $validatedData['customer_id'] =
            null;

        if (
            $validatedData['recharge_status']
                === TvVoucherTransaction::RECHARGE_SUCCESS
            && !$request->hasFile('payment_proof')
        ) {
            throw ValidationException::withMessages([
                'payment_proof' =>
                    'Bukti transaksi wajib diunggah jika isi ulang berhasil.',
            ]);
        }

        $this->calculateTransaction(
            $validatedData
        );

        $this->calculateCustomerPayment(
            $validatedData
        );

        $this->calculateStaffDeposit(
            $validatedData
        );

        $this->fillCustomerInformation(
            $validatedData
        );

        if ($request->hasFile('payment_proof')) {
            $validatedData['payment_proof'] =
                $request
                    ->file('payment_proof')
                    ->store(
                        'tv-voucher-proofs',
                        'public'
                    );
        }

        try {
            $tvVoucher =
                DB::transaction(
                    function () use ($validatedData) {
                        return TvVoucherTransaction::create(
                            $validatedData
                        );
                    }
                );
        } catch (\Throwable $exception) {
            if (
                !empty(
                    $validatedData['payment_proof']
                )
            ) {
                Storage::disk('public')
                    ->delete(
                        $validatedData['payment_proof']
                    );
            }

            throw $exception;
        }

        $tvVoucher->load('customer');

        $this->sendNewTransactionTelegram(
            $tvVoucher
        );

        return redirect()
            ->route('tv-vouchers.index')
            ->with(
                'success',
                'Transaksi TV Voucher berhasil ditambahkan.'
            );
    }

    /**
     * Detail transaksi.
     */
    public function show(
        TvVoucherTransaction $tvVoucher
    ) {
        $tvVoucher->load('customer');

        return view(
            'tv-vouchers.show',
            compact('tvVoucher')
        );
    }

    /**
     * Form edit.
     */
    public function edit(
        TvVoucherTransaction $tvVoucher
    ) {
        $customers =
            Customer::orderBy(
                'customer_name'
            )->get();

        return view(
            'tv-vouchers.edit',
            compact(
                'tvVoucher',
                'customers'
            )
        );
    }

    /**
     * Update transaksi.
     */
    public function update(
        Request $request,
        TvVoucherTransaction $tvVoucher
    ) {
        $validatedData =
            $this->validateTransaction(
                $request
            );

        /*
         * customer_id lama tetap dipertahankan apabila edit view lama
         * masih menggunakannya. Tetapi customer_name manual tetap menjadi
         * nama utama yang digunakan.
         */
        $validatedData['customer_id'] =
            $validatedData['customer_id']
            ?? $tvVoucher->customer_id;

        if (
            $validatedData['recharge_status']
                === TvVoucherTransaction::RECHARGE_SUCCESS
            && !$request->hasFile('payment_proof')
            && !$tvVoucher->payment_proof
        ) {
            throw ValidationException::withMessages([
                'payment_proof' =>
                    'Bukti transaksi wajib diunggah jika isi ulang berhasil.',
            ]);
        }

        $this->calculateTransaction(
            $validatedData
        );

        $this->calculateCustomerPayment(
            $validatedData
        );

        $this->calculateStaffDeposit(
            $validatedData,
            $tvVoucher
        );

        $this->fillCustomerInformation(
            $validatedData
        );

        $oldProof =
            $tvVoucher->payment_proof;

        $newProof =
            null;

        if ($request->hasFile('payment_proof')) {
            $newProof =
                $request
                    ->file('payment_proof')
                    ->store(
                        'tv-voucher-proofs',
                        'public'
                    );

            $validatedData['payment_proof'] =
                $newProof;
        }

        try {
            DB::transaction(
                function () use (
                    $validatedData,
                    $tvVoucher
                ) {
                    $tvVoucher->update(
                        $validatedData
                    );
                }
            );
        } catch (\Throwable $exception) {
            if ($newProof) {
                Storage::disk('public')
                    ->delete(
                        $newProof
                    );
            }

            throw $exception;
        }

        if (
            $newProof
            && $oldProof
            && $oldProof !== $newProof
        ) {
            Storage::disk('public')
                ->delete(
                    $oldProof
                );
        }

        return redirect()
            ->route('tv-vouchers.index')
            ->with(
                'success',
                'Transaksi TV Voucher berhasil diperbarui.'
            );
    }

    /**
     * Verifikasi pembayaran customer.
     */
    public function verifyCustomerPayment(
        Request $request,
        TvVoucherTransaction $tvVoucher
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
            (float) $tvVoucher->total_amount;

        $alreadyPaid =
            (float) $tvVoucher
                ->customer_paid_amount;

        $paymentAmount =
            (float) $validated[
                'payment_amount'
            ];

        $remainingBeforePayment =
            max(
                $total - $alreadyPaid,
                0
            );

        if ($remainingBeforePayment <= 0) {
            return redirect()
                ->route('tv-vouchers.index')
                ->with(
                    'error',
                    'Pembayaran customer sudah lunas.'
                );
        }

        if (
            $paymentAmount
            > $remainingBeforePayment
        ) {
            return redirect()
                ->route('tv-vouchers.index')
                ->with(
                    'error',
                    'Jumlah pembayaran melebihi sisa tagihan customer.'
                );
        }

        $newPaidAmount =
            $alreadyPaid
            + $paymentAmount;

        $newCustomerBalance =
            max(
                $total - $newPaidAmount,
                0
            );

        if ($newCustomerBalance <= 0) {
            $customerPaymentStatus =
                TvVoucherTransaction::CUSTOMER_PAYMENT_PAID;

            $customerPaidAt =
                now();
        } else {
            $customerPaymentStatus =
                TvVoucherTransaction::CUSTOMER_PAYMENT_PARTIAL;

            $customerPaidAt =
                null;
        }

        /*
         * Untuk alur sekarang, pembayaran yang diverifikasi
         * dianggap sudah diterima oleh petugas.
         */
        $staffReceivedAmount =
            $newPaidAmount;

        $staffDepositedAmount =
            (float) $tvVoucher
                ->staff_deposited_amount;

        if (
            $staffDepositedAmount
            > $staffReceivedAmount
        ) {
            $staffDepositedAmount =
                $staffReceivedAmount;
        }

        $staffBalance =
            max(
                $staffReceivedAmount
                - $staffDepositedAmount,
                0
            );

        if ($staffReceivedAmount <= 0) {
            $staffDepositStatus =
                TvVoucherTransaction::STAFF_DEPOSIT_UNPAID;

            $legacyPaymentStatus =
                TvVoucherTransaction::PAYMENT_UNPAID;

            $staffDepositedAt =
                null;

            $paidAt =
                null;
        } elseif ($staffBalance <= 0) {
            $staffDepositStatus =
                TvVoucherTransaction::STAFF_DEPOSIT_PAID;

            $legacyPaymentStatus =
                TvVoucherTransaction::PAYMENT_PAID;

            $staffDepositedAt =
                $tvVoucher->staff_deposited_at
                ?? now();

            $paidAt =
                $tvVoucher->paid_at
                ?? now();
        } elseif ($staffDepositedAmount > 0) {
            $staffDepositStatus =
                TvVoucherTransaction::STAFF_DEPOSIT_PARTIAL;

            $legacyPaymentStatus =
                TvVoucherTransaction::PAYMENT_UNPAID;

            $staffDepositedAt =
                $tvVoucher->staff_deposited_at
                ?? now();

            $paidAt =
                null;
        } else {
            $staffDepositStatus =
                TvVoucherTransaction::STAFF_DEPOSIT_UNPAID;

            $legacyPaymentStatus =
                TvVoucherTransaction::PAYMENT_UNPAID;

            $staffDepositedAt =
                null;

            $paidAt =
                null;
        }

        DB::transaction(
            function () use (
                $tvVoucher,
                $newPaidAmount,
                $newCustomerBalance,
                $customerPaymentStatus,
                $customerPaidAt,
                $staffReceivedAmount,
                $staffDepositedAmount,
                $staffBalance,
                $staffDepositStatus,
                $legacyPaymentStatus,
                $staffDepositedAt,
                $paidAt
            ) {
                $tvVoucher->update([
                    'customer_paid_amount' =>
                        $newPaidAmount,

                    'customer_balance' =>
                        $newCustomerBalance,

                    'customer_payment_status' =>
                        $customerPaymentStatus,

                    'customer_paid_at' =>
                        $customerPaidAt,

                    'staff_received_amount' =>
                        $staffReceivedAmount,

                    'staff_deposited_amount' =>
                        $staffDepositedAmount,

                    'staff_balance' =>
                        $staffBalance,

                    'staff_deposit_status' =>
                        $staffDepositStatus,

                    'staff_deposited_at' =>
                        $staffDepositedAt,

                    'payment_status' =>
                        $legacyPaymentStatus,

                    'paid_at' =>
                        $paidAt,
                ]);
            }
        );

        $tvVoucher->refresh();
        $tvVoucher->load('customer');

        $telegram =
            app(TelegramService::class);

        $customerName =
            e(
                $tvVoucher->customer_name
                ?: (
                    $tvVoucher->customer?->customer_name
                    ?? 'Tanpa Pelanggan'
                )
            );

        $customerPhone =
            e(
                $tvVoucher->customer_phone
                ?: '-'
            );

        $customerAddress =
            e(
                $tvVoucher->customer_address
                ?: '-'
            );

        $filledBy =
            e(
                $tvVoucher->filled_by
                ?: '-'
            );

        $provider =
            e(
                $tvVoucher->provider
                ?: '-'
            );

        $receiverNumber =
            e(
                $tvVoucher->receiver_number
                ?: '-'
            );

        $paymentFormatted =
            number_format(
                $paymentAmount,
                2
            );

        $totalPaidFormatted =
            number_format(
                $newPaidAmount,
                2
            );

        $customerBalanceFormatted =
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
                ? 'Lunas'
                : 'Bayar Sebagian';

        $telegram->send(
            "<b>VERIFIKASI PEMBAYARAN CUSTOMER</b>\n\n"
            . "<b>Pelanggan:</b> {$customerName}\n"
            . "<b>No HP:</b> {$customerPhone}\n"
            . "<b>Tempat Tinggal:</b> {$customerAddress}\n"
            . "<b>Diisi Oleh:</b> {$filledBy}\n"
            . "<b>Provider:</b> {$provider}\n"
            . "<b>No Receiver:</b> {$receiverNumber}\n\n"

            . "<b>Pembayaran Baru:</b> \${$paymentFormatted}\n"
            . "<b>Total Sudah Bayar:</b> \${$totalPaidFormatted}\n"
            . "<b>Sisa Customer:</b> \${$customerBalanceFormatted}\n"
            . "<b>Status Customer:</b> {$statusText}\n\n"

            . "<b>Uang Diterima Petugas:</b> \${$totalPaidFormatted}\n"
            . "<b>Belum Disetor Petugas:</b> \${$staffBalanceFormatted}"
        );

        if ($newCustomerBalance <= 0) {
            return redirect()
                ->route('tv-vouchers.index')
                ->with(
                    'success',
                    'Pembayaran customer berhasil diverifikasi dan sudah Lunas.'
                );
        }

        return redirect()
            ->route('tv-vouchers.index')
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
     * Konfirmasi seluruh setoran petugas.
     */
    public function confirmDeposit(
        TvVoucherTransaction $tvVoucher
    ) {
        $staffReceived =
            (float) $tvVoucher
                ->staff_received_amount;

        if ($staffReceived <= 0) {
            return redirect()
                ->route('tv-vouchers.index')
                ->with(
                    'error',
                    'Belum ada uang customer yang diterima petugas untuk disetor.'
                );
        }

        if (
            $tvVoucher->staff_deposit_status
                === TvVoucherTransaction::STAFF_DEPOSIT_PAID
            && (float) $tvVoucher->staff_balance <= 0
        ) {
            return redirect()
                ->route('tv-vouchers.index')
                ->with(
                    'error',
                    'Setoran petugas sudah lunas sebelumnya.'
                );
        }

        DB::transaction(
            function () use (
                $tvVoucher,
                $staffReceived
            ) {
                $tvVoucher->update([
                    'staff_deposited_amount' =>
                        $staffReceived,

                    'staff_balance' =>
                        0,

                    'staff_deposit_status' =>
                        TvVoucherTransaction::STAFF_DEPOSIT_PAID,

                    'staff_deposited_at' =>
                        now(),

                    'payment_status' =>
                        TvVoucherTransaction::PAYMENT_PAID,

                    'paid_at' =>
                        now(),
                ]);
            }
        );

        $tvVoucher->refresh();
        $tvVoucher->load('customer');

        $telegram =
            app(TelegramService::class);

        $customerName =
            e(
                $tvVoucher->customer_name
                ?: (
                    $tvVoucher->customer?->customer_name
                    ?? 'Tanpa Pelanggan'
                )
            );

        $filledBy =
            e(
                $tvVoucher->filled_by
                ?: '-'
            );

        $provider =
            e(
                $tvVoucher->provider
                ?: '-'
            );

        $receiverNumber =
            e(
                $tvVoucher->receiver_number
                ?: '-'
            );

        $received =
            number_format(
                (float) $tvVoucher
                    ->staff_received_amount,
                2
            );

        $deposited =
            number_format(
                (float) $tvVoucher
                    ->staff_deposited_amount,
                2
            );

        $balance =
            number_format(
                (float) $tvVoucher
                    ->staff_balance,
                2
            );

        $depositedAt =
            $tvVoucher->staff_deposited_at
                ? Carbon::parse(
                    $tvVoucher->staff_deposited_at
                )->format('d-m-Y H:i')
                : now()->format('d-m-Y H:i');

        $telegram->send(
            "<b>SETORAN PETUGAS TV VOUCHER</b>\n\n"
            . "<b>Pelanggan:</b> {$customerName}\n"
            . "<b>Diisi Oleh:</b> {$filledBy}\n"
            . "<b>Provider:</b> {$provider}\n"
            . "<b>No Receiver:</b> {$receiverNumber}\n\n"

            . "<b>Uang Diterima:</b> \${$received}\n"
            . "<b>Sudah Disetor:</b> \${$deposited}\n"
            . "<b>Belum Disetor:</b> \${$balance}\n"
            . "<b>Status:</b> Sudah Setor\n"
            . "<b>Waktu Setor:</b> {$depositedAt}\n\n"

            . "<b>Setoran petugas sudah lunas.</b>"
        );

        return redirect()
            ->route('tv-vouchers.index')
            ->with(
                'success',
                'Setoran dari '
                . (
                    $tvVoucher->filled_by
                    ?: 'petugas'
                )
                . ' berhasil dikonfirmasi lunas.'
            );
    }

    /**
     * Hapus transaksi.
     */
    public function destroy(
        TvVoucherTransaction $tvVoucher
    ) {
        if (
            $tvVoucher->recharge_status
                === TvVoucherTransaction::RECHARGE_SUCCESS
            || (float) $tvVoucher->staff_deposited_amount > 0
            || $tvVoucher->payment_status
                === TvVoucherTransaction::PAYMENT_PAID
        ) {
            return redirect()
                ->route('tv-vouchers.index')
                ->with(
                    'error',
                    'Transaksi yang berhasil atau sudah memiliki setoran tidak dapat dihapus.'
                );
        }

        $paymentProof =
            $tvVoucher->payment_proof;

        DB::transaction(
            function () use ($tvVoucher) {
                $tvVoucher->delete();
            }
        );

        if ($paymentProof) {
            Storage::disk('public')
                ->delete(
                    $paymentProof
                );
        }

        return redirect()
            ->route('tv-vouchers.index')
            ->with(
                'success',
                'Transaksi TV Voucher berhasil dihapus.'
            );
    }

    /**
     * Validasi transaksi.
     */
    private function validateTransaction(
        Request $request
    ): array {
        return $request->validate([
            /*
             * Untuk kompatibilitas data lama.
             */
            'customer_id' => [
                'nullable',
                'exists:customers,id',
            ],

            /*
             * Nama customer manual.
             */
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'filled_by' => [
                'required',
                'string',
                'max:255',
            ],

            'provider' => [
                'required',
                'in:K-Vision,Nex Parabola,Nusantara HD',
            ],

            'receiver_number' => [
                'required',
                'string',
                'max:100',
            ],

            'package_name' => [
                'required',
                'string',
                'max:255',
            ],

            'subscription_months' => [
                'required',
                'integer',
                'in:1,3,6,12',
            ],

            'unit_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'additional_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'recharge_status' => [
                'required',
                'in:pending,success,failed',
            ],

            'payment_status' => [
                'required',
                'in:unpaid,paid',
            ],

            'customer_payment_status' => [
                'nullable',
                'in:unpaid,partial,paid',
            ],

            'customer_paid_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'customer_phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'customer_address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'staff_deposited_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'payment_proof' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
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
            'customer_id.exists' =>
                'Pelanggan tidak ditemukan.',

            'customer_name.required' =>
                'Nama pelanggan wajib diisi.',

            'customer_name.string' =>
                'Nama pelanggan tidak valid.',

            'customer_name.max' =>
                'Nama pelanggan maksimal 255 karakter.',

            'filled_by.required' =>
                'Nama orang yang mengisi voucher wajib diisi.',

            'filled_by.max' =>
                'Nama pengisi voucher maksimal 255 karakter.',

            'provider.required' =>
                'Provider wajib dipilih.',

            'provider.in' =>
                'Provider tidak valid.',

            'receiver_number.required' =>
                'Nomor receiver wajib diisi.',

            'package_name.required' =>
                'Nama paket wajib diisi.',

            'subscription_months.required' =>
                'Masa aktif paket wajib dipilih.',

            'subscription_months.in' =>
                'Masa aktif harus 1, 3, 6, atau 12 bulan.',

            'unit_amount.required' =>
                'Nominal voucher wajib diisi.',

            'unit_amount.numeric' =>
                'Nominal voucher harus berupa angka.',

            'quantity.required' =>
                'Jumlah wajib diisi.',

            'quantity.integer' =>
                'Jumlah harus berupa angka bulat.',

            'quantity.min' =>
                'Jumlah minimal 1.',

            'additional_fee.numeric' =>
                'Biaya tambahan harus berupa angka.',

            'discount.numeric' =>
                'Diskon harus berupa angka.',

            'recharge_status.required' =>
                'Status isi ulang wajib dipilih.',

            'recharge_status.in' =>
                'Status isi ulang tidak valid.',

            'payment_status.required' =>
                'Status setoran wajib dipilih.',

            'payment_status.in' =>
                'Status setoran tidak valid.',

            'customer_payment_status.in' =>
                'Status pembayaran customer tidak valid.',

            'customer_paid_amount.numeric' =>
                'Jumlah pembayaran customer harus berupa angka.',

            'customer_paid_amount.min' =>
                'Jumlah pembayaran customer tidak boleh kurang dari nol.',

            'customer_phone.max' =>
                'Nomor HP maksimal 50 karakter.',

            'customer_address.max' =>
                'Tempat tinggal maksimal 255 karakter.',

            'staff_deposited_amount.numeric' =>
                'Jumlah setoran petugas harus berupa angka.',

            'staff_deposited_amount.min' =>
                'Jumlah setoran petugas tidak boleh kurang dari nol.',

            'payment_proof.image' =>
                'Bukti transaksi harus berupa gambar.',

            'payment_proof.mimes' =>
                'Bukti harus berformat JPG, JPEG, PNG, atau WEBP.',

            'payment_proof.max' =>
                'Ukuran bukti transaksi maksimal 5 MB.',

            'transaction_date.required' =>
                'Tanggal transaksi wajib diisi.',

            'transaction_date.date' =>
                'Format tanggal transaksi tidak valid.',

            'notes.max' =>
                'Catatan maksimal 1.000 karakter.',
        ]);
    }

    /**
     * Hitung total transaksi.
     */
    private function calculateTransaction(
        array &$validatedData
    ): void {
        $unitAmount =
            (float) $validatedData[
                'unit_amount'
            ];

        $quantity =
            (int) $validatedData[
                'quantity'
            ];

        $additionalFee =
            (float) (
                $validatedData[
                    'additional_fee'
                ]
                ?? 0
            );

        $discount =
            (float) (
                $validatedData[
                    'discount'
                ]
                ?? 0
            );

        $subtotal =
            $unitAmount
            * $quantity;

        $totalAmount =
            $subtotal
            + $additionalFee
            - $discount;

        if ($totalAmount < 0) {
            throw ValidationException::withMessages([
                'discount' =>
                    'Diskon tidak boleh melebihi subtotal dan biaya tambahan.',
            ]);
        }

        $validatedData['additional_fee'] =
            $additionalFee;

        $validatedData['discount'] =
            $discount;

        $validatedData['subtotal'] =
            $subtotal;

        $validatedData['total_amount'] =
            $totalAmount;
    }

    /**
     * Hitung pembayaran customer.
     */
    private function calculateCustomerPayment(
        array &$validatedData
    ): void {
        $status =
            $validatedData[
                'customer_payment_status'
            ]
            ?? TvVoucherTransaction::CUSTOMER_PAYMENT_PAID;

        $total =
            (float) $validatedData[
                'total_amount'
            ];

        $paid =
            (float) (
                $validatedData[
                    'customer_paid_amount'
                ]
                ?? 0
            );

        /*
         * Nama customer selalu wajib karena sekarang
         * menggunakan input manual.
         */
        if (
            empty(
                trim(
                    (string) (
                        $validatedData[
                            'customer_name'
                        ]
                        ?? ''
                    )
                )
            )
        ) {
            throw ValidationException::withMessages([
                'customer_name' =>
                    'Nama pelanggan wajib diisi.',
            ]);
        }

        if (
            $status
            === TvVoucherTransaction::CUSTOMER_PAYMENT_PAID
        ) {
            $validatedData[
                'customer_payment_status'
            ] =
                TvVoucherTransaction::CUSTOMER_PAYMENT_PAID;

            $validatedData[
                'customer_paid_amount'
            ] =
                $total;

            $validatedData[
                'customer_balance'
            ] =
                0;

            $validatedData[
                'customer_paid_at'
            ] =
                now();

            return;
        }

        if (
            $status
            === TvVoucherTransaction::CUSTOMER_PAYMENT_UNPAID
        ) {
            $validatedData[
                'customer_payment_status'
            ] =
                TvVoucherTransaction::CUSTOMER_PAYMENT_UNPAID;

            $validatedData[
                'customer_paid_amount'
            ] =
                0;

            $validatedData[
                'customer_balance'
            ] =
                $total;

            $validatedData[
                'customer_paid_at'
            ] =
                null;

            return;
        }

        if (
            $status
            === TvVoucherTransaction::CUSTOMER_PAYMENT_PARTIAL
        ) {
            if ($paid <= 0) {
                throw ValidationException::withMessages([
                    'customer_paid_amount' =>
                        'Masukkan jumlah yang sudah dibayar customer.',
                ]);
            }

            if ($paid >= $total) {
                throw ValidationException::withMessages([
                    'customer_paid_amount' =>
                        'Untuk Bayar Sebagian, nominal harus lebih kecil dari total transaksi.',
                ]);
            }

            $validatedData[
                'customer_payment_status'
            ] =
                TvVoucherTransaction::CUSTOMER_PAYMENT_PARTIAL;

            $validatedData[
                'customer_paid_amount'
            ] =
                $paid;

            $validatedData[
                'customer_balance'
            ] =
                $total - $paid;

            $validatedData[
                'customer_paid_at'
            ] =
                null;
        }
    }

    /**
     * Hitung setoran petugas.
     */
    private function calculateStaffDeposit(
        array &$validatedData,
        ?TvVoucherTransaction $existingTransaction = null
    ): void {
        $received =
            (float) (
                $validatedData[
                    'customer_paid_amount'
                ]
                ?? 0
            );

        $validatedData[
            'staff_received_amount'
        ] =
            $received;

        if (
            array_key_exists(
                'staff_deposited_amount',
                $validatedData
            )
            && $validatedData[
                'staff_deposited_amount'
            ] !== null
        ) {
            $deposited =
                (float) $validatedData[
                    'staff_deposited_amount'
                ];
        } elseif ($existingTransaction) {
            $deposited =
                (float) $existingTransaction
                    ->staff_deposited_amount;
        } elseif (
            (
                $validatedData[
                    'payment_status'
                ]
                ?? 'unpaid'
            )
            === TvVoucherTransaction::PAYMENT_PAID
        ) {
            $deposited =
                $received;
        } else {
            $deposited =
                0;
        }

        if ($deposited < 0) {
            $deposited =
                0;
        }

        if ($deposited > $received) {
            throw ValidationException::withMessages([
                'staff_deposited_amount' =>
                    'Setoran petugas tidak boleh melebihi uang yang diterima dari customer.',
            ]);
        }

        $balance =
            $received
            - $deposited;

        $validatedData[
            'staff_deposited_amount'
        ] =
            $deposited;

        $validatedData[
            'staff_balance'
        ] =
            $balance;

        if ($received <= 0) {
            $validatedData[
                'staff_deposit_status'
            ] =
                TvVoucherTransaction::STAFF_DEPOSIT_UNPAID;

            $validatedData[
                'staff_deposited_amount'
            ] =
                0;

            $validatedData[
                'staff_balance'
            ] =
                0;

            $validatedData[
                'staff_deposited_at'
            ] =
                null;

            $validatedData[
                'payment_status'
            ] =
                TvVoucherTransaction::PAYMENT_UNPAID;

            $validatedData[
                'paid_at'
            ] =
                null;

            return;
        }

        if ($deposited <= 0) {
            $validatedData[
                'staff_deposit_status'
            ] =
                TvVoucherTransaction::STAFF_DEPOSIT_UNPAID;

            $validatedData[
                'staff_deposited_at'
            ] =
                null;

            $validatedData[
                'payment_status'
            ] =
                TvVoucherTransaction::PAYMENT_UNPAID;

            $validatedData[
                'paid_at'
            ] =
                null;

            return;
        }

        if ($deposited < $received) {
            $validatedData[
                'staff_deposit_status'
            ] =
                TvVoucherTransaction::STAFF_DEPOSIT_PARTIAL;

            $validatedData[
                'staff_deposited_at'
            ] =
                $existingTransaction?->staff_deposited_at
                ?? now();

            $validatedData[
                'payment_status'
            ] =
                TvVoucherTransaction::PAYMENT_UNPAID;

            $validatedData[
                'paid_at'
            ] =
                null;

            return;
        }

        $validatedData[
            'staff_deposit_status'
        ] =
            TvVoucherTransaction::STAFF_DEPOSIT_PAID;

        $validatedData[
            'staff_balance'
        ] =
            0;

        $validatedData[
            'staff_deposited_at'
        ] =
            $existingTransaction?->staff_deposited_at
            ?? now();

        $validatedData[
            'payment_status'
        ] =
            TvVoucherTransaction::PAYMENT_PAID;

        $validatedData[
            'paid_at'
        ] =
            $existingTransaction?->paid_at
            ?? now();
    }

    /**
     * Isi informasi customer.
     */
    private function fillCustomerInformation(
        array &$validatedData
    ): void {
        /*
         * customer_name sekarang merupakan data utama.
         */
        $validatedData[
            'customer_name'
        ] =
            trim(
                (string) (
                    $validatedData[
                        'customer_name'
                    ]
                    ?? ''
                )
            );

        /*
         * Jika transaksi lama masih memiliki customer_id,
         * relasi lama tetap boleh digunakan sebagai fallback
         * untuk nomor HP/alamat.
         */
        if (
            empty(
                $validatedData[
                    'customer_id'
                ]
            )
        ) {
            $validatedData[
                'customer_phone'
            ] =
                !empty(
                    $validatedData[
                        'customer_phone'
                    ]
                )
                    ? trim(
                        (string) $validatedData[
                            'customer_phone'
                        ]
                    )
                    : null;

            $validatedData[
                'customer_address'
            ] =
                !empty(
                    $validatedData[
                        'customer_address'
                    ]
                )
                    ? trim(
                        (string) $validatedData[
                            'customer_address'
                        ]
                    )
                    : null;

            return;
        }

        $customer =
            Customer::find(
                $validatedData[
                    'customer_id'
                ]
            );

        if (!$customer) {
            return;
        }

        if (
            empty(
                $validatedData[
                    'customer_name'
                ]
            )
        ) {
            $validatedData[
                'customer_name'
            ] =
                $customer->customer_name;
        }

        $validatedData[
            'customer_phone'
        ] =
            !empty(
                $validatedData[
                    'customer_phone'
                ]
            )
                ? trim(
                    (string) $validatedData[
                        'customer_phone'
                    ]
                )
                : $customer->getAttribute(
                    'phone'
                );

        $validatedData[
            'customer_address'
        ] =
            !empty(
                $validatedData[
                    'customer_address'
                ]
            )
                ? trim(
                    (string) $validatedData[
                        'customer_address'
                    ]
                )
                : $customer->getAttribute(
                    'address'
                );
    }

    /**
     * Telegram transaksi baru.
     */
    private function sendNewTransactionTelegram(
        TvVoucherTransaction $tvVoucher
    ): void {
        $telegram =
            app(TelegramService::class);

        $customerName =
            e(
                $tvVoucher->customer_name
                ?: (
                    $tvVoucher->customer?->customer_name
                    ?? 'Tanpa Pelanggan'
                )
            );

        $customerPhone =
            e(
                $tvVoucher->customer_phone
                ?: '-'
            );

        $customerAddress =
            e(
                $tvVoucher->customer_address
                ?: '-'
            );

        $filledBy =
            e(
                $tvVoucher->filled_by
                ?: '-'
            );

        $provider =
            e(
                $tvVoucher->provider
                ?: '-'
            );

        $receiverNumber =
            e(
                $tvVoucher->receiver_number
                ?: '-'
            );

        $packageName =
            e(
                $tvVoucher->package_name
                ?: '-'
            );

        $months =
            (int) $tvVoucher
                ->subscription_months;

        $subscriptionText =
            $months === 12
                ? '1 Tahun'
                : $months . ' Bulan';

        $quantity =
            (int) $tvVoucher
                ->quantity;

        $total =
            number_format(
                (float) $tvVoucher
                    ->total_amount,
                2
            );

        $customerPaid =
            number_format(
                (float) $tvVoucher
                    ->customer_paid_amount,
                2
            );

        $customerBalance =
            number_format(
                (float) $tvVoucher
                    ->customer_balance,
                2
            );

        $staffReceived =
            number_format(
                (float) $tvVoucher
                    ->staff_received_amount,
                2
            );

        $staffDeposited =
            number_format(
                (float) $tvVoucher
                    ->staff_deposited_amount,
                2
            );

        $staffBalance =
            number_format(
                (float) $tvVoucher
                    ->staff_balance,
                2
            );

        $rechargeStatus =
            match (
                $tvVoucher->recharge_status
            ) {
                TvVoucherTransaction::RECHARGE_SUCCESS =>
                    'Berhasil',

                TvVoucherTransaction::RECHARGE_FAILED =>
                    'Gagal',

                default =>
                    'Pending',
            };

        $customerPaymentStatus =
            match (
                $tvVoucher
                    ->customer_payment_status
            ) {
                TvVoucherTransaction::CUSTOMER_PAYMENT_PAID =>
                    'Lunas',

                TvVoucherTransaction::CUSTOMER_PAYMENT_PARTIAL =>
                    'Bayar Sebagian',

                default =>
                    'Belum Bayar',
            };

        if (
            (float) $tvVoucher
                ->staff_received_amount
            <= 0
        ) {
            $staffDepositStatus =
                'Belum Ada Uang untuk Disetor';
        } else {
            $staffDepositStatus =
                match (
                    $tvVoucher
                        ->staff_deposit_status
                ) {
                    TvVoucherTransaction::STAFF_DEPOSIT_PAID =>
                        'Sudah Setor',

                    TvVoucherTransaction::STAFF_DEPOSIT_PARTIAL =>
                        'Setor Sebagian',

                    default =>
                        'Belum Setor',
                };
        }

        $transactionDate =
            $tvVoucher->transaction_date
                ? Carbon::parse(
                    $tvVoucher
                        ->transaction_date
                )->format('d-m-Y')
                : '-';

        $message =
            "<b>TV VOUCHER BARU</b>\n\n"

            . "<b>Pelanggan:</b> {$customerName}\n"
            . "<b>No HP:</b> {$customerPhone}\n"
            . "<b>Tempat Tinggal:</b> {$customerAddress}\n"
            . "<b>Diisi Oleh:</b> {$filledBy}\n\n"

            . "<b>Provider:</b> {$provider}\n"
            . "<b>No Receiver:</b> {$receiverNumber}\n"
            . "<b>Paket:</b> {$packageName}\n"
            . "<b>Masa Aktif:</b> {$subscriptionText}\n"
            . "<b>Jumlah:</b> {$quantity}\n"
            . "<b>Total Isi Saldo:</b> \${$total}\n\n"

            . "<b>PEMBAYARAN CUSTOMER</b>\n"
            . "<b>Status:</b> {$customerPaymentStatus}\n"
            . "<b>Sudah Bayar:</b> \${$customerPaid}\n"
            . "<b>Sisa Customer:</b> \${$customerBalance}\n\n"

            . "<b>SETORAN PETUGAS</b>\n"
            . "<b>Diisi Oleh:</b> {$filledBy}\n"
            . "<b>Uang Diterima:</b> \${$staffReceived}\n"
            . "<b>Sudah Disetor:</b> \${$staffDeposited}\n"
            . "<b>Belum Disetor:</b> \${$staffBalance}\n"
            . "<b>Status:</b> {$staffDepositStatus}\n\n"

            . "<b>Status Isi Ulang:</b> {$rechargeStatus}\n"
            . "<b>Tanggal:</b> {$transactionDate}";

        if (
            (float) $tvVoucher
                ->customer_balance
            > 0
        ) {
            $message .=
                "\n\n"
                . "<b>PIUTANG CUSTOMER:</b> "
                . "\${$customerBalance}";
        }

        if (
            (float) $tvVoucher
                ->staff_balance
            > 0
        ) {
            $message .=
                "\n"
                . "<b>SETORAN PETUGAS BELUM LUNAS:</b> "
                . "\${$staffBalance}";
        }

        $telegram->send(
            $message
        );
    }
}