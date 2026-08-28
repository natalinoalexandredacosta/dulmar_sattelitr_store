<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_outs', 'unit_purchase_price')) {
                $table->decimal('unit_purchase_price', 15, 2)
                    ->default(0)
                    ->after('quantity');
            }

            if (!Schema::hasColumn('stock_outs', 'unit_selling_price')) {
                $table->decimal('unit_selling_price', 15, 2)
                    ->default(0)
                    ->after('unit_purchase_price');
            }

            if (!Schema::hasColumn('stock_outs', 'subtotal')) {
                $table->decimal('subtotal', 15, 2)
                    ->default(0)
                    ->after('unit_selling_price');
            }

            if (!Schema::hasColumn('stock_outs', 'total_profit')) {
                $table->decimal('total_profit', 15, 2)
                    ->default(0)
                    ->after('subtotal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            $columns = [
                'unit_purchase_price',
                'unit_selling_price',
                'subtotal',
                'total_profit',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('stock_outs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};