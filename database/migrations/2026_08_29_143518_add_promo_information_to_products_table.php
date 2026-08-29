<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            if (!Schema::hasColumn('products', 'promo_title')) {
                $table
                    ->string('promo_title')
                    ->nullable()
                    ->after('promo_end');
            }

            if (!Schema::hasColumn('products', 'promo_description')) {
                $table
                    ->text('promo_description')
                    ->nullable()
                    ->after('promo_title');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            if (Schema::hasColumn('products', 'promo_description')) {
                $table->dropColumn('promo_description');
            }

            if (Schema::hasColumn('products', 'promo_title')) {
                $table->dropColumn('promo_title');
            }

        });
    }
};