<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'tv_voucher_transactions',
            function (Blueprint $table) {
                if (
                    !Schema::hasColumn(
                        'tv_voucher_transactions',
                        'filled_by'
                    )
                ) {
                    $table
                        ->string('filled_by')
                        ->nullable()
                        ->after('customer_id');
                }

                if (
                    !Schema::hasColumn(
                        'tv_voucher_transactions',
                        'subscription_months'
                    )
                ) {
                    $table
                        ->unsignedTinyInteger('subscription_months')
                        ->default(1)
                        ->after('package_name');
                }
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'tv_voucher_transactions',
            function (Blueprint $table) {
                if (
                    Schema::hasColumn(
                        'tv_voucher_transactions',
                        'filled_by'
                    )
                ) {
                    $table->dropColumn('filled_by');
                }

                if (
                    Schema::hasColumn(
                        'tv_voucher_transactions',
                        'subscription_months'
                    )
                ) {
                    $table->dropColumn(
                        'subscription_months'
                    );
                }
            }
        );
    }
};