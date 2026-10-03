<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'month',
        'year',
        'target_qty',
        'target_revenue',
        'notes',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'month' => 'integer',
        'year' => 'integer',
        'target_qty' => 'integer',
        'target_revenue' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}