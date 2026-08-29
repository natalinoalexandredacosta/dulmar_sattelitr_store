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


    /*
    |--------------------------------------------------------------------------
    | Metode Pembayaran
    |--------------------------------------------------------------------------
    */

    public const PAYMENT_METHOD_CASH = 'cash';
    public const PAYMENT_METHOD_BANK = 'bank';


    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER
        |--------------------------------------------------------------------------
        */

        'customer_id',
        'customer_name',

        /*
        |--------------------------------------------------------------------------
        | PETUGAS
        |--------------------------------------------------------------------------
        */

        'filled_by',

        /*
        |--------------------------------------------------------------------------
        | DATA TV VOUCHER
        |--------------------------------------------------------------------------
        */

        'provider',
        'receiver_number',
        'package_name',
        'subscription_months',
        'reference_number',

        /*
        |--------------------------------------------------------------------------
        | NILAI TRANSAKSI
        |--------------------------------------------------------------------------
        */

        'unit_amount',
        'quantity',
        'subtotal',
        'additional_fee',
        'discount',
        'total_amount',

        /*
        |--------------------------------------------------------------------------
        | STATUS ISI ULANG
        |--------------------------------------------------------------------------
        */

        'recharge_status',

        /*
        |--------------------------------------------------------------------------
        | STATUS SETORAN LAMA
        |--------------------------------------------------------------------------
        */

        'payment_status',
        'paid_at',

        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN CUSTOMER
        |--------------------------------------------------------------------------
        */

        'customer_payment_status',
        'customer_paid_amount',
        'customer_balance',
        'customer_phone',
        'customer_address',
        'customer_paid_at',

        /*
        |--------------------------------------------------------------------------
        | METODE PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        'payment_method',
        'bank_name',

        /*
        |--------------------------------------------------------------------------
        | SETORAN PETUGAS
        |--------------------------------------------------------------------------
        */

        'staff_received_amount',
        'staff_deposited_amount',
        'staff_balance',
        'staff_deposit_status',
        'staff_deposited_at',

        /*
        |--------------------------------------------------------------------------
        | INFORMASI LAIN
        |--------------------------------------------------------------------------
        */

        'payment_proof',
        'transaction_date',
        'notes',
    ];


    protected $casts = [

        /*
        |--------------------------------------------------------------------------
        | NILAI TRANSAKSI
        |--------------------------------------------------------------------------
        */

        'unit_amount' =>
            'decimal:2',

        'quantity' =>
            'integer',

        'subscription_months' =>
            'integer',

        'subtotal' =>
            'decimal:2',

        'additional_fee' =>
            'decimal:2',

        'discount' =>
            'decimal:2',

        'total_amount' =>
            'decimal:2',

        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN CUSTOMER
        |--------------------------------------------------------------------------
        */

        'customer_paid_amount' =>
            'decimal:2',

        'customer_balance' =>
            'decimal:2',

        'customer_paid_at' =>
            'datetime',

        /*
        |--------------------------------------------------------------------------
        | SETORAN PETUGAS
        |--------------------------------------------------------------------------
        */

        'staff_received_amount' =>
            'decimal:2',

        'staff_deposited_amount' =>
            'decimal:2',

        'staff_balance' =>
            'decimal:2',

        'staff_deposited_at' =>
            'datetime',

        /*
        |--------------------------------------------------------------------------
        | TANGGAL
        |--------------------------------------------------------------------------
        */

        'transaction_date' =>
            'date',

        'paid_at' =>
            'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | RELATION CUSTOMER
    |--------------------------------------------------------------------------
    */

    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            Customer::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DISPLAY CUSTOMER NAME
    |--------------------------------------------------------------------------
    */

    public function getDisplayCustomerNameAttribute(): string
    {
        if (
            !empty(
                $this->customer_name
            )
        ) {
            return $this->customer_name;
        }

        return
            $this->customer?->customer_name
            ?? 'Tidak diketahui';
    }


    /*
    |--------------------------------------------------------------------------
    | DISPLAY CUSTOMER PHONE
    |--------------------------------------------------------------------------
    */

    public function getDisplayCustomerPhoneAttribute(): string
    {
        if (
            !empty(
                $this->customer_phone
            )
        ) {
            return $this->customer_phone;
        }

        return
            $this->customer?->phone
            ?? '-';
    }


    /*
    |--------------------------------------------------------------------------
    | DISPLAY CUSTOMER ADDRESS
    |--------------------------------------------------------------------------
    */

    public function getDisplayCustomerAddressAttribute(): string
    {
        if (
            !empty(
                $this->customer_address
            )
        ) {
            return $this->customer_address;
        }

        return
            $this->customer?->address
            ?? '-';
    }


    /*
    |--------------------------------------------------------------------------
    | LABEL STATUS ISI ULANG
    |--------------------------------------------------------------------------
    */

    public function getRechargeStatusLabelAttribute(): string
    {
        return match (
            $this->recharge_status
        ) {
            self::RECHARGE_SUCCESS =>
                'Berhasil',

            self::RECHARGE_FAILED =>
                'Gagal',

            default =>
                'Menunggu',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | LABEL STATUS SETORAN LAMA
    |--------------------------------------------------------------------------
    */

    public function getPaymentStatusLabelAttribute(): string
    {
        return match (
            $this->payment_status
        ) {
            self::PAYMENT_PAID =>
                'Sudah Setor',

            default =>
                'Belum Setor',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | LABEL STATUS PEMBAYARAN CUSTOMER
    |--------------------------------------------------------------------------
    */

    public function getCustomerPaymentStatusLabelAttribute(): string
    {
        return match (
            $this->customer_payment_status
        ) {
            self::CUSTOMER_PAYMENT_PAID =>
                'Lunas',

            self::CUSTOMER_PAYMENT_PARTIAL =>
                'Bayar Sebagian',

            default =>
                'Belum Bayar',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | LABEL STATUS SETORAN PETUGAS
    |--------------------------------------------------------------------------
    */

    public function getStaffDepositStatusLabelAttribute(): string
    {
        /*
        |--------------------------------------------------------------------------
        | BANK
        |--------------------------------------------------------------------------
        |
        | Jika pembayaran melalui Bank, dana tidak perlu disetor petugas.
        |
        */

        if (
            $this->payment_method
            === self::PAYMENT_METHOD_BANK
        ) {
            return 'Masuk Bank';
        }

        return match (
            $this->staff_deposit_status
        ) {
            self::STAFF_DEPOSIT_PAID =>
                'Sudah Setor',

            self::STAFF_DEPOSIT_PARTIAL =>
                'Setor Sebagian',

            default =>
                'Belum Setor',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | LABEL METODE PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    public function getPaymentMethodLabelAttribute(): string
    {
        return match (
            $this->payment_method
        ) {
            self::PAYMENT_METHOD_BANK =>
                'BANK',

            self::PAYMENT_METHOD_CASH =>
                'CASH',

            default =>
                '-',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER SUDAH LUNAS
    |--------------------------------------------------------------------------
    */

    public function getIsCustomerPaidAttribute(): bool
    {
        return
            $this->customer_payment_status
            === self::CUSTOMER_PAYMENT_PAID;
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER MASIH PUNYA SISA TAGIHAN
    |--------------------------------------------------------------------------
    */

    public function getHasCustomerBalanceAttribute(): bool
    {
        return
            (float) $this->customer_balance
            > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | PEMBAYARAN CASH
    |--------------------------------------------------------------------------
    */

    public function getIsCashPaymentAttribute(): bool
    {
        return
            $this->payment_method
            === self::PAYMENT_METHOD_CASH;
    }


    /*
    |--------------------------------------------------------------------------
    | PEMBAYARAN BANK
    |--------------------------------------------------------------------------
    */

    public function getIsBankPaymentAttribute(): bool
    {
        return
            $this->payment_method
            === self::PAYMENT_METHOD_BANK;
    }


    /*
    |--------------------------------------------------------------------------
    | PETUGAS SUDAH SETOR SEMUA
    |--------------------------------------------------------------------------
    */

    public function getIsStaffDepositPaidAttribute(): bool
    {
        /*
        |--------------------------------------------------------------------------
        | BANK LANGSUNG DIANGGAP SELESAI
        |--------------------------------------------------------------------------
        */

        if (
            $this->payment_method
            === self::PAYMENT_METHOD_BANK
        ) {
            return true;
        }

        return
            $this->staff_deposit_status
            === self::STAFF_DEPOSIT_PAID;
    }


    /*
    |--------------------------------------------------------------------------
    | PETUGAS MASIH MEMEGANG CASH
    |--------------------------------------------------------------------------
    */

    public function getHasStaffBalanceAttribute(): bool
    {
        if (
            $this->payment_method
            === self::PAYMENT_METHOD_BANK
        ) {
            return false;
        }

        return
            (float) $this->staff_balance
            > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | LABEL MASA AKTIF
    |--------------------------------------------------------------------------
    */

    public function getSubscriptionPeriodLabelAttribute(): string
    {
        return match (
            (int) $this->subscription_months
        ) {
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