<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn([
                'sertifikat_text_x',
                'sertifikat_text_y',
                'sertifikat_font_size',
                'sertifikat_text_color',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->integer('sertifikat_text_x')->default(50)->after('sertifikat_template');
            $table->integer('sertifikat_text_y')->default(55)->after('sertifikat_text_x');
            $table->integer('sertifikat_font_size')->default(48)->after('sertifikat_text_y');
            $table->string('sertifikat_text_color', 7)->default('#000000')->after('sertifikat_font_size');
        });
    }
};
