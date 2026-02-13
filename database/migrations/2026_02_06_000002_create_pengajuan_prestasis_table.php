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
        Schema::create('pengajuan_prestasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('formulir_prestasi_id')->constrained('formulir_prestasis')->cascadeOnDelete();
            $table->enum('status', ['menunggu', 'revisi', 'ditolak', 'diterima'])->default('menunggu');
            $table->text('catatan_verifikator')->nullable();
            $table->foreignUuid('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('jawaban_prestasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pengajuan_prestasi_id')->constrained('pengajuan_prestasis')->cascadeOnDelete();
            $table->foreignUuid('formulir_pertanyaan_id')->constrained('formulir_pertanyaans')->cascadeOnDelete();
            $table->text('jawaban')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_prestasis');
        Schema::dropIfExists('pengajuan_prestasis');
    }
};
