<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamProductCarry extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_name',
        'product_id',
        'quantity_taken',
        'quantity_sold',
        'quantity_returned',
        'taken_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quantity_taken' => 'integer',
        'quantity_sold' => 'integer',
        'quantity_returned' => 'integer',
        'taken_at' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getRemainingQuantityAttribute(): int
    {
        return max(
            0,
            (int) $this->quantity_taken
            - (int) $this->quantity_sold
            - (int) $this->quantity_returned
        );
    }

    public function isActive(): bool
    {
        return $this->remaining_quantity > 0;
    }
}