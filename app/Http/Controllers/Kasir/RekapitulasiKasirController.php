<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\RekapKasir;
use App\Models\Transaksi;
use App\Models\KasKeluar;
use Illuminate\Http\Request;

class RekapitulasiKasirController extends Controller
{
    public function index()
    {
        $tanggal = today();

        $modalAwal = session('modal_awal', 0);

        $tunai = Transaksi::whereDate('tanggal', $tanggal)
            ->where('status', 'selesai')
            ->where('metode_pembayaran', 'tunai')
            ->sum('grand_total');

        $qris = Transaksi::whereDate('tanggal', $tanggal)
            ->where('status', 'selesai')
            ->where('metode_pembayaran', 'qris')
            ->sum('grand_total');

        $totalTransaksi = Transaksi::whereDate('tanggal', $tanggal)
            ->where('status', 'selesai')
            ->count();

        $totalPenerimaan = $tunai + $qris;
        $kasKeluar = KasKeluar::whereDate('tanggal', $tanggal)
            ->sum('nominal');

        $saldoAkhir = $modalAwal + $totalPenerimaan - $kasKeluar;

        return view('kasir.rekapitulasi', compact(
            'modalAwal',
            'tunai',
            'qris',
            'totalTransaksi',
            'totalPenerimaan',
            'kasKeluar',
            'saldoAkhir'
        ));
    }

    public function tutupKas(Request $request)
    {
        $request->validate([
            'uang_fisik' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $tanggal = today();

        $modalAwal = session('modal_awal', 0);

        $totalTransaksi = Transaksi::whereDate('tanggal', $tanggal)
            ->where('status', 'selesai')
            ->count();

        $totalPenjualan = Transaksi::whereDate('tanggal', $tanggal)
            ->where('status', 'selesai')
            ->sum('grand_total');

        $selisih =
            $request->uang_fisik -
            ($modalAwal + $totalPenjualan);

        RekapKasir::create([
            'user_id' => session('user_id'),
            'tanggal' => $tanggal,
            'modal_awal' => $modalAwal,
            'total_transaksi' => $totalTransaksi,
            'total_penjualan' => $totalPenjualan,
            'uang_fisik' => $request->uang_fisik,
            'selisih' => $selisih,
            'catatan' => $request->catatan,
        ]);

        session()->forget('modal_awal');

        return redirect()
            ->route('login')
            ->with('success', 'Tutup kas berhasil.');
    }
}
