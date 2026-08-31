<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cash_transactions', function (Blueprint $table) {
            $table
                ->string('borrower_name')
                ->nullable()
                ->after('category');

            $table
                ->unsignedBigInteger('loan_reference')
                ->nullable()
                ->after('borrower_name');

            $table->index('borrower_name');
            $table->index('loan_reference');
        });
    }

    public function down(): void
    {
        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->dropIndex([
                'borrower_name',
            ]);

            $table->dropIndex([
                'loan_reference',
            ]);

            $table->dropColumn([
                'borrower_name',
                'loan_reference',
            ]);
        });
    }
};