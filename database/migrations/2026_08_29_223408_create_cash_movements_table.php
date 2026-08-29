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
        Schema::create('cash_movements', function (Blueprint $table) {
            $table->id();

            $table->string('movement_type');
            // add_admin
            // add_bank
            // transfer_admin_to_bank
            // edit_admin
            // edit_bank

            $table->decimal('amount', 15, 2);

            $table->string('from_account')->nullable();
            // admin / bank

            $table->string('to_account')->nullable();
            // admin / bank

            $table->string('bank_name')->nullable();

            $table->string('proof')->nullable();

            $table->text('notes')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index('movement_type');
            $table->index('from_account');
            $table->index('to_account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_movements');
    }
};