<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tv_voucher_transactions', function (Blueprint $table) {
            $table->decimal('staff_received_amount', 12, 2)
                ->default(0)
                ->after('customer_paid_at');

            $table->decimal('staff_deposited_amount', 12, 2)
                ->default(0)
                ->after('staff_received_amount');

            $table->decimal('staff_balance', 12, 2)
                ->default(0)
                ->after('staff_deposited_amount');

            $table->string('staff_deposit_status')
                ->default('unpaid')
                ->after('staff_balance');

            $table->timestamp('staff_deposited_at')
                ->nullable()
                ->after('staff_deposit_status');
        });
    }

    public function down(): void
    {
        Schema::table('tv_voucher_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'staff_received_amount',
                'staff_deposited_amount',
                'staff_balance',
                'staff_deposit_status',
                'staff_deposited_at',
            ]);
        });
    }
};