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
        Schema::create('kegiatan_jawaban_pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('kegiatan_peserta_id')->constrained('kegiatan_peserta')->onDelete('cascade');
            $table->integer('pertanyaan_id');
            $table->text('jawaban')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_jawaban_pesertas');
    }
};
