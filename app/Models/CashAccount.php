<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAccount extends Model
{
    use HasFactory;

    public const TYPE_ADMIN = 'admin';
    public const TYPE_BANK = 'bank';

    protected $fillable = [
        'account_type',
        'balance',
        'bank_name',
        'notes',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function getAccountLabelAttribute(): string
    {
        return match ($this->account_type) {
            self::TYPE_ADMIN => 'Uang di Admin',
            self::TYPE_BANK => 'Uang di Bank',
            default => ucfirst((string) $this->account_type),
        };
    }

    public function getFormattedBalanceAttribute(): string
    {
        return '$' . number_format(
            (float) $this->balance,
            2
        );
    }
}