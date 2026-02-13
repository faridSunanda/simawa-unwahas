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
        // Jenis Sertifikat
        Schema::create('jenis_sertifikats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pengajuan Sertifikat
        Schema::create('pengajuan_sertifikats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('mahasiswa_id');
            $table->uuid('jenis_sertifikat_id');
            $table->string('nama_sertifikat');
            $table->string('nama_sertifikat_en')->nullable();
            $table->string('penerbit');
            $table->date('tanggal_terbit');
            $table->string('file_sertifikat')->nullable();
            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('catatan_verifikasi')->nullable();
            $table->uuid('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('jenis_sertifikat_id')->references('id')->on('jenis_sertifikats')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_sertifikats');
        Schema::dropIfExists('jenis_sertifikats');
    }
};
