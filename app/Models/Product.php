<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'category',
        'stock',
        'price',
        'purchase_price',
        'selling_price',
        'image',

        // Detail / spesifikasi produk
        'description',
        'brand',
        'model',
        'connectivity',
        'warranty',
    ];

    protected $casts = [
        'stock' => 'integer',
        'price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    public function stockIns(): HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    public function stockOuts(): HasMany
    {
        return $this->hasMany(StockOut::class);
    }

    public function getProfitPerUnitAttribute(): float
    {
        return (float) $this->selling_price
            - (float) $this->purchase_price;
    }
}