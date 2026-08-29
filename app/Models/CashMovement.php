<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashMovement extends Model
{
    use HasFactory;

    public const TYPE_ADD_ADMIN = 'add_admin';
    public const TYPE_ADD_BANK = 'add_bank';
    public const TYPE_TRANSFER_ADMIN_TO_BANK = 'transfer_admin_to_bank';
    public const TYPE_EDIT_ADMIN = 'edit_admin';
    public const TYPE_EDIT_BANK = 'edit_bank';

    protected $fillable = [
        'movement_type',
        'amount',
        'from_account',
        'to_account',
        'bank_name',
        'proof',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function getMovementLabelAttribute(): string
    {
        return match ($this->movement_type) {
            self::TYPE_ADD_ADMIN => 'Tambah Uang Admin',
            self::TYPE_ADD_BANK => 'Tambah Uang Bank',
            self::TYPE_TRANSFER_ADMIN_TO_BANK => 'Setor Admin ke Bank',
            self::TYPE_EDIT_ADMIN => 'Edit Saldo Admin',
            self::TYPE_EDIT_BANK => 'Edit Saldo Bank',
            default => ucfirst(
                str_replace(
                    '_',
                    ' ',
                    (string) $this->movement_type
                )
            ),
        };
    }

    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format(
            (float) $this->amount,
            2
        );
    }
}