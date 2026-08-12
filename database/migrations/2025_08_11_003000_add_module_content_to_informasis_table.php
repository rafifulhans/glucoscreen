<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('informasis', function (Blueprint $table) {
            $table->text('hasil_pemeriksaan')->nullable()->after('gds');
            $table->text('pesan_utama')->nullable()->after('hasil_pemeriksaan');
        });
    }

    public function down(): void
    {
        Schema::table('informasis', function (Blueprint $table) {
            $table->dropColumn(['hasil_pemeriksaan', 'pesan_utama']);
        });
    }
};
