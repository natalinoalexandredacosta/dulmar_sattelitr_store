<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->foreignId('supplier_id')
                ->nullable()
                ->after('product_id')
                ->constrained('suppliers')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->dropConstrainedForeignId('supplier_id');
        });
    }
};