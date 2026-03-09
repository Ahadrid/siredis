<?php

use App\Http\Controllers\PasienController;
use App\Http\Controllers\RekamMedisController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth','role:admin'])->group(function(){

    Route::resource('pasien', PasienController::class);

});

Route::middleware(['auth','role:dokter'])->group(function(){

    Route::resource('rekam-medis', RekamMedisController::class);

});