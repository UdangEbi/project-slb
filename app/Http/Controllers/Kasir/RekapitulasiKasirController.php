<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\RekapKasir;
use App\Models\Transaksi;
use App\Models\KasKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapitulasiKasirController extends Controller
{
    public function index()
    {

        $modalAwal = session('modal_awal', 0);

        $tunai = Transaksi::whereNull('id_rekap')
            ->where('status', 'selesai')
            ->where('metode_pembayaran', 'tunai')
            ->sum('grand_total');

        $qris = Transaksi::whereNull('id_rekap')
            ->where('status', 'selesai')
            ->where('metode_pembayaran', 'qris')
            ->sum('grand_total');

        $donasi = Transaksi::whereNull('id_rekap')
            ->where('status', 'selesai')
            ->sum('donasi');

        $totalTransaksi = Transaksi::whereNull('id_rekap')
            ->where('status', 'selesai')
            ->count();

        $totalPenerimaan = $tunai + $qris;
        $kasKeluar = KasKeluar::whereNull('id_rekap')
            ->sum('nominal');

        $saldoAkhir = $modalAwal + $totalPenerimaan + $donasi  - $kasKeluar;

        return view('kasir.rekapitulasi', compact(
            'modalAwal',
            'tunai',
            'qris',
            'donasi',
            'totalTransaksi',
            'totalPenerimaan',
            'kasKeluar',
            'saldoAkhir'
        ));
    }

    public function tutupKas(Request $request)
    {
        $request->merge([
            'uang_fisik' => str_replace('.', '', $request->uang_fisik),
        ]);

        $request->validate([
            'uang_fisik' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $tanggal = today();

        $modalAwal = session('modal_awal', 0);

        $totalTransaksi = Transaksi::whereNull('id_rekap')
            ->where('status', 'selesai')
            ->count();

        $totalPenjualan = Transaksi::whereNull('id_rekap')
            ->where('status', 'selesai')
            ->sum('grand_total');

        $totalDonasi = Transaksi::whereNull('id_rekap')
            ->where('status', 'selesai')
            ->sum('donasi');

        $totalKasKeluar = KasKeluar::whereNull('id_rekap')
            ->sum('nominal');

        $saldoAkhir = $modalAwal + $totalPenjualan + $totalDonasi - $totalKasKeluar;

        $selisih = $request->uang_fisik - $saldoAkhir;

        DB::transaction(function () use (
            $tanggal,
            $modalAwal,
            $totalTransaksi,
            $totalPenjualan,
            $totalKasKeluar,
            $saldoAkhir,
            $selisih,
            $request
        ) {

            $rekap = RekapKasir::create([
                'user_id' => session('user_id'),
                'tanggal' => $tanggal,
                'modal_awal' => $modalAwal,
                'total_transaksi' => $totalTransaksi,
                'total_penjualan' => $totalPenjualan,
                'total_kas_keluar' => $totalKasKeluar,
                'saldo_akhir' => $saldoAkhir,
                'uang_fisik' => $request->uang_fisik,
                'selisih' => $selisih,
                'catatan' => $request->catatan ?? '',
            ]);

            KasKeluar::whereNull('id_rekap')
                ->update([
                    'id_rekap' => $rekap->id_rekap,
                ]);

            Transaksi::whereNull('id_rekap')
                ->where('status', 'selesai')
                ->update([
                    'id_rekap' => $rekap->id_rekap,
                ]);

            session()->forget('modal_awal');
        });

        return redirect()
            ->route('login')
            ->with('success', 'Tutup kas berhasil.');
    }
}