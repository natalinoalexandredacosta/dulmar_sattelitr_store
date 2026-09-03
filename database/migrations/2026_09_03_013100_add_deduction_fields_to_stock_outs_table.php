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
                ->decimal('deduction_amount', 15, 2)
                ->default(0)
                ->after('total_profit');

            $table
                ->string('deduction_note', 255)
                ->nullable()
                ->after('deduction_amount');

        });
    }

    public function down(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {

            $table->dropColumn([
                'deduction_amount',
                'deduction_note',
            ]);

        });
    }
};