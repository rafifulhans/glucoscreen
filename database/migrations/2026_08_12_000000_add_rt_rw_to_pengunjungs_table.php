<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengunjungs', function (Blueprint $table) {
            $table->string('rt')->nullable()->after('alamat');
            $table->string('rw')->nullable()->after('rt');
        });
    }

    public function down(): void
    {
        Schema::table('pengunjungs', function (Blueprint $table) {
            $table->dropColumn(['rt', 'rw']);
        });
    }
};
