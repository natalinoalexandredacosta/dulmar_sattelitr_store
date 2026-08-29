<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

        // Promo lama / product-level promo
        'is_promo',
        'discount_type',
        'discount_value',
        'promo_start',
        'promo_end',
        'promo_title',
        'promo_description',
    ];

    protected $casts = [
        'stock' => 'integer',

        'price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',

        'is_promo' => 'boolean',
        'discount_value' => 'decimal:2',

        'promo_start' => 'date',
        'promo_end' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function stockIns(): HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    public function stockOuts(): HasMany
    {
        return $this->hasMany(StockOut::class);
    }

    /**
     * Satu product dapat masuk ke banyak Promo Campaign.
     *
     * Data diskon khusus campaign disimpan pada tabel pivot:
     * promo_campaign_product
     */
    public function promoCampaigns(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                PromoCampaign::class,
                'promo_campaign_product'
            )
            ->withPivot([
                'discount_type',
                'discount_value',
            ])
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | PROFIT
    |--------------------------------------------------------------------------
    */

    public function getProfitPerUnitAttribute(): float
    {
        return (float) $this->selling_price
            - (float) $this->purchase_price;
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCT-LEVEL PROMO ACTIVE
    |--------------------------------------------------------------------------
    |
    | Ini tetap dipertahankan agar fitur promo lama tidak rusak.
    |
    */

    public function getPromoActiveAttribute(): bool
    {
        if (!$this->is_promo) {
            return false;
        }

        if (empty($this->discount_type)) {
            return false;
        }

        if ((float) $this->discount_value <= 0) {
            return false;
        }

        $today = Carbon::today();

        if (
            $this->promo_start
            && $today->lt($this->promo_start)
        ) {
            return false;
        }

        if (
            $this->promo_end
            && $today->gt($this->promo_end)
        ) {
            return false;
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCT-LEVEL PROMO PRICE
    |--------------------------------------------------------------------------
    */

    public function getPromoPriceAttribute(): float
    {
        $normalPrice =
            (float) $this->selling_price;

        if (!$this->promo_active) {
            return $normalPrice;
        }

        $discountValue =
            (float) $this->discount_value;

        if ($this->discount_type === 'percent') {
            $discountAmount =
                $normalPrice
                * ($discountValue / 100);

            return max(
                0,
                $normalPrice - $discountAmount
            );
        }

        if ($this->discount_type === 'fixed') {
            return max(
                0,
                $normalPrice - $discountValue
            );
        }

        return $normalPrice;
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCT-LEVEL DISCOUNT PERCENTAGE
    |--------------------------------------------------------------------------
    */

    public function getDiscountPercentageAttribute(): float
    {
        if (!$this->promo_active) {
            return 0;
        }

        if ($this->discount_type === 'percent') {
            return (float) $this->discount_value;
        }

        $normalPrice =
            (float) $this->selling_price;

        if ($normalPrice <= 0) {
            return 0;
        }

        $discountValue =
            (float) $this->discount_value;

        return ($discountValue / $normalPrice) * 100;
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE PROMO CAMPAIGN
    |--------------------------------------------------------------------------
    |
    | Mengambil campaign aktif yang sedang berlaku untuk product ini.
    |
    */

    public function getActivePromoCampaignAttribute(): ?PromoCampaign
    {
        $today = Carbon::today();

        return $this
            ->promoCampaigns()
            ->where('promo_campaigns.is_active', true)
            ->whereDate(
                'promo_campaigns.start_date',
                '<=',
                $today
            )
            ->whereDate(
                'promo_campaigns.end_date',
                '>=',
                $today
            )
            ->orderBy(
                'promo_campaigns.start_date',
                'desc'
            )
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | CAMPAIGN PROMO ACTIVE
    |--------------------------------------------------------------------------
    */

    public function getCampaignPromoActiveAttribute(): bool
    {
        $campaign =
            $this->active_promo_campaign;

        if (!$campaign) {
            return false;
        }

        if (empty($campaign->pivot->discount_type)) {
            return false;
        }

        if (
            (float) $campaign->pivot->discount_value
            <= 0
        ) {
            return false;
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | CAMPAIGN PROMO PRICE
    |--------------------------------------------------------------------------
    */

    public function getCampaignPromoPriceAttribute(): float
    {
        $normalPrice =
            (float) $this->selling_price;

        $campaign =
            $this->active_promo_campaign;

        if (!$campaign) {
            return $normalPrice;
        }

        $discountType =
            $campaign->pivot->discount_type;

        $discountValue =
            (float) $campaign->pivot->discount_value;

        if ($discountValue <= 0) {
            return $normalPrice;
        }

        if ($discountType === 'percent') {
            $discountAmount =
                $normalPrice
                * ($discountValue / 100);

            return max(
                0,
                $normalPrice - $discountAmount
            );
        }

        if ($discountType === 'fixed') {
            return max(
                0,
                $normalPrice - $discountValue
            );
        }

        return $normalPrice;
    }

    /*
    |--------------------------------------------------------------------------
    | CAMPAIGN DISCOUNT PERCENTAGE
    |--------------------------------------------------------------------------
    */

    public function getCampaignDiscountPercentageAttribute(): float
    {
        $campaign =
            $this->active_promo_campaign;

        if (!$campaign) {
            return 0;
        }

        $discountType =
            $campaign->pivot->discount_type;

        $discountValue =
            (float) $campaign->pivot->discount_value;

        if ($discountType === 'percent') {
            return $discountValue;
        }

        $normalPrice =
            (float) $this->selling_price;

        if ($normalPrice <= 0) {
            return 0;
        }

        if ($discountType === 'fixed') {
            return (
                $discountValue
                / $normalPrice
            ) * 100;
        }

        return 0;
    }
}