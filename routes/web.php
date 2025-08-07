<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\InformasiController;
use App\Http\Controllers\Web\PosyanduController;
use App\Http\Controllers\Web\PemimpinController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('guest')->group(function(){

    Route::get('/login', AuthController::class)->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function(){
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/informasi', InformasiController::class)->name('informasi');
    Route::post('/informasi', [InformasiController::class, 'store'])->name('informasi.store');   
    Route::patch('/informasi/update', [InformasiController::class, 'update'])->name('informasi.update');    
    Route::delete('/informasi/{id}/destroy', [InformasiController::class, 'destroy'])->name('informasi.destroy');

    Route::get('/posyandu', PosyanduController::class)->name('posyandu');
    Route::post('/posyandu', [PosyanduController::class, 'store'])->name('posyandu.store'); 
    Route::delete('/posyandu/{id}/destroy', [PosyanduController::class, 'destroy'])->name('posyandu.destroy');   
    Route::put('/posyandu/{id}/update', [PosyanduController::class, 'update'])->name('posyandu.update');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::middleware(['auth', 'admin'])->group(function(){
    Route::get('/admin/dashboard', AdminController::class)->name('admin.dashboard');
    Route::get('/pemimpin', action: [AdminController::class, 'pemimpin'])->name('admin.pemimpin');
    Route::post('/pemimpin', [AdminController::class, 'pemimpin_store'])->name('admin.pemimpin.store');
    Route::put('/pemimpin/{id}/update', [AdminController::class, 'pemimpin_update'])->name('admin.pemimpin.update');
    Route::delete('/pemimpin/{id}/destroy', [AdminController::class, 'pemimpin_destroy'])->name('admin.pemimpin.destroy');
});


Route::middleware(['auth', 'pemimpin'])->group(function(){
    Route::get('/pemimpin/dashboard', PemimpinController::class)->name('pemimpin.dashboard');
    Route::get('/kader', [PemimpinController::class, 'kader'])->name('pemimpin.kader');
    Route::post('/kader', [PemimpinController::class, 'kader_store'])->name('pemimpin.kader.store');
    Route::put('/kader/{id}/update', [PemimpinController::class, 'kader_update'])->name('pemimpin.kader.update');
    Route::delete('/kader/{id}/destroy', [PemimpinController::class, 'kader_destroy'])->name('pemimpin.kader.destroy');
    Route::get('/pengunjung', [PemimpinController::class, 'pengunjung'])->name('pemimpin.pengunjung');
});
