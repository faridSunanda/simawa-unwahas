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
        Schema::create('kegiatan_pertanyaans', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('kegiatan_id')->constrained()->cascadeOnDelete();
            $table->text('pertanyaan');
            $table->string('tipe'); // text, dropdown, file
            $table->boolean('wajib')->default(false);
            $table->json('opsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_pertanyaans');
    }
};
