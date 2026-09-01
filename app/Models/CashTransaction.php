<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    protected $fillable = [
        'type',
        'source',
        'category',
        'borrower_name',
        'loan_reference',
        'reference_id',
        'amount',
        'description',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'transaction_date',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'approved_at' => 'datetime',
    ];


    /**
     * Total semua cash masuk approved.
     */
    public static function totalIncome(): float
    {
        return (float) static::query()
            ->where('type', 'income')
            ->where('approval_status', 'approved')
            ->sum('amount');
    }


    /**
     * Total semua cash keluar approved.
     */
    public static function totalExpense(): float
    {
        return (float) static::query()
            ->where('type', 'expense')
            ->where('approval_status', 'approved')
            ->sum('amount');
    }


    /**
     * Saldo cash saat ini.
     *
     * Cash Masuk Approved - Cash Keluar Approved.
     */
    public static function currentBalance(): float
    {
        return
            static::totalIncome()
            -
            static::totalExpense();
    }


    /**
     * Total cash keluar pending.
     */
    public static function totalPendingExpense(): float
    {
        return (float) static::query()
            ->where('type', 'expense')
            ->where('approval_status', 'pending')
            ->sum('amount');
    }


    /**
     * Total cash masuk pending.
     */
    public static function totalPendingIncome(): float
    {
        return (float) static::query()
            ->where('type', 'income')
            ->where('approval_status', 'pending')
            ->sum('amount');
    }


    /**
     * Cash masuk.
     */
    public function isIncome(): bool
    {
        return $this->type === 'income';
    }


    /**
     * Cash keluar.
     */
    public function isExpense(): bool
    {
        return $this->type === 'expense';
    }


    /**
     * Pending.
     */
    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }


    /**
     * Approved.
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }


    /**
     * Rejected.
     */
    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }


    /**
     * Pinjaman keluar.
     */
    public function isLoan(): bool
    {
        return $this->category === 'Pinjaman Keluar';
    }


    /**
     * Pengembalian pinjaman.
     */
    public function isLoanRepayment(): bool
    {
        return $this->category === 'Pengembalian Pinjaman';
    }


    /**
     * Transaksi pinjaman.
     */
    public function isLoanTransaction(): bool
    {
        return
            $this->isLoan()
            ||
            $this->isLoanRepayment();
    }


    /**
     * Transaksi manual.
     *
     * Hanya source berikut yang dianggap dibuat manual
     * dari halaman Kas Inventory.
     */
    public function isManual(): bool
    {
        return in_array(
            $this->source,
            [
                'opening_balance',
                'manual_cash_in',
                'manual_cash_out',
                'loan_out',
                'loan_repayment',
            ],
            true
        );
    }


    /**
     * Cash masuk manual yang boleh dikoreksi.
     *
     * Contoh:
     * - Saldo Awal
     * - Modal Tambahan
     * - Pendapatan Lain
     * - Pengembalian Dana
     *
     * Transaksi otomatis dari penjualan tidak termasuk
     * karena source-nya bukan manual_cash_in.
     */
    public function isEditableManualIncome(): bool
    {
        return in_array(
            $this->source,
            [
                'opening_balance',
                'manual_cash_in',
            ],
            true
        )
        &&
        $this->type === 'income';
    }


    /**
     * Apakah transaksi boleh diedit.
     *
     * Aturan:
     *
     * 1. Transaksi otomatis tidak boleh diedit.
     *
     * 2. Opening Balance boleh diedit.
     *
     * 3. Cash Masuk manual boleh diedit
     *    meskipun sudah approved.
     *
     * 4. Cash Keluar manual / Pinjaman hanya
     *    boleh diedit selama masih pending.
     *
     * 5. Transaksi rejected tidak boleh diedit.
     */
    public function canEdit(): bool
    {
        if (!$this->isManual()) {
            return false;
        }


        if ($this->isRejected()) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | CASH MASUK MANUAL
        |--------------------------------------------------------------------------
        |
        | Cash masuk manual langsung approved.
        | Tetap boleh dikoreksi apabila terjadi salah input.
        |
        */

        if ($this->isEditableManualIncome()) {
            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | CASH KELUAR / PINJAMAN
        |--------------------------------------------------------------------------
        |
        | Hanya boleh diedit sebelum proses approval.
        |
        */

        return $this->isPending();
    }


    /**
     * Apakah transaksi boleh dihapus.
     *
     * Untuk keamanan saldo:
     *
     * - Transaksi otomatis tidak boleh dihapus.
     * - Opening balance boleh dihapus,
     *   tetapi controller tetap mengecek agar
     *   saldo tidak menjadi negatif.
     * - Cash masuk manual approved tidak kita
     *   izinkan hapus langsung.
     *   Gunakan Edit bila salah jumlah.
     * - Cash keluar/pinjaman hanya bisa dihapus
     *   selama masih pending.
     */
    public function canDelete(): bool
    {
        if (!$this->isManual()) {
            return false;
        }


        if ($this->isRejected()) {
            return false;
        }


        if (
            $this->source === 'opening_balance'
            &&
            $this->type === 'income'
        ) {
            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | CASH MASUK MANUAL APPROVED
        |--------------------------------------------------------------------------
        |
        | Jangan hapus langsung.
        | Gunakan tombol Edit untuk koreksi.
        |
        */

        if (
            $this->source === 'manual_cash_in'
            &&
            $this->type === 'income'
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | CASH KELUAR / PINJAMAN
        |--------------------------------------------------------------------------
        */

        return $this->isPending();
    }
}