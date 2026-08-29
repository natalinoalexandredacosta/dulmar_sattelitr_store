<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tv_voucher_transactions', function (Blueprint $table) {

            $table->string('payment_method')
                ->nullable()
                ->after('customer_payment_status');

            $table->string('bank_name')
                ->nullable()
                ->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('tv_voucher_transactions', function (Blueprint $table) {

            $table->dropColumn([
                'payment_method',
                'bank_name',
            ]);
        });
    }
};