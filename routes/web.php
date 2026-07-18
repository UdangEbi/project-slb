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
use App\Http\Controllers\Kasir\KasKeluarController;
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


    Route::post('/transaksi/simpan', [TransaksiController::class, 'simpanTransaksi'])
        ->name('kasir.transaksi.store');

    Route::get('/rekapitulasikasir', [RekapitulasiKasirController::class, 'index'])
        ->name('kasir.rekapitulasi');

    Route::post('/kasir/tutup-kas', [RekapitulasiKasirController::class, 'tutupKas'])
        ->name('kasir.tutup-kas');

    Route::get('/kas-keluar', [KasKeluarController::class, 'index'])
        ->name('kasir.kas-keluar');

    Route::post('/kas-keluar', [KasKeluarController::class, 'store'])
        ->name('kasir.kas-keluar.store');

    Route::put('/kas-keluar/{kasKeluar}', [KasKeluarController::class, 'update'])
        ->name('kasir.kas-keluar.update');

});

// Route::prefix('kasir/stok')->name('kasir.stok.')->group(function () {
//     Route::post('/', [StokController::class, 'store'])->name('store');
//     Route::post('/tambah', [StokController::class, 'tambahStok'])->name('tambah');
//     Route::post('/penyesuaian', [StokController::class, 'penyesuaian'])->name('penyesuaian');
//     Route::get('/riwayat', [StokController::class, 'riwayat'])->name('riwayat');
// });

Route::prefix('kasir/stok')->group(function () {

    Route::get('/', [StokController::class, 'index'])
        ->name('kasir.stok');

    Route::get('/tambah', [StokController::class, 'create'])
        ->name('kasir.stok.create');

    Route::post('/tambah', [StokController::class, 'store'])
        ->name('kasir.stok.store');

    Route::get('/{produk}/edit', [StokController::class, 'edit'])
        ->name('kasir.stok.edit');

    Route::post('/tambah-stok', [StokController::class, 'tambahStok'])
        ->name('kasir.stok.tambah');

    Route::get('/riwayat', [StokController::class, 'riwayat'])
        ->name('kasir.stok.riwayat');

    Route::get('/kode-produk/{kategori}', [StokController::class, 'getKodeProduk'])
        ->name('kasir.stok.kode');

    Route::delete('/{produk}', [StokController::class, 'destroy'])
        ->name('kasir.stok.destroy');
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
