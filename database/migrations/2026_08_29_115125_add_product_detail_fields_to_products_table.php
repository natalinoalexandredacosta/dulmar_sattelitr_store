<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')
                ->nullable();

            $table->string('brand')
                ->nullable();

            $table->string('model')
                ->nullable();

            $table->string('connectivity')
                ->nullable();

            $table->string('warranty')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'brand',
                'model',
                'connectivity',
                'warranty',
            ]);
        });
    }
};