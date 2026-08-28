<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TvVoucherTransaction extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Status Isi Ulang
    |--------------------------------------------------------------------------
    */

    public const RECHARGE_PENDING = 'pending';
    public const RECHARGE_SUCCESS = 'success';
    public const RECHARGE_FAILED = 'failed';

    /*
    |--------------------------------------------------------------------------
    | Status Setoran Lama
    |--------------------------------------------------------------------------
    */

    public const PAYMENT_UNPAID = 'unpaid';
    public const PAYMENT_PAID = 'paid';

    /*
    |--------------------------------------------------------------------------
    | Status Pembayaran Customer
    |--------------------------------------------------------------------------
    */

    public const CUSTOMER_PAYMENT_UNPAID = 'unpaid';
    public const CUSTOMER_PAYMENT_PARTIAL = 'partial';
    public const CUSTOMER_PAYMENT_PAID = 'paid';

    /*
    |--------------------------------------------------------------------------
    | Status Setoran Petugas
    |--------------------------------------------------------------------------
    */

    public const STAFF_DEPOSIT_UNPAID = 'unpaid';
    public const STAFF_DEPOSIT_PARTIAL = 'partial';
    public const STAFF_DEPOSIT_PAID = 'paid';

    protected $fillable = [
        /*
         * Data Customer.
         *
         * customer_id tetap dipertahankan untuk kompatibilitas
         * dengan data lama. customer_name digunakan untuk input
         * nama pelanggan secara manual.
         */
        'customer_id',
        'customer_name',

        /*
         * Nama orang / petugas yang mengisi voucher.
         */
        'filled_by',

        'provider',
        'receiver_number',
        'package_name',
        'subscription_months',

        /*
         * Kolom lama tetap dipertahankan.
         */
        'reference_number',

        /*
         * Nilai transaksi.
         */
        'unit_amount',
        'quantity',
        'subtotal',
        'additional_fee',
        'discount',
        'total_amount',

        /*
         * Status proses isi ulang.
         */
        'recharge_status',

        /*
         * Status setoran lama.
         * Tetap dipertahankan agar fitur lama tidak rusak.
         */
        'payment_status',
        'paid_at',

        /*
         * Pembayaran Customer.
         */
        'customer_payment_status',
        'customer_paid_amount',
        'customer_balance',
        'customer_phone',
        'customer_address',
        'customer_paid_at',

        /*
         * Setoran Petugas.
         */
        'staff_received_amount',
        'staff_deposited_amount',
        'staff_balance',
        'staff_deposit_status',
        'staff_deposited_at',

        /*
         * Informasi transaksi lainnya.
         */
        'payment_proof',
        'transaction_date',
        'notes',
    ];

    protected $casts = [
        'unit_amount' => 'decimal:2',
        'quantity' => 'integer',
        'subscription_months' => 'integer',

        'subtotal' => 'decimal:2',
        'additional_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',

        /*
         * Pembayaran Customer.
         */
        'customer_paid_amount' => 'decimal:2',
        'customer_balance' => 'decimal:2',
        'customer_paid_at' => 'datetime',

        /*
         * Setoran Petugas.
         */
        'staff_received_amount' => 'decimal:2',
        'staff_deposited_amount' => 'decimal:2',
        'staff_balance' => 'decimal:2',
        'staff_deposited_at' => 'datetime',

        /*
         * Tanggal.
         */
        'transaction_date' => 'date',
        'paid_at' => 'datetime',
    ];

    /**
     * Pelanggan pemilik receiver.
     *
     * Relasi ini tetap dipertahankan untuk data lama yang
     * masih menggunakan customer_id.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Nama customer yang akan ditampilkan.
     *
     * Prioritas:
     * 1. customer_name manual
     * 2. nama dari relasi customer lama
     */
    public function getDisplayCustomerNameAttribute(): string
    {
        if (!empty($this->customer_name)) {
            return $this->customer_name;
        }

        return $this->customer?->customer_name
            ?? 'Tidak diketahui';
    }

    /**
     * Nomor HP customer yang akan ditampilkan.
     *
     * Prioritas:
     * 1. customer_phone pada transaksi
     * 2. nomor HP dari data customer lama
     */
    public function getDisplayCustomerPhoneAttribute(): string
    {
        if (!empty($this->customer_phone)) {
            return $this->customer_phone;
        }

        return $this->customer?->phone
            ?? '-';
    }

    /**
     * Alamat customer yang akan ditampilkan.
     *
     * Prioritas:
     * 1. customer_address pada transaksi
     * 2. alamat dari data customer lama
     */
    public function getDisplayCustomerAddressAttribute(): string
    {
        if (!empty($this->customer_address)) {
            return $this->customer_address;
        }

        return $this->customer?->address
            ?? '-';
    }

    /**
     * Label status proses isi ulang.
     */
    public function getRechargeStatusLabelAttribute(): string
    {
        return match ($this->recharge_status) {
            self::RECHARGE_SUCCESS =>
                'Berhasil',

            self::RECHARGE_FAILED =>
                'Gagal',

            default =>
                'Menunggu',
        };
    }

    /**
     * Label status setoran lama.
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            self::PAYMENT_PAID =>
                'Sudah Setor',

            default =>
                'Belum Setor',
        };
    }

    /**
     * Label status pembayaran customer.
     */
    public function getCustomerPaymentStatusLabelAttribute(): string
    {
        return match ($this->customer_payment_status) {
            self::CUSTOMER_PAYMENT_PAID =>
                'Lunas',

            self::CUSTOMER_PAYMENT_PARTIAL =>
                'Bayar Sebagian',

            default =>
                'Belum Bayar',
        };
    }

    /**
     * Label status setoran petugas.
     */
    public function getStaffDepositStatusLabelAttribute(): string
    {
        return match ($this->staff_deposit_status) {
            self::STAFF_DEPOSIT_PAID =>
                'Sudah Setor',

            self::STAFF_DEPOSIT_PARTIAL =>
                'Setor Sebagian',

            default =>
                'Belum Setor',
        };
    }

    /**
     * Customer sudah lunas.
     */
    public function getIsCustomerPaidAttribute(): bool
    {
        return $this->customer_payment_status
            === self::CUSTOMER_PAYMENT_PAID;
    }

    /**
     * Customer masih memiliki sisa pembayaran.
     */
    public function getHasCustomerBalanceAttribute(): bool
    {
        return (float) $this->customer_balance > 0;
    }

    /**
     * Petugas sudah setor semua.
     */
    public function getIsStaffDepositPaidAttribute(): bool
    {
        return $this->staff_deposit_status
            === self::STAFF_DEPOSIT_PAID;
    }

    /**
     * Petugas masih memiliki uang yang belum disetor.
     */
    public function getHasStaffBalanceAttribute(): bool
    {
        return (float) $this->staff_balance > 0;
    }

    /**
     * Label masa aktif paket.
     */
    public function getSubscriptionPeriodLabelAttribute(): string
    {
        return match ((int) $this->subscription_months) {
            1 =>
                '1 Bulan',

            3 =>
                '3 Bulan',

            6 =>
                '6 Bulan',

            12 =>
                '1 Tahun',

            default =>
                $this->subscription_months
                . ' Bulan',
        };
    }
}