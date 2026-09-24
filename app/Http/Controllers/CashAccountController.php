<?php

namespace App\Http\Controllers;

use App\Models\CashAccount;
use App\Models\CashMovement;
use App\Services\CashTelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CashAccountController extends Controller
{
    /**
     * Halaman utama Kas Admin / Bank / Mosan.
     */
    public function index()
    {
        $adminAccount = CashAccount::firstOrCreate(
            [
                'account_type' => CashAccount::TYPE_ADMIN,
            ],
            [
                'balance' => 0,
                'bank_name' => null,
                'notes' => 'Saldo uang yang sedang berada di Admin.',
            ]
        );

        $bankAccount = CashAccount::firstOrCreate(
            [
                'account_type' => CashAccount::TYPE_BANK,
            ],
            [
                'balance' => 0,
                'bank_name' => null,
                'notes' => 'Saldo uang yang sedang berada di Bank.',
            ]
        );

        $mosanAccount = CashAccount::firstOrCreate(
            [
                'account_type' => CashAccount::TYPE_MOSAN,
            ],
            [
                'balance' => 0,
                'bank_name' => null,
                'notes' => 'Saldo pembayaran digital yang berada di aplikasi Mosan.',
            ]
        );

        $movements = CashMovement::query()
            ->with('creator')
            ->latest()
            ->paginate(20);

        $totalMoney =
            (float) $adminAccount->balance
            + (float) $bankAccount->balance
            + (float) $mosanAccount->balance;

        return view(
            'cash-accounts.index',
            compact(
                'adminAccount',
                'bankAccount',
                'mosanAccount',
                'movements',
                'totalMoney'
            )
        );
    }

    /**
     * Tambah uang ke saldo Admin.
     */
    public function addAdmin(Request $request)
    {
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'amount.required' =>
                'Jumlah uang wajib diisi.',

            'amount.numeric' =>
                'Jumlah uang harus berupa angka.',

            'amount.min' =>
                'Jumlah uang minimal $0.01.',
        ]);

        $amount = (float) $validated['amount'];

        DB::transaction(
            function () use (
                $amount,
                $validated,
                $request
            ) {
                $adminAccount =
                    CashAccount::query()
                        ->where(
                            'account_type',
                            CashAccount::TYPE_ADMIN
                        )
                        ->lockForUpdate()
                        ->firstOrCreate(
                            [
                                'account_type' =>
                                    CashAccount::TYPE_ADMIN,
                            ],
                            [
                                'balance' => 0,
                            ]
                        );

                $adminAccount->balance =
                    (float) $adminAccount->balance
                    + $amount;

                $adminAccount->save();

                CashMovement::create([
                    'movement_type' =>
                        CashMovement::TYPE_ADD_ADMIN,

                    'amount' =>
                        $amount,

                    'from_account' =>
                        null,

                    'to_account' =>
                        CashAccount::TYPE_ADMIN,

                    'bank_name' =>
                        null,

                    'proof' =>
                        null,

                    'notes' =>
                        $validated['notes']
                        ?? 'Tambah uang ke Admin.',

                    'created_by' =>
                        $request->user()?->id,
                ]);
            }
        );

        return redirect()
            ->route('cash-accounts.index')
            ->with(
                'success',
                'Uang di Admin berhasil ditambah sebesar $'
                . number_format(
                    $amount,
                    2
                )
                . '.'
            );
    }

    /**
     * Tambah uang langsung ke saldo Bank.
     */
    public function addBank(Request $request)
    {
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'bank_name' => [
                'required',
                'string',
                'max:100',
            ],

            'proof' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'amount.required' =>
                'Jumlah uang wajib diisi.',

            'bank_name.required' =>
                'Nama Bank wajib diisi.',

            'proof.image' =>
                'Bukti harus berupa gambar.',

            'proof.mimes' =>
                'Bukti harus JPG, JPEG, PNG atau WEBP.',

            'proof.max' =>
                'Ukuran bukti maksimal 5 MB.',
        ]);

        $amount = (float) $validated['amount'];

        $proofPath = null;

        if ($request->hasFile('proof')) {
            $proofPath =
                $request
                    ->file('proof')
                    ->store(
                        'cash-account-proofs',
                        'public'
                    );
        }

        try {
            DB::transaction(
                function () use (
                    $amount,
                    $validated,
                    $request,
                    $proofPath
                ) {
                    $bankAccount =
                        CashAccount::query()
                            ->where(
                                'account_type',
                                CashAccount::TYPE_BANK
                            )
                            ->lockForUpdate()
                            ->firstOrCreate(
                                [
                                    'account_type' =>
                                        CashAccount::TYPE_BANK,
                                ],
                                [
                                    'balance' => 0,
                                ]
                            );

                    $bankAccount->balance =
                        (float) $bankAccount->balance
                        + $amount;

                    $bankAccount->bank_name =
                        $validated['bank_name'];

                    $bankAccount->save();

                    CashMovement::create([
                        'movement_type' =>
                            CashMovement::TYPE_ADD_BANK,

                        'amount' =>
                            $amount,

                        'from_account' =>
                            null,

                        'to_account' =>
                            CashAccount::TYPE_BANK,

                        'bank_name' =>
                            $validated['bank_name'],

                        'proof' =>
                            $proofPath,

                        'notes' =>
                            $validated['notes']
                            ?? 'Tambah uang langsung ke Bank.',

                        'created_by' =>
                            $request->user()?->id,
                    ]);
                }
            );
        } catch (\Throwable $exception) {
            if ($proofPath) {
                Storage::disk('public')
                    ->delete(
                        $proofPath
                    );
            }

            throw $exception;
        }

        return redirect()
            ->route('cash-accounts.index')
            ->with(
                'success',
                'Uang di Bank berhasil ditambah sebesar $'
                . number_format(
                    $amount,
                    2
                )
                . '.'
            );
    }

    /**
     * Tambah saldo Mosan.
     */
    public function addMosan(Request $request)
    {
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'amount.required' =>
                'Jumlah saldo Mosan wajib diisi.',

            'amount.numeric' =>
                'Jumlah saldo Mosan harus berupa angka.',

            'amount.min' =>
                'Jumlah saldo Mosan minimal $0.01.',
        ]);

        $amount = (float) $validated['amount'];

        DB::transaction(
            function () use (
                $amount,
                $validated,
                $request
            ) {
                $mosanAccount =
                    CashAccount::query()
                        ->where(
                            'account_type',
                            CashAccount::TYPE_MOSAN
                        )
                        ->lockForUpdate()
                        ->firstOrCreate(
                            [
                                'account_type' =>
                                    CashAccount::TYPE_MOSAN,
                            ],
                            [
                                'balance' => 0,
                                'bank_name' => null,
                                'notes' =>
                                    'Saldo pembayaran digital yang berada di aplikasi Mosan.',
                            ]
                        );

                $mosanAccount->balance =
                    (float) $mosanAccount->balance
                    + $amount;

                $mosanAccount->save();

                CashMovement::create([
                    'movement_type' =>
                        CashMovement::TYPE_ADD_MOSAN,

                    'amount' =>
                        $amount,

                    'from_account' =>
                        null,

                    'to_account' =>
                        CashAccount::TYPE_MOSAN,

                    'bank_name' =>
                        null,

                    'proof' =>
                        null,

                    'notes' =>
                        $validated['notes']
                        ?? 'Tambah saldo Mosan.',

                    'created_by' =>
                        $request->user()?->id,
                ]);
            }
        );

        return redirect()
            ->route('cash-accounts.index')
            ->with(
                'success',
                'Saldo Mosan berhasil ditambah sebesar $'
                . number_format(
                    $amount,
                    2
                )
                . '.'
            );
    }

    /**
     * Setor uang Cash dari Admin ke Bank.
     */
    public function transferToBank(Request $request)
    {
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'bank_name' => [
                'required',
                'string',
                'max:100',
            ],

            'proof' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'amount.required' =>
                'Jumlah setoran wajib diisi.',

            'amount.numeric' =>
                'Jumlah setoran harus berupa angka.',

            'amount.min' =>
                'Jumlah setoran minimal $0.01.',

            'bank_name.required' =>
                'Nama Bank wajib diisi.',

            'proof.required' =>
                'Bukti setoran Bank wajib diunggah.',

            'proof.image' =>
                'Bukti setoran harus berupa gambar.',

            'proof.mimes' =>
                'Bukti setoran harus JPG, JPEG, PNG atau WEBP.',

            'proof.max' =>
                'Ukuran bukti maksimal 5 MB.',
        ]);

        $amount =
            (float) $validated['amount'];

        $proofPath =
            $request
                ->file('proof')
                ->store(
                    'cash-account-proofs',
                    'public'
                );

        try {
            $result =
                DB::transaction(
                    function () use (
                        $amount,
                        $validated,
                        $request,
                        $proofPath
                    ) {
                        $adminAccount =
                            CashAccount::query()
                                ->where(
                                    'account_type',
                                    CashAccount::TYPE_ADMIN
                                )
                                ->lockForUpdate()
                                ->firstOrCreate(
                                    [
                                        'account_type' =>
                                            CashAccount::TYPE_ADMIN,
                                    ],
                                    [
                                        'balance' => 0,
                                    ]
                                );

                        $bankAccount =
                            CashAccount::query()
                                ->where(
                                    'account_type',
                                    CashAccount::TYPE_BANK
                                )
                                ->lockForUpdate()
                                ->firstOrCreate(
                                    [
                                        'account_type' =>
                                            CashAccount::TYPE_BANK,
                                    ],
                                    [
                                        'balance' => 0,
                                    ]
                                );

                        $adminBalance =
                            (float) $adminAccount->balance;

                        if ($amount > $adminBalance) {
                            throw new \RuntimeException(
                                'Saldo uang di Admin tidak cukup.'
                            );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | ADMIN BERKURANG
                        |--------------------------------------------------------------------------
                        */

                        $adminAccount->balance =
                            $adminBalance
                            - $amount;

                        $adminAccount->save();

                        /*
                        |--------------------------------------------------------------------------
                        | BANK BERTAMBAH
                        |--------------------------------------------------------------------------
                        */

                        $bankAccount->balance =
                            (float) $bankAccount->balance
                            + $amount;

                        $bankAccount->bank_name =
                            $validated['bank_name'];

                        $bankAccount->save();

                        /*
                        |--------------------------------------------------------------------------
                        | HISTORY
                        |--------------------------------------------------------------------------
                        */

                        $movement =
                            CashMovement::create([
                                'movement_type' =>
                                    CashMovement::TYPE_TRANSFER_ADMIN_TO_BANK,

                                'amount' =>
                                    $amount,

                                'from_account' =>
                                    CashAccount::TYPE_ADMIN,

                                'to_account' =>
                                    CashAccount::TYPE_BANK,

                                'bank_name' =>
                                    $validated['bank_name'],

                                'proof' =>
                                    $proofPath,

                                'notes' =>
                                    $validated['notes']
                                    ?? 'Setoran Cash Admin ke Bank.',

                                'created_by' =>
                                    $request->user()?->id,
                            ]);

                        return [
                            'movement_id' =>
                                $movement->id,

                            'admin_balance' =>
                                (float) $adminAccount->balance,

                            'bank_balance' =>
                                (float) $bankAccount->balance,
                        ];
                    }
                );

        } catch (\Throwable $exception) {
            Storage::disk('public')
                ->delete(
                    $proofPath
                );

            if (
                $exception instanceof \RuntimeException
            ) {
                return redirect()
                    ->route('cash-accounts.index')
                    ->with(
                        'error',
                        $exception->getMessage()
                    );
            }

            throw $exception;
        }

        /*
        |--------------------------------------------------------------------------
        | TELEGRAM SETELAH DATABASE BERHASIL
        |--------------------------------------------------------------------------
        */

        $this->sendTransferTelegram(
            fromLabel: 'Cash Admin',
            toLabel: 'Bank',
            amount: $amount,
            bankName: $validated['bank_name'],
            fromBalance: $result['admin_balance'],
            bankBalance: $result['bank_balance'],
            notes: $validated['notes']
                ?? 'Setoran Cash Admin ke Bank.',
            movementId: $result['movement_id']
        );

        return redirect()
            ->route('cash-accounts.index')
            ->with(
                'success',
                'Setoran $'
                . number_format(
                    $amount,
                    2
                )
                . ' ke '
                . $validated['bank_name']
                . ' berhasil. Uang di Admin otomatis berkurang dan Uang di Bank bertambah.'
            );
    }

    /**
     * Transfer saldo Mosan ke Bank.
     */
    public function transferMosanToBank(Request $request)
    {
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'bank_name' => [
                'required',
                'string',
                'max:100',
            ],

            'proof' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'amount.required' =>
                'Jumlah transfer wajib diisi.',

            'amount.numeric' =>
                'Jumlah transfer harus berupa angka.',

            'amount.min' =>
                'Jumlah transfer minimal $0.01.',

            'bank_name.required' =>
                'Nama Bank wajib diisi.',

            'proof.image' =>
                'Bukti transfer harus berupa gambar.',

            'proof.mimes' =>
                'Bukti transfer harus JPG, JPEG, PNG atau WEBP.',

            'proof.max' =>
                'Ukuran bukti maksimal 5 MB.',
        ]);

        $amount =
            (float) $validated['amount'];

        $proofPath =
            null;

        if ($request->hasFile('proof')) {
            $proofPath =
                $request
                    ->file('proof')
                    ->store(
                        'cash-account-proofs',
                        'public'
                    );
        }

        try {
            $result =
                DB::transaction(
                    function () use (
                        $amount,
                        $validated,
                        $request,
                        $proofPath
                    ) {
                        $mosanAccount =
                            CashAccount::query()
                                ->where(
                                    'account_type',
                                    CashAccount::TYPE_MOSAN
                                )
                                ->lockForUpdate()
                                ->firstOrCreate(
                                    [
                                        'account_type' =>
                                            CashAccount::TYPE_MOSAN,
                                    ],
                                    [
                                        'balance' => 0,
                                    ]
                                );

                        $bankAccount =
                            CashAccount::query()
                                ->where(
                                    'account_type',
                                    CashAccount::TYPE_BANK
                                )
                                ->lockForUpdate()
                                ->firstOrCreate(
                                    [
                                        'account_type' =>
                                            CashAccount::TYPE_BANK,
                                    ],
                                    [
                                        'balance' => 0,
                                    ]
                                );

                        $mosanBalance =
                            (float) $mosanAccount->balance;

                        if ($amount > $mosanBalance) {
                            throw new \RuntimeException(
                                'Saldo Mosan tidak cukup.'
                            );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | MOSAN BERKURANG
                        |--------------------------------------------------------------------------
                        */

                        $mosanAccount->balance =
                            $mosanBalance
                            - $amount;

                        $mosanAccount->save();

                        /*
                        |--------------------------------------------------------------------------
                        | BANK BERTAMBAH
                        |--------------------------------------------------------------------------
                        */

                        $bankAccount->balance =
                            (float) $bankAccount->balance
                            + $amount;

                        $bankAccount->bank_name =
                            $validated['bank_name'];

                        $bankAccount->save();

                        /*
                        |--------------------------------------------------------------------------
                        | HISTORY
                        |--------------------------------------------------------------------------
                        */

                        $movement =
                            CashMovement::create([
                                'movement_type' =>
                                    CashMovement::TYPE_TRANSFER_MOSAN_TO_BANK,

                                'amount' =>
                                    $amount,

                                'from_account' =>
                                    CashAccount::TYPE_MOSAN,

                                'to_account' =>
                                    CashAccount::TYPE_BANK,

                                'bank_name' =>
                                    $validated['bank_name'],

                                'proof' =>
                                    $proofPath,

                                'notes' =>
                                    $validated['notes']
                                    ?? 'Transfer saldo Mosan ke Bank.',

                                'created_by' =>
                                    $request->user()?->id,
                            ]);

                        return [
                            'movement_id' =>
                                $movement->id,

                            'mosan_balance' =>
                                (float) $mosanAccount->balance,

                            'bank_balance' =>
                                (float) $bankAccount->balance,
                        ];
                    }
                );

        } catch (\Throwable $exception) {
            if ($proofPath) {
                Storage::disk('public')
                    ->delete(
                        $proofPath
                    );
            }

            if (
                $exception instanceof \RuntimeException
            ) {
                return redirect()
                    ->route('cash-accounts.index')
                    ->with(
                        'error',
                        $exception->getMessage()
                    );
            }

            throw $exception;
        }

        /*
        |--------------------------------------------------------------------------
        | TELEGRAM SETELAH DATABASE BERHASIL
        |--------------------------------------------------------------------------
        */

        $this->sendTransferTelegram(
            fromLabel: 'Mosan',
            toLabel: 'Bank',
            amount: $amount,
            bankName: $validated['bank_name'],
            fromBalance: $result['mosan_balance'],
            bankBalance: $result['bank_balance'],
            notes: $validated['notes']
                ?? 'Transfer saldo Mosan ke Bank.',
            movementId: $result['movement_id']
        );

        return redirect()
            ->route('cash-accounts.index')
            ->with(
                'success',
                'Transfer Mosan sebesar $'
                . number_format(
                    $amount,
                    2
                )
                . ' ke '
                . $validated['bank_name']
                . ' berhasil. Saldo Mosan berkurang dan saldo Bank bertambah.'
            );
    }

    /**
     * Edit langsung saldo Uang di Admin.
     */
    public function updateAdmin(Request $request)
    {
        $validated = $request->validate([
            'balance' => [
                'required',
                'numeric',
                'min:0',
            ],

            'notes' => [
                'required',
                'string',
                'max:1000',
            ],
        ], [
            'balance.required' =>
                'Saldo Admin wajib diisi.',

            'notes.required' =>
                'Alasan perubahan saldo wajib diisi.',
        ]);

        $newBalance =
            (float) $validated['balance'];

        DB::transaction(
            function () use (
                $newBalance,
                $validated,
                $request
            ) {
                $account =
                    CashAccount::query()
                        ->where(
                            'account_type',
                            CashAccount::TYPE_ADMIN
                        )
                        ->lockForUpdate()
                        ->firstOrCreate(
                            [
                                'account_type' =>
                                    CashAccount::TYPE_ADMIN,
                            ],
                            [
                                'balance' => 0,
                            ]
                        );

                $oldBalance =
                    (float) $account->balance;

                $difference =
                    $newBalance
                    - $oldBalance;

                $account->balance =
                    $newBalance;

                $account->save();

                CashMovement::create([
                    'movement_type' =>
                        CashMovement::TYPE_EDIT_ADMIN,

                    'amount' =>
                        abs($difference),

                    'from_account' =>
                        CashAccount::TYPE_ADMIN,

                    'to_account' =>
                        CashAccount::TYPE_ADMIN,

                    'bank_name' =>
                        null,

                    'proof' =>
                        null,

                    'notes' =>
                        'Saldo Admin diubah dari $'
                        . number_format(
                            $oldBalance,
                            2
                        )
                        . ' menjadi $'
                        . number_format(
                            $newBalance,
                            2
                        )
                        . '. Alasan: '
                        . $validated['notes'],

                    'created_by' =>
                        $request->user()?->id,
                ]);
            }
        );

        return redirect()
            ->route('cash-accounts.index')
            ->with(
                'success',
                'Saldo Uang di Admin berhasil diperbarui menjadi $'
                . number_format(
                    $newBalance,
                    2
                )
                . '.'
            );
    }

    /**
     * Edit langsung saldo Bank.
     */
    public function updateBank(Request $request)
    {
        $validated = $request->validate([
            'balance' => [
                'required',
                'numeric',
                'min:0',
            ],

            'bank_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'notes' => [
                'required',
                'string',
                'max:1000',
            ],
        ], [
            'balance.required' =>
                'Saldo Bank wajib diisi.',

            'notes.required' =>
                'Alasan perubahan saldo wajib diisi.',
        ]);

        $newBalance =
            (float) $validated['balance'];

        DB::transaction(
            function () use (
                $newBalance,
                $validated,
                $request
            ) {
                $account =
                    CashAccount::query()
                        ->where(
                            'account_type',
                            CashAccount::TYPE_BANK
                        )
                        ->lockForUpdate()
                        ->firstOrCreate(
                            [
                                'account_type' =>
                                    CashAccount::TYPE_BANK,
                            ],
                            [
                                'balance' => 0,
                            ]
                        );

                $oldBalance =
                    (float) $account->balance;

                $difference =
                    $newBalance
                    - $oldBalance;

                $account->balance =
                    $newBalance;

                if (
                    !empty(
                        $validated['bank_name']
                    )
                ) {
                    $account->bank_name =
                        $validated['bank_name'];
                }

                $account->save();

                CashMovement::create([
                    'movement_type' =>
                        CashMovement::TYPE_EDIT_BANK,

                    'amount' =>
                        abs($difference),

                    'from_account' =>
                        CashAccount::TYPE_BANK,

                    'to_account' =>
                        CashAccount::TYPE_BANK,

                    'bank_name' =>
                        $account->bank_name,

                    'proof' =>
                        null,

                    'notes' =>
                        'Saldo Bank diubah dari $'
                        . number_format(
                            $oldBalance,
                            2
                        )
                        . ' menjadi $'
                        . number_format(
                            $newBalance,
                            2
                        )
                        . '. Alasan: '
                        . $validated['notes'],

                    'created_by' =>
                        $request->user()?->id,
                ]);
            }
        );

        return redirect()
            ->route('cash-accounts.index')
            ->with(
                'success',
                'Saldo Uang di Bank berhasil diperbarui menjadi $'
                . number_format(
                    $newBalance,
                    2
                )
                . '.'
            );
    }

    /**
     * Edit langsung saldo Mosan.
     */
    public function updateMosan(Request $request)
    {
        $validated = $request->validate([
            'balance' => [
                'required',
                'numeric',
                'min:0',
            ],

            'notes' => [
                'required',
                'string',
                'max:1000',
            ],
        ], [
            'balance.required' =>
                'Saldo Mosan wajib diisi.',

            'notes.required' =>
                'Alasan perubahan saldo wajib diisi.',
        ]);

        $newBalance =
            (float) $validated['balance'];

        DB::transaction(
            function () use (
                $newBalance,
                $validated,
                $request
            ) {
                $account =
                    CashAccount::query()
                        ->where(
                            'account_type',
                            CashAccount::TYPE_MOSAN
                        )
                        ->lockForUpdate()
                        ->firstOrCreate(
                            [
                                'account_type' =>
                                    CashAccount::TYPE_MOSAN,
                            ],
                            [
                                'balance' => 0,
                            ]
                        );

                $oldBalance =
                    (float) $account->balance;

                $difference =
                    $newBalance
                    - $oldBalance;

                $account->balance =
                    $newBalance;

                $account->save();

                CashMovement::create([
                    'movement_type' =>
                        CashMovement::TYPE_EDIT_MOSAN,

                    'amount' =>
                        abs($difference),

                    'from_account' =>
                        CashAccount::TYPE_MOSAN,

                    'to_account' =>
                        CashAccount::TYPE_MOSAN,

                    'bank_name' =>
                        null,

                    'proof' =>
                        null,

                    'notes' =>
                        'Saldo Mosan diubah dari $'
                        . number_format(
                            $oldBalance,
                            2
                        )
                        . ' menjadi $'
                        . number_format(
                            $newBalance,
                            2
                        )
                        . '. Alasan: '
                        . $validated['notes'],

                    'created_by' =>
                        $request->user()?->id,
                ]);
            }
        );

        return redirect()
            ->route('cash-accounts.index')
            ->with(
                'success',
                'Saldo Mosan berhasil diperbarui menjadi $'
                . number_format(
                    $newBalance,
                    2
                )
                . '.'
            );
    }

    /**
     * Kirim notifikasi Telegram setelah perpindahan uang berhasil.
     *
     * Telegram sengaja dikirim setelah DB::transaction() selesai agar
     * kegagalan Telegram tidak membatalkan perubahan saldo.
     */
    private function sendTransferTelegram(
        string $fromLabel,
        string $toLabel,
        float $amount,
        string $bankName,
        float $fromBalance,
        float $bankBalance,
        ?string $notes = null,
        ?int $movementId = null
    ): void {
        try {
            $telegram =
                app(
                    CashTelegramService::class
                );

            $amountFormatted =
                number_format(
                    $amount,
                    2
                );

            $fromBalanceFormatted =
                number_format(
                    $fromBalance,
                    2
                );

            $bankBalanceFormatted =
                number_format(
                    $bankBalance,
                    2
                );

            $from =
                e($fromLabel);

            $to =
                e($toLabel);

            $bank =
                e($bankName);

            $description =
                e(
                    $notes
                    ?: '-'
                );

            $processedBy =
                e(
                    auth()->user()?->name
                    ?? 'Administrator'
                );

            $time =
                now()->format(
                    'd-m-Y H:i'
                );

            $movementText =
                $movementId
                    ? '#' . $movementId
                    : '-';

            if (
                $fromLabel === 'Mosan'
            ) {
                $title =
                    '📱➡️🏦 TRANSFER MOSAN KE BANK';

                $balanceLabel =
                    'Saldo Mosan Sekarang';
            } else {
                $title =
                    '💵➡️🏦 TRANSFER ADMIN KE BANK';

                $balanceLabel =
                    'Saldo Admin Sekarang';
            }

            $message =
                "<b>{$title}</b>\n\n"
                . "<b>Movement:</b> {$movementText}\n"
                . "<b>Jumlah:</b> \${$amountFormatted}\n"
                . "<b>Dari:</b> {$from}\n"
                . "<b>Ke:</b> {$to} - {$bank}\n\n"
                . "<b>{$balanceLabel}:</b> \${$fromBalanceFormatted}\n"
                . "<b>Saldo Bank Sekarang:</b> \${$bankBalanceFormatted}\n\n"
                . "<b>Keterangan:</b> {$description}\n"
                . "<b>Diproses Oleh:</b> {$processedBy}\n"
                . "<b>Waktu:</b> {$time}";

            $telegram->send(
                $message
            );

        } catch (\Throwable $e) {
            Log::error(
                'Gagal mengirim Telegram transfer Cash Account.',
                [
                    'from' =>
                        $fromLabel,

                    'to' =>
                        $toLabel,

                    'amount' =>
                        $amount,

                    'bank_name' =>
                        $bankName,

                    'movement_id' =>
                        $movementId,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            report($e);
        }
    }

}