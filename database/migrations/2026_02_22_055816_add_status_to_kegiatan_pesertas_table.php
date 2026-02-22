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
        Schema::table('kegiatan_peserta', function (Blueprint $table) {
            $table->enum('status', ['menunggu', 'terdaftar', 'ditolak'])->default('menunggu')->after('waktu_presensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan_peserta', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
