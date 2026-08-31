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
        'transaction_date',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * Total semua cash masuk.
     *
     * Hanya transaksi yang approved yang dihitung.
     */
    public static function totalIncome(): float
    {
        return (float) static::where(
            'type',
            'income'
        )
            ->where(
                'approval_status',
                'approved'
            )
            ->sum('amount');
    }

    /**
     * Total semua cash keluar.
     *
     * Hanya transaksi yang approved yang dihitung.
     */
    public static function totalExpense(): float
    {
        return (float) static::where(
            'type',
            'expense'
        )
            ->where(
                'approval_status',
                'approved'
            )
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
     * Total cash keluar yang masih pending.
     */
    public static function totalPendingExpense(): float
    {
        return (float) static::where(
            'type',
            'expense'
        )
            ->where(
                'approval_status',
                'pending'
            )
            ->sum('amount');
    }

    /**
     * Total cash masuk yang masih pending.
     *
     * Contoh:
     * pengembalian pinjaman yang belum disetujui.
     */
    public static function totalPendingIncome(): float
    {
        return (float) static::where(
            'type',
            'income'
        )
            ->where(
                'approval_status',
                'pending'
            )
            ->sum('amount');
    }

    /**
     * Cek apakah transaksi cash masuk.
     */
    public function isIncome(): bool
    {
        return
            $this->type === 'income';
    }

    /**
     * Cek apakah transaksi cash keluar.
     */
    public function isExpense(): bool
    {
        return
            $this->type === 'expense';
    }

    /**
     * Status pending.
     */
    public function isPending(): bool
    {
        return
            $this->approval_status === 'pending';
    }

    /**
     * Status approved.
     */
    public function isApproved(): bool
    {
        return
            $this->approval_status === 'approved';
    }

    /**
     * Status rejected.
     */
    public function isRejected(): bool
    {
        return
            $this->approval_status === 'rejected';
    }

    /**
     * Cek apakah transaksi merupakan pinjaman keluar.
     */
    public function isLoan(): bool
    {
        return
            $this->category === 'Pinjaman Keluar';
    }

    /**
     * Cek apakah transaksi merupakan pengembalian pinjaman.
     */
    public function isLoanRepayment(): bool
    {
        return
            $this->category === 'Pengembalian Pinjaman';
    }

    /**
     * Cek apakah transaksi berkaitan dengan pinjaman.
     */
    public function isLoanTransaction(): bool
    {
        return
            $this->isLoan()
            ||
            $this->isLoanRepayment();
    }

    /**
     * Transaksi yang dibuat manual.
     *
     * Transaksi otomatis dari penjualan / stok
     * nanti tidak boleh diedit atau dihapus langsung.
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
     * Transaksi yang masih pending boleh diedit.
     *
     * Jika sudah approved atau rejected,
     * sebaiknya tidak diedit lagi.
     */
    public function canEdit(): bool
    {
        if (!$this->isManual()) {
            return false;
        }

        if ($this->source === 'opening_balance') {
            return true;
        }

        return $this->isPending();
    }

    /**
     * Transaksi manual yang masih pending boleh dihapus.
     *
     * Opening balance tetap boleh dikoreksi.
     */
    public function canDelete(): bool
    {
        if (!$this->isManual()) {
            return false;
        }

        if ($this->source === 'opening_balance') {
            return true;
        }

        return $this->isPending();
    }
}