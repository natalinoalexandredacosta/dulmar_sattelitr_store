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
    ];

    protected $casts = [
        'stock' => 'integer',
        'price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    /**
     * Riwayat stok masuk produk.
     */
    public function stockIns(): HasMany
    {
        return $this->hasMany(
            StockIn::class
        );
    }

    /**
     * Riwayat stok keluar produk.
     */
    public function stockOuts(): HasMany
    {
        return $this->hasMany(
            StockOut::class
        );
    }

    /**
     * Keuntungan per unit.
     */
    public function getProfitPerUnitAttribute(): float
    {
        return
            (float) $this->selling_price
            - (float) $this->purchase_price;
    }

    /**
     * Harga yang ditampilkan kepada customer.
     */
    public function getDisplayPriceAttribute(): float
    {
        return (float) (
            $this->selling_price
            ?? $this->price
            ?? 0
        );
    }

    /**
     * Produk masih tersedia.
     */
    public function getIsAvailableAttribute(): bool
    {
        return (int) $this->stock > 0;
    }

    /**
     * URL gambar produk.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        return asset(
            'storage/' . $this->image
        );
    }
}