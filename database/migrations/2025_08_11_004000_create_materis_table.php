<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('informasi_id');
            $table->integer('urutan')->default(0);
            $table->longText('isi');
            $table->timestamps();

            $table->foreign('informasi_id')->references('id')->on('informasis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};
