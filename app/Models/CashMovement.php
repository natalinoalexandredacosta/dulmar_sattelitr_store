<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashMovement extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | TAMBAH SALDO
    |--------------------------------------------------------------------------
    */

    public const TYPE_ADD_ADMIN = 'add_admin';
    public const TYPE_ADD_BANK = 'add_bank';
    public const TYPE_ADD_MOSAN = 'add_mosan';


    /*
    |--------------------------------------------------------------------------
    | TRANSFER
    |--------------------------------------------------------------------------
    */

    public const TYPE_TRANSFER_ADMIN_TO_BANK =
        'transfer_admin_to_bank';

    public const TYPE_TRANSFER_MOSAN_TO_BANK =
        'transfer_mosan_to_bank';


    /*
    |--------------------------------------------------------------------------
    | EDIT / KOREKSI SALDO
    |--------------------------------------------------------------------------
    */

    public const TYPE_EDIT_ADMIN = 'edit_admin';
    public const TYPE_EDIT_BANK = 'edit_bank';
    public const TYPE_EDIT_MOSAN = 'edit_mosan';


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


    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MOVEMENT LABEL
    |--------------------------------------------------------------------------
    */

    public function getMovementLabelAttribute(): string
    {
        return match ($this->movement_type) {

            /*
            | Tambah saldo
            */

            self::TYPE_ADD_ADMIN =>
                'Tambah Uang Admin',

            self::TYPE_ADD_BANK =>
                'Tambah Uang Bank',

            self::TYPE_ADD_MOSAN =>
                'Tambah Saldo Mosan',


            /*
            | Transfer
            */

            self::TYPE_TRANSFER_ADMIN_TO_BANK =>
                'Setor Admin ke Bank',

            self::TYPE_TRANSFER_MOSAN_TO_BANK =>
                'Transfer Mosan ke Bank',


            /*
            | Edit saldo
            */

            self::TYPE_EDIT_ADMIN =>
                'Edit Saldo Admin',

            self::TYPE_EDIT_BANK =>
                'Edit Saldo Bank',

            self::TYPE_EDIT_MOSAN =>
                'Edit Saldo Mosan',


            /*
            | Fallback
            */

            default => ucfirst(
                str_replace(
                    '_',
                    ' ',
                    (string) $this->movement_type
                )
            ),
        };
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT AMOUNT
    |--------------------------------------------------------------------------
    */

    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format(
            (float) $this->amount,
            2
        );
    }
}