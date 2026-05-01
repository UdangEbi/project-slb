<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\Kasir\TransaksiController;
use App\Http\Controllers\Kasir\StokController;
use App\Http\Controllers\Kasir\RekapitulasiKasirController;

Route::get('/kasir', function () {
    return view('kasir.index');
});

Route::prefix('kasir')->group(function () {
    Route::get('/transaksi', [TransaksiController::class, 'index'])
        ->name('kasir.transaksi');
});

Route::prefix('kasir')->group(function () {
    Route::get('/rekapitulasikasir', [RekapitulasiKasirController::class, 'index'])
        ->name('kasir.rekapitulasi');
});

Route::prefix('kasir')->group(function () {
    Route::get('/stok', [StokController::class, 'index'])
        ->name('kasir.stok');
});


use App\Http\Controllers\Admin\RekapitulasiController;
use App\Http\Controllers\Admin\DashboardController;


Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');
});

Route::prefix('admin')->group(function () {
    Route::get('/rekapitulasi', [RekapitulasiController::class, 'index'])
        ->name('admin.rekapitulasi');
});
