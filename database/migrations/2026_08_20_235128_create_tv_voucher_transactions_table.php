<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel transaksi TV Voucher.
     */
    public function up(): void
    {
        Schema::create(
            'tv_voucher_transactions',
            function (Blueprint $table) {
                $table->id();

                /*
                 * Pelanggan bersifat opsional.
                 * Jika pelanggan dihapus, transaksi tetap tersimpan.
                 */
                $table->foreignId('customer_id')
                    ->nullable()
                    ->constrained('customers')
                    ->nullOnDelete();

                /*
                 * Informasi provider dan receiver.
                 */
                $table->string('provider', 100);

                $table->string(
                    'receiver_number',
                    100
                );

                $table->string(
                    'package_name',
                    255
                );

                /*
                 * Nomor referensi dari aplikasi TV Voucher.
                 */
                $table->string(
                    'reference_number',
                    150
                )->nullable();

                /*
                 * Perhitungan transaksi.
                 */
                $table->decimal(
                    'unit_amount',
                    15,
                    2
                )->default(0);

                $table->unsignedInteger(
                    'quantity'
                )->default(1);

                $table->decimal(
                    'subtotal',
                    15,
                    2
                )->default(0);

                $table->decimal(
                    'additional_fee',
                    15,
                    2
                )->default(0);

                $table->decimal(
                    'discount',
                    15,
                    2
                )->default(0);

                $table->decimal(
                    'total_amount',
                    15,
                    2
                )->default(0);

                /*
                 * Status proses isi ulang:
                 * pending, success, atau failed.
                 */
                $table->string(
                    'recharge_status',
                    30
                )->default('pending');

                /*
                 * Status pembayaran pelanggan:
                 * unpaid atau paid.
                 */
                $table->string(
                    'payment_status',
                    30
                )->default('unpaid');

                /*
                 * Lokasi gambar bukti transaksi.
                 */
                $table->string(
                    'payment_proof'
                )->nullable();

                /*
                 * Tanggal isi ulang dan pembayaran.
                 */
                $table->date(
                    'transaction_date'
                );

                $table->timestamp(
                    'paid_at'
                )->nullable();

                $table->text(
                    'notes'
                )->nullable();

                $table->timestamps();

                /*
                 * Index untuk mempercepat pencarian.
                 */
                $table->index('provider');
                $table->index('receiver_number');
                $table->index('recharge_status');
                $table->index('payment_status');
                $table->index('transaction_date');
            }
        );
    }

    /**
     * Menghapus tabel jika migration dibatalkan.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'tv_voucher_transactions'
        );
    }
};