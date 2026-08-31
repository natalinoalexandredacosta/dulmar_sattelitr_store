<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cash_transactions', function (Blueprint $table) {

            if (
                !Schema::hasColumn(
                    'cash_transactions',
                    'approval_status'
                )
            ) {
                $table
                    ->string('approval_status')
                    ->default('approved')
                    ->after('description');
            }

            if (
                !Schema::hasColumn(
                    'cash_transactions',
                    'approved_by'
                )
            ) {
                $table
                    ->string('approved_by')
                    ->nullable()
                    ->after('approval_status');
            }

            if (
                !Schema::hasColumn(
                    'cash_transactions',
                    'approved_at'
                )
            ) {
                $table
                    ->timestamp('approved_at')
                    ->nullable()
                    ->after('approved_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cash_transactions', function (Blueprint $table) {

            if (
                Schema::hasColumn(
                    'cash_transactions',
                    'approved_at'
                )
            ) {
                $table->dropColumn(
                    'approved_at'
                );
            }

            if (
                Schema::hasColumn(
                    'cash_transactions',
                    'approved_by'
                )
            ) {
                $table->dropColumn(
                    'approved_by'
                );
            }

            if (
                Schema::hasColumn(
                    'cash_transactions',
                    'approval_status'
                )
            ) {
                $table->dropColumn(
                    'approval_status'
                );
            }

        });
    }
};