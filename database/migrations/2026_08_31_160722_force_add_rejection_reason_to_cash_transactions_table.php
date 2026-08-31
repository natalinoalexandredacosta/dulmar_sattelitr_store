<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            !Schema::hasColumn(
                'cash_transactions',
                'rejection_reason'
            )
        ) {
            Schema::table(
                'cash_transactions',
                function (Blueprint $table) {
                    $table
                        ->text('rejection_reason')
                        ->nullable()
                        ->after('approved_at');
                }
            );
        }
    }

    public function down(): void
    {
        if (
            Schema::hasColumn(
                'cash_transactions',
                'rejection_reason'
            )
        ) {
            Schema::table(
                'cash_transactions',
                function (Blueprint $table) {
                    $table->dropColumn(
                        'rejection_reason'
                    );
                }
            );
        }
    }
};