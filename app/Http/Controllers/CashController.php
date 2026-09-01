<?php

namespace App\Http\Controllers;

use App\Models\CashTransaction;
use App\Services\StockTelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CashController extends Controller
{
    /**
     * Halaman utama Kas Inventory.
     */
    public function index()
    {
        $cashBalance =
            CashTransaction::currentBalance();

        $totalIncome =
            CashTransaction::totalIncome();

        $totalExpense =
            CashTransaction::totalExpense();

        $totalPendingExpense =
            CashTransaction::totalPendingExpense();

        $totalPendingIncome =
            CashTransaction::totalPendingIncome();

        $transactions =
            CashTransaction::query()
                ->orderByDesc('transaction_date')
                ->orderByDesc('id')
                ->paginate(15);

        return view(
            'cash.index',
            compact(
                'cashBalance',
                'totalIncome',
                'totalExpense',
                'totalPendingExpense',
                'totalPendingIncome',
                'transactions'
            )
        );
    }


    /**
     * Tambah transaksi kas manual.
     */
    public function store(Request $request)
    {
        $validated =
            $request->validate([
                'type' => [
                    'required',
                    'in:income,expense',
                ],

                'category' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'borrower_name' => [
                    'nullable',
                    'string',
                    'max:150',
                ],

                'loan_reference' => [
                    'nullable',
                    'integer',
                ],

                'amount' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],

                'description' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ], [
                'type.required' =>
                    'Jenis transaksi wajib dipilih.',

                'type.in' =>
                    'Jenis transaksi tidak valid.',

                'category.required' =>
                    'Kategori transaksi wajib dipilih.',

                'category.max' =>
                    'Kategori maksimal 100 karakter.',

                'borrower_name.max' =>
                    'Nama peminjam maksimal 150 karakter.',

                'amount.required' =>
                    'Jumlah uang wajib diisi.',

                'amount.numeric' =>
                    'Jumlah uang harus berupa angka.',

                'amount.min' =>
                    'Jumlah uang harus lebih dari 0.',

                'description.max' =>
                    'Keterangan maksimal 255 karakter.',
            ]);


        $type =
            $validated['type'];

        $category =
            $validated['category'];

        $amount =
            (float) $validated['amount'];

        $borrowerName =
            trim(
                (string) (
                    $validated['borrower_name']
                    ?? ''
                )
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI PINJAMAN
        |--------------------------------------------------------------------------
        */

        if (
            $category === 'Pinjaman Keluar'
            &&
            $type !== 'expense'
        ) {
            return redirect()
                ->route('cash.index')
                ->withInput()
                ->with(
                    'error',
                    'Pinjaman Keluar harus menggunakan jenis transaksi Cash Keluar.'
                );
        }


        if (
            $category === 'Pengembalian Pinjaman'
            &&
            $type !== 'income'
        ) {
            return redirect()
                ->route('cash.index')
                ->withInput()
                ->with(
                    'error',
                    'Pengembalian Pinjaman harus menggunakan jenis transaksi Cash Masuk.'
                );
        }


        if (
            in_array(
                $category,
                [
                    'Pinjaman Keluar',
                    'Pengembalian Pinjaman',
                ],
                true
            )
            &&
            $borrowerName === ''
        ) {
            return redirect()
                ->route('cash.index')
                ->withInput()
                ->with(
                    'error',
                    'Nama peminjam wajib diisi untuk transaksi pinjaman.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CEK CASH KELUAR
        |--------------------------------------------------------------------------
        */

        if (
            $type === 'expense'
            &&
            $amount > CashTransaction::currentBalance()
        ) {
            return redirect()
                ->route('cash.index')
                ->withInput()
                ->with(
                    'error',
                    'Permintaan cash keluar melebihi saldo kas saat ini. Saldo tersedia $'
                    . number_format(
                        CashTransaction::currentBalance(),
                        2
                    )
                    . '.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SOURCE
        |--------------------------------------------------------------------------
        */

        if (
            $category === 'Pinjaman Keluar'
        ) {
            $source =
                'loan_out';

        } elseif (
            $category === 'Pengembalian Pinjaman'
        ) {
            $source =
                'loan_repayment';

        } elseif (
            $category === 'Saldo Awal'
            &&
            $type === 'income'
        ) {
            $source =
                'opening_balance';

        } elseif (
            $type === 'income'
        ) {
            $source =
                'manual_cash_in';

        } else {
            $source =
                'manual_cash_out';
        }


        /*
        |--------------------------------------------------------------------------
        | APPROVAL
        |--------------------------------------------------------------------------
        */

        $needsApproval =
            $type === 'expense'
            ||
            $category === 'Pinjaman Keluar'
            ||
            $category === 'Pengembalian Pinjaman';


        $approvalStatus =
            $needsApproval
                ? 'pending'
                : 'approved';


        /*
        |--------------------------------------------------------------------------
        | KETERANGAN
        |--------------------------------------------------------------------------
        */

        $description =
            trim(
                (string) (
                    $validated['description']
                    ?? ''
                )
            );


        if (
            $description === ''
        ) {
            if (
                $category === 'Pinjaman Keluar'
            ) {
                $description =
                    'Pinjaman cash kepada '
                    . $borrowerName;

            } elseif (
                $category === 'Pengembalian Pinjaman'
            ) {
                $description =
                    'Pengembalian pinjaman dari '
                    . $borrowerName;

            } elseif (
                $type === 'income'
            ) {
                $description =
                    'Cash masuk manual';

            } else {
                $description =
                    'Cash keluar manual';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        try {
            $cashTransaction =
                CashTransaction::create([
                    'type' =>
                        $type,

                    'source' =>
                        $source,

                    'category' =>
                        $category,

                    'borrower_name' =>
                        $borrowerName !== ''
                            ? $borrowerName
                            : null,

                    'loan_reference' =>
                        $validated['loan_reference']
                        ?? null,

                    'reference_id' =>
                        null,

                    'amount' =>
                        $amount,

                    'description' =>
                        $description,

                    'approval_status' =>
                        $approvalStatus,

                    'approved_by' =>
                        $approvalStatus === 'approved'
                            ? (
                                auth()->user()?->name
                                ?? 'Administrator'
                            )
                            : null,

                    'approved_at' =>
                        $approvalStatus === 'approved'
                            ? now()
                            : null,

                    'rejection_reason' =>
                        null,

                    'transaction_date' =>
                        now()->toDateString(),

                    'created_by' =>
                        auth()->user()?->name
                        ?? 'Administrator',
                ]);

        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('cash.index')
                ->withInput()
                ->with(
                    'error',
                    'Transaksi kas gagal disimpan.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | REFRESH DATA
        |--------------------------------------------------------------------------
        */

        $cashTransaction->refresh();


        /*
        |--------------------------------------------------------------------------
        | TELEGRAM REQUEST APPROVAL
        |--------------------------------------------------------------------------
        */

        if (
            $needsApproval
        ) {
            $this->sendPendingTelegram(
                $cashTransaction
            );


            return redirect()
                ->route('cash.index')
                ->with(
                    'success',
                    'Permintaan transaksi kas berhasil dibuat dan menunggu persetujuan Admin.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CASH MASUK LANGSUNG APPROVED
        |--------------------------------------------------------------------------
        |
        | Cash Masuk manual, Modal Tambahan, Saldo Awal,
        | Penjualan Barang, Pengembalian Dana dan lain-lain
        | yang tidak membutuhkan approval akan langsung
        | menambah saldo.
        |
        | Setelah tersimpan, Telegram akan mendapatkan
        | notifikasi Cash Masuk.
        |
        */

        $this->sendApprovedIncomeTelegram(
            $cashTransaction
        );


        return redirect()
            ->route('cash.index')
            ->with(
                'success',
                'Cash masuk berhasil ditambahkan.'
            );
    }


    /**
     * Edit transaksi manual.
     */
    public function update(
        Request $request,
        CashTransaction $cashTransaction
    ) {
        if (
            !$cashTransaction->canEdit()
        ) {
            return redirect()
                ->route('cash.index')
                ->with(
                    'error',
                    'Transaksi ini sudah diproses atau merupakan transaksi otomatis sehingga tidak boleh diedit.'
                );
        }


        $validated =
            $request->validate([
                'type' => [
                    'required',
                    'in:income,expense',
                ],

                'category' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'borrower_name' => [
                    'nullable',
                    'string',
                    'max:150',
                ],

                'loan_reference' => [
                    'nullable',
                    'integer',
                ],

                'amount' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],

                'description' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ]);


        $newType =
            $validated['type'];

        $newCategory =
            $validated['category'];

        $newAmount =
            (float) $validated['amount'];

        $borrowerName =
            trim(
                (string) (
                    $validated['borrower_name']
                    ?? ''
                )
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI PINJAMAN
        |--------------------------------------------------------------------------
        */

        if (
            $newCategory === 'Pinjaman Keluar'
            &&
            $newType !== 'expense'
        ) {
            return redirect()
                ->route('cash.index')
                ->withInput()
                ->with(
                    'error',
                    'Pinjaman Keluar harus menggunakan Cash Keluar.'
                );
        }


        if (
            $newCategory === 'Pengembalian Pinjaman'
            &&
            $newType !== 'income'
        ) {
            return redirect()
                ->route('cash.index')
                ->withInput()
                ->with(
                    'error',
                    'Pengembalian Pinjaman harus menggunakan Cash Masuk.'
                );
        }


        if (
            in_array(
                $newCategory,
                [
                    'Pinjaman Keluar',
                    'Pengembalian Pinjaman',
                ],
                true
            )
            &&
            $borrowerName === ''
        ) {
            return redirect()
                ->route('cash.index')
                ->withInput()
                ->with(
                    'error',
                    'Nama peminjam wajib diisi.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CEK SALDO
        |--------------------------------------------------------------------------
        */

        if (
            $newType === 'expense'
            &&
            $newAmount > CashTransaction::currentBalance()
        ) {
            return redirect()
                ->route('cash.index')
                ->withInput()
                ->with(
                    'error',
                    'Jumlah cash keluar melebihi saldo tersedia.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SOURCE BARU
        |--------------------------------------------------------------------------
        */

        if (
            $newCategory === 'Pinjaman Keluar'
        ) {
            $newSource =
                'loan_out';

        } elseif (
            $newCategory === 'Pengembalian Pinjaman'
        ) {
            $newSource =
                'loan_repayment';

        } elseif (
            $cashTransaction->source === 'opening_balance'
            &&
            $newType === 'income'
        ) {
            $newSource =
                'opening_balance';

        } elseif (
            $newType === 'income'
        ) {
            $newSource =
                'manual_cash_in';

        } else {
            $newSource =
                'manual_cash_out';
        }


        /*
        |--------------------------------------------------------------------------
        | APPROVAL SETELAH EDIT
        |--------------------------------------------------------------------------
        */

        $needsApproval =
            $newType === 'expense'
            ||
            $newCategory === 'Pinjaman Keluar'
            ||
            $newCategory === 'Pengembalian Pinjaman';


        $newApprovalStatus =
            $needsApproval
                ? 'pending'
                : 'approved';


        /*
        |--------------------------------------------------------------------------
        | DESCRIPTION
        |--------------------------------------------------------------------------
        */

        $description =
            trim(
                (string) (
                    $validated['description']
                    ?? ''
                )
            );


        if (
            $description === ''
        ) {
            if (
                $newCategory === 'Pinjaman Keluar'
            ) {
                $description =
                    'Pinjaman cash kepada '
                    . $borrowerName;

            } elseif (
                $newCategory === 'Pengembalian Pinjaman'
            ) {
                $description =
                    'Pengembalian pinjaman dari '
                    . $borrowerName;

            } elseif (
                $newType === 'income'
            ) {
                $description =
                    'Cash masuk manual';

            } else {
                $description =
                    'Cash keluar manual';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $cashTransaction->update([
            'type' =>
                $newType,

            'source' =>
                $newSource,

            'category' =>
                $newCategory,

            'borrower_name' =>
                $borrowerName !== ''
                    ? $borrowerName
                    : null,

            'loan_reference' =>
                $validated['loan_reference']
                ?? null,

            'amount' =>
                $newAmount,

            'description' =>
                $description,

            'approval_status' =>
                $newApprovalStatus,

            'approved_by' =>
                $newApprovalStatus === 'approved'
                    ? (
                        auth()->user()?->name
                        ?? 'Administrator'
                    )
                    : null,

            'approved_at' =>
                $newApprovalStatus === 'approved'
                    ? now()
                    : null,

            'rejection_reason' =>
                null,
        ]);


        $cashTransaction->refresh();


        /*
        |--------------------------------------------------------------------------
        | TELEGRAM SETELAH EDIT
        |--------------------------------------------------------------------------
        */

        if (
            $needsApproval
        ) {
            $this->sendPendingTelegram(
                $cashTransaction
            );

        } else {
            $this->sendApprovedIncomeTelegram(
                $cashTransaction
            );
        }


        return redirect()
            ->route('cash.index')
            ->with(
                'success',
                'Transaksi cash berhasil diperbarui.'
            );
    }


    /**
     * Approve transaksi dari website.
     */
    public function approve(
        CashTransaction $cashTransaction
    ) {
        try {
            $result =
                DB::transaction(
                    function () use (
                        $cashTransaction
                    ) {
                        $transaction =
                            CashTransaction::query()
                                ->lockForUpdate()
                                ->find(
                                    $cashTransaction->id
                                );


                        if (!$transaction) {
                            return [
                                'status' =>
                                    'not_found',
                            ];
                        }


                        if (
                            $transaction->approval_status
                            !== 'pending'
                        ) {
                            return [
                                'status' =>
                                    'already_processed',
                            ];
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | CEK SALDO
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $transaction->type === 'expense'
                        ) {
                            $balance =
                                CashTransaction::currentBalance();


                            if (
                                (float) $transaction->amount
                                > $balance
                            ) {
                                return [
                                    'status' =>
                                        'insufficient_balance',

                                    'balance' =>
                                        $balance,
                                ];
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | APPROVE
                        |--------------------------------------------------------------------------
                        */

                        $transaction->approval_status =
                            'approved';

                        $transaction->approved_by =
                            auth()->user()?->name
                            ?? 'Administrator';

                        $transaction->approved_at =
                            now();

                        $transaction->rejection_reason =
                            null;

                        $transaction->save();


                        return [
                            'status' =>
                                'approved',

                            'transaction' =>
                                $transaction,
                        ];
                    }
                );


            if (
                $result['status'] === 'not_found'
            ) {
                return redirect()
                    ->route('cash.index')
                    ->with(
                        'error',
                        'Transaksi tidak ditemukan.'
                    );
            }


            if (
                $result['status'] === 'already_processed'
            ) {
                return redirect()
                    ->route('cash.index')
                    ->with(
                        'error',
                        'Transaksi ini sudah diproses sebelumnya.'
                    );
            }


            if (
                $result['status'] === 'insufficient_balance'
            ) {
                return redirect()
                    ->route('cash.index')
                    ->with(
                        'error',
                        'Transaksi tidak dapat disetujui karena saldo kas tidak mencukupi. Saldo saat ini $'
                        . number_format(
                            (float) $result['balance'],
                            2
                        )
                        . '.'
                    );
            }


            /** @var CashTransaction $transaction */
            $transaction =
                $result['transaction'];

            $transaction->refresh();


            /*
            |--------------------------------------------------------------------------
            | TELEGRAM APPROVED
            |--------------------------------------------------------------------------
            */

            $this->sendApprovedTelegram(
                $transaction
            );


            return redirect()
                ->route('cash.index')
                ->with(
                    'success',
                    'Transaksi kas berhasil disetujui.'
                );

        } catch (\Throwable $e) {
            report($e);


            return redirect()
                ->route('cash.index')
                ->with(
                    'error',
                    'Terjadi kesalahan saat menyetujui transaksi.'
                );
        }
    }


    /**
     * Reject transaksi dari website.
     */
    public function reject(
        Request $request,
        CashTransaction $cashTransaction
    ) {
        $validated =
            $request->validate([
                'rejection_reason' => [
                    'required',
                    'string',
                    'min:3',
                    'max:1000',
                ],
            ], [
                'rejection_reason.required' =>
                    'Alasan penolakan wajib diisi.',

                'rejection_reason.min' =>
                    'Alasan penolakan minimal 3 karakter.',

                'rejection_reason.max' =>
                    'Alasan penolakan maksimal 1000 karakter.',
            ]);


        try {
            $result =
                DB::transaction(
                    function () use (
                        $cashTransaction,
                        $validated
                    ) {
                        $transaction =
                            CashTransaction::query()
                                ->lockForUpdate()
                                ->find(
                                    $cashTransaction->id
                                );


                        if (!$transaction) {
                            return [
                                'status' =>
                                    'not_found',
                            ];
                        }


                        if (
                            $transaction->approval_status
                            !== 'pending'
                        ) {
                            return [
                                'status' =>
                                    'already_processed',
                            ];
                        }


                        $transaction->approval_status =
                            'rejected';

                        $transaction->approved_by =
                            auth()->user()?->name
                            ?? 'Administrator';

                        $transaction->approved_at =
                            now();

                        $transaction->rejection_reason =
                            trim(
                                $validated[
                                    'rejection_reason'
                                ]
                            );

                        $transaction->save();


                        return [
                            'status' =>
                                'rejected',

                            'transaction' =>
                                $transaction,
                        ];
                    }
                );


            if (
                $result['status'] === 'not_found'
            ) {
                return redirect()
                    ->route('cash.index')
                    ->with(
                        'error',
                        'Transaksi tidak ditemukan.'
                    );
            }


            if (
                $result['status'] === 'already_processed'
            ) {
                return redirect()
                    ->route('cash.index')
                    ->with(
                        'error',
                        'Transaksi ini sudah diproses sebelumnya.'
                    );
            }


            /** @var CashTransaction $transaction */
            $transaction =
                $result['transaction'];

            $transaction->refresh();


            /*
            |--------------------------------------------------------------------------
            | TELEGRAM REJECTED
            |--------------------------------------------------------------------------
            */

            $this->sendRejectedTelegram(
                $transaction
            );


            return redirect()
                ->route('cash.index')
                ->with(
                    'success',
                    'Permintaan transaksi kas berhasil ditolak.'
                );

        } catch (\Throwable $e) {
            report($e);


            return redirect()
                ->route('cash.index')
                ->with(
                    'error',
                    'Terjadi kesalahan saat menolak transaksi.'
                );
        }
    }


    /**
     * Hapus transaksi manual.
     */
    public function destroy(
        CashTransaction $cashTransaction
    ) {
        if (
            !$cashTransaction->canDelete()
        ) {
            return redirect()
                ->route('cash.index')
                ->with(
                    'error',
                    'Transaksi ini tidak boleh dihapus karena sudah diproses atau merupakan transaksi otomatis.'
                );
        }


        if (
            $cashTransaction->source === 'opening_balance'
            &&
            $cashTransaction->type === 'income'
        ) {
            $balanceAfterDelete =
                CashTransaction::currentBalance()
                -
                (float) $cashTransaction->amount;


            if (
                $balanceAfterDelete < 0
            ) {
                return redirect()
                    ->route('cash.index')
                    ->with(
                        'error',
                        'Saldo awal tidak dapat dihapus karena akan membuat saldo kas menjadi negatif.'
                    );
            }
        }


        $cashTransaction->delete();


        return redirect()
            ->route('cash.index')
            ->with(
                'success',
                'Transaksi cash berhasil dihapus.'
            );
    }


    /**
     * Telegram request pending.
     */
    private function sendPendingTelegram(
        CashTransaction $transaction
    ): void {
        try {
            $telegram =
                app(
                    StockTelegramService::class
                );


            $amount =
                number_format(
                    (float) $transaction->amount,
                    2
                );


            $category =
                e(
                    $transaction->category
                    ?: '-'
                );


            $description =
                e(
                    $transaction->description
                    ?: '-'
                );


            $createdBy =
                e(
                    $transaction->created_by
                    ?: '-'
                );


            $borrower =
                e(
                    $transaction->borrower_name
                    ?: '-'
                );


            if (
                $transaction->category === 'Pinjaman Keluar'
            ) {
                $title =
                    '🟠 PERMINTAAN PINJAMAN KAS';

            } elseif (
                $transaction->category === 'Pengembalian Pinjaman'
            ) {
                $title =
                    '🔵 PENGEMBALIAN PINJAMAN';

            } else {
                $title =
                    '💸 PERMINTAAN CASH KELUAR';
            }


            $message =
                "<b>{$title}</b>\n\n"
                . "<b>ID:</b> #{$transaction->id}\n"
                . "<b>Kategori:</b> {$category}\n"
                . "<b>Jumlah:</b> \${$amount}\n";


            if (
                $transaction->isLoanTransaction()
            ) {
                $message .=
                    "<b>Peminjam:</b> {$borrower}\n";
            }


            $message .=
                "<b>Keterangan:</b> {$description}\n"
                . "<b>Dibuat Oleh:</b> {$createdBy}\n\n"
                . "<b>Status:</b> ⏳ MENUNGGU PERSETUJUAN";


            $telegram
                ->sendCashApprovalRequest(
                    $transaction->id,
                    $message
                );

        } catch (\Throwable $e) {
            Log::error(
                'Gagal mengirim Telegram pending Kas Inventory.',
                [
                    'cash_transaction_id' =>
                        $transaction->id,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            report($e);
        }
    }


    /**
     * Telegram transaksi approved.
     */
    private function sendApprovedTelegram(
        CashTransaction $transaction
    ): void {
        try {
            $telegram =
                app(
                    StockTelegramService::class
                );


            $amount =
                number_format(
                    (float) $transaction->amount,
                    2
                );


            $balance =
                number_format(
                    CashTransaction::currentBalance(),
                    2
                );


            $category =
                e(
                    $transaction->category
                    ?: '-'
                );


            $description =
                e(
                    $transaction->description
                    ?: '-'
                );


            $approvedBy =
                e(
                    $transaction->approved_by
                    ?: 'Administrator'
                );


            $borrower =
                e(
                    $transaction->borrower_name
                    ?: '-'
                );


            if (
                $transaction->category === 'Pinjaman Keluar'
            ) {
                $title =
                    '✅ PINJAMAN KAS DISETUJUI';

            } elseif (
                $transaction->category === 'Pengembalian Pinjaman'
            ) {
                $title =
                    '✅ PENGEMBALIAN PINJAMAN DISETUJUI';

            } elseif (
                $transaction->type === 'expense'
            ) {
                $title =
                    '✅ CASH KELUAR DISETUJUI';

            } else {
                $title =
                    '✅ CASH MASUK DISETUJUI';
            }


            $message =
                "<b>{$title}</b>\n\n"
                . "<b>ID:</b> #{$transaction->id}\n"
                . "<b>Kategori:</b> {$category}\n"
                . "<b>Jumlah:</b> \${$amount}\n";


            if (
                $transaction->isLoanTransaction()
            ) {
                $message .=
                    "<b>Peminjam:</b> {$borrower}\n";
            }


            $message .=
                "<b>Keterangan:</b> {$description}\n"
                . "<b>Disetujui Oleh:</b> {$approvedBy}\n"
                . "<b>Saldo Kas Sekarang:</b> \${$balance}\n\n"
                . "✅ Transaksi berhasil diproses.";


            $telegram->send(
                $message
            );

        } catch (\Throwable $e) {
            Log::error(
                'Gagal mengirim Telegram approved Kas Inventory.',
                [
                    'cash_transaction_id' =>
                        $transaction->id,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            report($e);
        }
    }


    /**
     * Telegram transaksi rejected.
     */
    private function sendRejectedTelegram(
        CashTransaction $transaction
    ): void {
        try {
            $telegram =
                app(
                    StockTelegramService::class
                );


            $amount =
                number_format(
                    (float) $transaction->amount,
                    2
                );


            $category =
                e(
                    $transaction->category
                    ?: '-'
                );


            $rejectedBy =
                e(
                    $transaction->approved_by
                    ?: 'Administrator'
                );


            $reason =
                e(
                    $transaction->rejection_reason
                    ?: '-'
                );


            $borrower =
                e(
                    $transaction->borrower_name
                    ?: '-'
                );


            $message =
                "<b>❌ PERMINTAAN KAS DITOLAK</b>\n\n"
                . "<b>ID:</b> #{$transaction->id}\n"
                . "<b>Kategori:</b> {$category}\n"
                . "<b>Jumlah:</b> \${$amount}\n";


            if (
                $transaction->isLoanTransaction()
            ) {
                $message .=
                    "<b>Peminjam:</b> {$borrower}\n";
            }


            $message .=
                "<b>Ditolak Oleh:</b> {$rejectedBy}\n"
                . "<b>Alasan:</b> {$reason}\n\n"
                . "Saldo kas tidak berubah.";


            $telegram->send(
                $message
            );

        } catch (\Throwable $e) {
            Log::error(
                'Gagal mengirim Telegram rejected Kas Inventory.',
                [
                    'cash_transaction_id' =>
                        $transaction->id,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            report($e);
        }
    }


    /**
     * Telegram Cash Masuk langsung approved.
     */
    private function sendApprovedIncomeTelegram(
        CashTransaction $transaction
    ): void {
        try {
            $telegram =
                app(
                    StockTelegramService::class
                );


            $amount =
                number_format(
                    (float) $transaction->amount,
                    2
                );


            $balance =
                number_format(
                    CashTransaction::currentBalance(),
                    2
                );


            $category =
                e(
                    $transaction->category
                    ?: '-'
                );


            $description =
                e(
                    $transaction->description
                    ?: '-'
                );


            $createdBy =
                e(
                    $transaction->created_by
                    ?: 'Administrator'
                );


            $date =
                optional(
                    $transaction->transaction_date
                )->format('d-m-Y')
                ?: now()->format('d-m-Y');


            /*
            |--------------------------------------------------------------------------
            | TITLE
            |--------------------------------------------------------------------------
            */

            if (
                $transaction->source === 'opening_balance'
            ) {
                $title =
                    '💰 SALDO AWAL KAS';

            } elseif (
                $transaction->category === 'Modal Tambahan'
            ) {
                $title =
                    '💵 MODAL TAMBAHAN';

            } elseif (
                $transaction->category === 'Penjualan Barang'
            ) {
                $title =
                    '💰 CASH PENJUALAN MASUK';

            } else {
                $title =
                    '💰 CASH MASUK';
            }


            /*
            |--------------------------------------------------------------------------
            | MESSAGE
            |--------------------------------------------------------------------------
            */

            $message =
                "<b>{$title}</b>\n\n"
                . "<b>ID:</b> #{$transaction->id}\n"
                . "<b>Tanggal:</b> {$date}\n"
                . "<b>Kategori:</b> {$category}\n"
                . "<b>Jumlah:</b> +\${$amount}\n"
                . "<b>Keterangan:</b> {$description}\n"
                . "<b>Dibuat Oleh:</b> {$createdBy}\n"
                . "<b>Status:</b> ✅ DISETUJUI\n\n"
                . "<b>Saldo Kas Sekarang:</b> \${$balance}";


            /*
            |--------------------------------------------------------------------------
            | SEND
            |--------------------------------------------------------------------------
            */

            $telegram->send(
                $message
            );


            Log::info(
                'Telegram Cash Masuk Kas Inventory dikirim.',
                [
                    'cash_transaction_id' =>
                        $transaction->id,

                    'category' =>
                        $transaction->category,

                    'amount' =>
                        (float) $transaction->amount,

                    'balance' =>
                        CashTransaction::currentBalance(),
                ]
            );

        } catch (\Throwable $e) {
            Log::error(
                'Gagal mengirim Telegram Cash Masuk Kas Inventory.',
                [
                    'cash_transaction_id' =>
                        $transaction->id,

                    'category' =>
                        $transaction->category,

                    'amount' =>
                        (float) $transaction->amount,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            report($e);
        }
    }
}