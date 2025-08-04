<?php

use App\Http\Controllers\Api\PengunjungController;
use App\Http\Controllers\Api\AuthKaderController;

Route::post('/login', [AuthKaderController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthKaderController::class, 'logout']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pengunjung', [PengunjungController::class, 'index']);
    Route::post('/pengunjung', [PengunjungController::class, 'store']);
});