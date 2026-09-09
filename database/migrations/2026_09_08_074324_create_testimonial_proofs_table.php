<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonial_proofs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('testimonial_id')
                ->constrained('testimonials')
                ->cascadeOnDelete();

            $table->enum('proof_type', [
                'chat',
                'video',
            ]);

            $table->string('file_path');

            $table->string('file_name')
                ->nullable();

            $table->string('mime_type')
                ->nullable();

            $table->unsignedBigInteger('file_size')
                ->nullable();

            $table->timestamps();

            $table->index('testimonial_id');
            $table->index('proof_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonial_proofs');
    }
};