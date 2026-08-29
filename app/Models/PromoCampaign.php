<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PromoCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi campaign ke banyak product.
     */
    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'promo_campaign_product')
            ->withPivot([
                'discount_type',
                'discount_value',
            ])
            ->withTimestamps();
    }

    /**
     * Cek apakah campaign sedang aktif hari ini.
     */
    public function getCurrentlyActiveAttribute(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $today = Carbon::today();

        if ($this->start_date && $today->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $today->gt($this->end_date)) {
            return false;
        }

        return true;
    }
}