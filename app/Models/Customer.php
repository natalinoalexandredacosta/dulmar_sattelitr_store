<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'phone',
        'email',
        'address',
    ];

    /**
     * Transaksi stok keluar milik pelanggan.
     */
    public function stockOuts(): HasMany
    {
        return $this->hasMany(StockOut::class);
    }

    /**
     * Transaksi TV Voucher milik pelanggan.
     */
    public function tvVoucherTransactions(): HasMany
    {
        return $this->hasMany(TvVoucherTransaction::class);
    }
}