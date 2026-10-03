<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('month');

            $table->unsignedSmallInteger('year');

            $table->unsignedInteger('target_qty')->default(0);

            $table->decimal(
                'target_revenue',
                15,
                2
            )->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'product_id',
                    'month',
                    'year',
                ],
                'sales_targets_product_period_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_targets');
    }
};