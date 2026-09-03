<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {

            $table
                ->decimal('customer_discount_amount', 15, 2)
                ->default(0)
                ->after('total_profit');

            $table
                ->string('customer_discount_note', 255)
                ->nullable()
                ->after('customer_discount_amount');

        });
    }

    public function down(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {

            $table->dropColumn([
                'customer_discount_amount',
                'customer_discount_note',
            ]);

        });
    }
};