<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_id',

        'sold_by',

        'quantity',

        'unit_purchase_price',
        'unit_selling_price',

        'subtotal',
        'total_profit',

        'customer_paid_amount',
        'customer_balance',
        'customer_payment_status',
        'customer_paid_at',

        'staff_received_amount',
        'staff_deposited_amount',
        'staff_balance',
        'staff_deposit_status',
        'staff_deposited_at',

        'deposit_verified_by',

        'transaction_date',
        'notes',
    ];

    protected $casts = [
        'quantity' =>
            'integer',

        'unit_purchase_price' =>
            'decimal:2',

        'unit_selling_price' =>
            'decimal:2',

        'subtotal' =>
            'decimal:2',

        'total_profit' =>
            'decimal:2',

        'customer_paid_amount' =>
            'decimal:2',

        'customer_balance' =>
            'decimal:2',

        'customer_paid_at' =>
            'datetime',

        'staff_received_amount' =>
            'decimal:2',

        'staff_deposited_amount' =>
            'decimal:2',

        'staff_balance' =>
            'decimal:2',

        'staff_deposited_at' =>
            'datetime',

        'transaction_date' =>
            'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | RELATION PRODUCT
    |--------------------------------------------------------------------------
    */

    public function product(): BelongsTo
    {
        return $this->belongsTo(
            Product::class
        );
    }


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
}