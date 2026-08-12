<?php

use App\Http\Controllers\Api\PengunjungController;
use App\Http\Controllers\Api\AuthKaderController;
use App\Http\Controllers\Api\InformasiController;
use App\Http\Controllers\Api\ModulEdukasiController;

Route::post('/login', [AuthKaderController::class, 'login'])->middleware('guest');
Route::middleware('auth:sanctum')->post('/logout', [AuthKaderController::class, 'logout']);

Route::get('/informasi', [InformasiController::class, 'index']);

// Modul edukasi bisa diakses oleh admin dan kader yang terautentikasi
Route::middleware('auth:sanctum')->group(function () {
    // Modul edukasi: mengembalikan modul per kategori (maks 1/kategori) beserta
    // materi yang terurut — sajikan materi ke-N pada slide ke-N (mobile-ready).
    Route::get('/modul', [ModulEdukasiController::class, 'index'])->name('modul.index');

    // Mobile POST GDS + gejala klasik -> kembalikan kategori yang sesuai + modulnya.
    Route::post('/modul/diagnose', [ModulEdukasiController::class, 'diagnose'])->name('modul.diagnose');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pengunjung', [PengunjungController::class, 'index']);
    Route::get('/pengunjung/riwayat', [PengunjungController::class, 'riwayat']);
    Route::post('/pengunjung', [PengunjungController::class, 'store']);
    Route::get('/pengunjung/{date}', [PengunjungController::class, 'showByDate']);
});