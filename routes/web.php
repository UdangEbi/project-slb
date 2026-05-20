<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\Kasir\TransaksiController;
use App\Http\Controllers\Kasir\StokController;
use App\Http\Controllers\Kasir\RekapitulasiKasirController;
use App\Http\Controllers\Kasir\AuthKasirController;


Route::get('/kasir', function () {
    return redirect()->route('kasir.login');
});

Route::get('/login', [AuthKasirController::class, 'login'])
    ->name('login');

Route::post('/login', [AuthKasirController::class, 'processLogin'])
    ->name('login.process');

Route::get('/logout', [AuthKasirController::class, 'logout'])
    ->name('logout');

Route::post('/kasir/modal-awal', [AuthKasirController::class, 'storeModalAwal'])
    ->name('kasir.modal-awal.store');

Route::prefix('kasir')->group(function () {

    Route::get('/transaksi', [TransaksiController::class, 'index'])
        ->name('kasir.transaksi');

    Route::get('/stok', [StokController::class, 'index'])
        ->name('kasir.stok');

    Route::get('/rekapitulasikasir', [RekapitulasiKasirController::class, 'index'])
        ->name('kasir.rekapitulasi');
});

use App\Http\Controllers\Admin\RekapitulasiController;
use App\Http\Controllers\Admin\DashboardController;


Route::prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/rekapitulasi', [RekapitulasiController::class, 'index'])
        ->name('admin.rekapitulasi');
});
