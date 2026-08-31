<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();

            // income = uang masuk
            // expense = uang keluar
            $table->enum('type', [
                'income',
                'expense',
            ]);

            // Sumber transaksi
            $table->string('source')->nullable();

            // ID referensi dari transaksi lain
            // contoh: stock_out_id / stock_in_id
            $table->unsignedBigInteger('reference_id')->nullable();

            // Nilai transaksi
            $table->decimal('amount', 15, 2);

            // Keterangan transaksi
            $table->string('description')->nullable();

            // Tanggal transaksi kas
            $table->date('transaction_date');

            // User yang membuat / memicu transaksi
            $table->string('created_by')->nullable();

            $table->timestamps();

            $table->index('type');
            $table->index('source');
            $table->index('reference_id');
            $table->index('transaction_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};