<?php

use App\Http\Controllers\Api\PengunjungController;
use App\Http\Controllers\Api\AuthKaderController;
use App\Http\Controllers\Api\InformasiController;

Route::post('/login', [AuthKaderController::class, 'login'])->middleware('guest');
Route::middleware('auth:sanctum')->post('/logout', [AuthKaderController::class, 'logout']);

Route::get('/informasi', [InformasiController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pengunjung', [PengunjungController::class, 'index']);
    Route::post('/pengunjung', [PengunjungController::class, 'store']);
});