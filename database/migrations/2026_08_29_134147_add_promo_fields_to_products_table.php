<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_promo')
                ->default(false);

            $table->enum('discount_type', [
                'percent',
                'fixed',
            ])->nullable();

            $table->decimal(
                'discount_value',
                12,
                2
            )->nullable();

            $table->dateTime('promo_start')
                ->nullable();

            $table->dateTime('promo_end')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'is_promo',
                'discount_type',
                'discount_value',
                'promo_start',
                'promo_end',
            ]);
        });
    }
};