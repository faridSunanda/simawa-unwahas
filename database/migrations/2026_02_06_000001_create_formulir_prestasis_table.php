<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Formulir Prestasi table
        Schema::create('formulir_prestasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('judul');
            $table->foreignUuid('kategori_prestasi_id')->constrained('kategori_prestasis')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Formulir Pertanyaan table
        Schema::create('formulir_pertanyaans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('formulir_prestasi_id')->constrained('formulir_prestasis')->cascadeOnDelete();
            $table->string('pertanyaan');
            $table->enum('tipe', ['text', 'dropdown', 'file'])->default('text');
            $table->json('opsi')->nullable();
            $table->boolean('wajib')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulir_pertanyaans');
        Schema::dropIfExists('formulir_prestasis');
    }
};
