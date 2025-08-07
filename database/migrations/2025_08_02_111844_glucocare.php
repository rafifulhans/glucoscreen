<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel Users (admin, pemimpin, kader)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('readable_password');
            $table->enum('role', ['admin', 'pemimpin', 'kader']);
            $table->timestamps();
        });

        // Tabel Posyandu
        Schema::create('posyandus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat');
            $table->integer('total_kader')->default(0);
            $table->integer('total_pengunjung')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });

        // Tabel Kader (optional, jika ingin terpisah dari tabel users)
        Schema::create('kaders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pemimpin_user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pemimpin_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Tabel Pengunjung (pasien)
        Schema::create('pengunjungs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nik');
            $table->string('alamat');
            $table->date('tanggal_kunjungan');
            $table->float('gds');
            $table->unsignedBigInteger('kader_id');
            $table->unsignedBigInteger('posyandu_id');
            $table->foreign('kader_id')->references('id')->on('kaders')->onDelete('cascade');
            $table->foreign('posyandu_id')->references('id')->on('posyandus')->onDelete('cascade');
            $table->timestamps();
        });

        // Tabel Informasi
        Schema::create('informasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasis');
        Schema::dropIfExists('pengunjungs');
        Schema::dropIfExists('kaders');
        Schema::dropIfExists('posyandus');
        Schema::dropIfExists('users');
    }
};
