<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('informasis', function (Blueprint $table) {
            $table->float('gds')->nullable()->after('kategori');
        });
    }

    public function down(): void
    {
        Schema::table('informasis', function (Blueprint $table) {
            $table->dropColumn('gds');
        });
    }
};
