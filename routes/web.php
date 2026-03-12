<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\UserController;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pasien
    Route::middleware('permission:lihat pasien')
         ->resource('pasien', PasienController::class);

    // Kunjungan
    Route::middleware('permission:lihat kunjungan')->group(function () {
        Route::resource('kunjungan', KunjunganController::class);
        Route::patch('kunjungan/{kunjungan}/status', [KunjunganController::class, 'updateStatus'])
             ->name('kunjungan.status');
    });

    Route::put('/kunjungan/{kunjungan}/status', [KunjunganController::class,'updateStatus'])
          ->name('kunjungan.updateStatus');

    // Rekam Medis
    Route::middleware('permission:lihat rekam medis')
         ->resource('rekam-medis', RekamMedisController::class);

    // Obat
    Route::middleware('permission:lihat obat')->group(function () {
        Route::resource('obat', ObatController::class);
        Route::post('obat/{obat}/tambah-stok', [ObatController::class, 'tambahStok'])
             ->name('obat.tambah-stok')
             ->middleware('permission:kelola obat');
    });

    // User Management
    Route::middleware('role:superadmin|admin')
         ->resource('user', UserController::class);

});

require __DIR__.'/auth.php';