<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_product_carries', function (Blueprint $table) {
            $table->id();

            $table->string('team_name', 150);

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedInteger('quantity_taken')->default(0);

            $table->unsignedInteger('quantity_sold')->default(0);

            $table->unsignedInteger('quantity_returned')->default(0);

            $table->date('taken_at');

            $table->text('notes')
                ->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('team_name');
            $table->index('taken_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_product_carries');
    }
};