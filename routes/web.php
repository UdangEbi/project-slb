<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

Route::post('/password/reset', function () {

    $user = Auth::user();

    if (!($user instanceof User)) {
        return redirect('/login');
    }

    $user->password = Hash::make('12345678');
    $user->save();


    return back()->with('success', 'Password berhasil direset');

})->name('password.reset');

use App\Http\Controllers\Kasir\TransaksiController;
use App\Http\Controllers\Kasir\StokController;
use App\Http\Controllers\Kasir\RekapitulasiKasirController;
use App\Http\Controllers\Admin\PiutangController;
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

Route::prefix('kasir')->group(function () {
    Route::get('/stok', [StokController::class, 'index'])
        ->name('kasir.stok');
});



use App\Http\Controllers\Admin\RekapitulasiController;
use App\Http\Controllers\Admin\DashboardController;

Route::prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/rekapitulasi', [RekapitulasiController::class, 'index'])
        ->name('admin.rekapitulasi');
});

Route::get('/admin/rekapitulasi/pdf', [RekapitulasiController::class, 'downloadRekapitulasiPdf'])
    ->name('admin.rekapitulasi.pdf');

Route::prefix('admin')->group(function () {
    Route::get('/piutang', [PiutangController::class, 'index'])
        ->name('admin.piutang');
});
