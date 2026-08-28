<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            $table->foreignId('customer_id')
                ->nullable()
                ->after('product_id')
                ->constrained('customers')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_id');
        });
    }
};