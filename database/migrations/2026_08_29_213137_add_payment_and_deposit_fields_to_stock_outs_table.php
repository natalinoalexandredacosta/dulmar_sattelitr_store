<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | PETUGAS YANG MELAKUKAN PENJUALAN
            |--------------------------------------------------------------------------
            */

            $table->string('sold_by')
                ->nullable()
                ->after('customer_id');


            /*
            |--------------------------------------------------------------------------
            | PEMBAYARAN CUSTOMER
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'customer_paid_amount',
                15,
                2
            )
                ->default(0)
                ->after('subtotal');

            $table->decimal(
                'customer_balance',
                15,
                2
            )
                ->default(0)
                ->after('customer_paid_amount');

            $table->string(
                'customer_payment_status'
            )
                ->default('unpaid')
                ->after('customer_balance');

            $table->timestamp(
                'customer_paid_at'
            )
                ->nullable()
                ->after('customer_payment_status');


            /*
            |--------------------------------------------------------------------------
            | UANG YANG DIPEGANG PETUGAS
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'staff_received_amount',
                15,
                2
            )
                ->default(0)
                ->after('customer_paid_at');


            /*
            |--------------------------------------------------------------------------
            | SETORAN PETUGAS
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'staff_deposited_amount',
                15,
                2
            )
                ->default(0)
                ->after('staff_received_amount');

            $table->decimal(
                'staff_balance',
                15,
                2
            )
                ->default(0)
                ->after('staff_deposited_amount');

            $table->string(
                'staff_deposit_status'
            )
                ->default('unpaid')
                ->after('staff_balance');

            $table->timestamp(
                'staff_deposited_at'
            )
                ->nullable()
                ->after('staff_deposit_status');


            /*
            |--------------------------------------------------------------------------
            | ADMIN YANG KONFIRMASI SETORAN
            |--------------------------------------------------------------------------
            */

            $table->string(
                'deposit_verified_by'
            )
                ->nullable()
                ->after('staff_deposited_at');
        });
    }


    public function down(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {

            $table->dropColumn([
                'sold_by',

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
            ]);
        });
    }
};