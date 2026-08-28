<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tv_voucher_transactions', function (Blueprint $table) {
            $table->string('customer_payment_status')
                ->default('unpaid')
                ->after('payment_status');

            $table->decimal('customer_paid_amount', 12, 2)
                ->default(0)
                ->after('customer_payment_status');

            $table->decimal('customer_balance', 12, 2)
                ->default(0)
                ->after('customer_paid_amount');

            $table->string('customer_phone')
                ->nullable()
                ->after('customer_balance');

            $table->string('customer_address')
                ->nullable()
                ->after('customer_phone');

            $table->timestamp('customer_paid_at')
                ->nullable()
                ->after('customer_address');
        });
    }

    public function down(): void
    {
        Schema::table('tv_voucher_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'customer_payment_status',
                'customer_paid_amount',
                'customer_balance',
                'customer_phone',
                'customer_address',
                'customer_paid_at',
            ]);
        });
    }
};