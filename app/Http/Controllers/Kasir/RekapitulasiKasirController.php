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

        $rekapAktif = RekapKasir::where('user_id', session('user_id'))
            ->whereNull('waktu_tutup')
            ->latest('id_rekap')
            ->first();

        $modalAwal = $rekapAktif?->modal_awal ?? 0;

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
            'catatan'    => 'nullable|string',
        ]);

        $userId = session('user_id');

        // Cari rekap kasir yang sedang aktif.
        $rekapAktif = RekapKasir::where('user_id', $userId)
            ->whereNull('waktu_tutup')
            ->latest('id_rekap')
            ->first();

        if (!$rekapAktif) {
            return redirect()
                ->route('kasir.transaksi')
                ->with('error', 'Tidak ada sesi kasir yang sedang aktif.');
        }

        $modalAwal = $rekapAktif->modal_awal;

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

        $saldoAkhir = $modalAwal
            + $totalPenjualan
            + $totalDonasi
            - $totalKasKeluar;

        $selisih = $request->uang_fisik - $saldoAkhir;

        DB::transaction(function () use (
            $rekapAktif,
            $totalTransaksi,
            $totalPenjualan,
            $totalKasKeluar,
            $saldoAkhir,
            $selisih,
            $request
        ) {
            // Perbarui rekap yang dibuat saat memasukkan modal awal.
            $rekapAktif->update([
                'waktu_tutup'      => now(),
                'total_transaksi'  => $totalTransaksi,
                'total_penjualan'  => $totalPenjualan,
                'total_kas_keluar' => $totalKasKeluar,
                'saldo_akhir'      => $saldoAkhir,
                'uang_fisik'       => $request->uang_fisik,
                'selisih'          => $selisih,
                'catatan'          => $request->catatan ?? '',
            ]);

            // Hubungkan kas keluar dengan rekap yang ditutup.
            KasKeluar::whereNull('id_rekap')
                ->update([
                    'id_rekap' => $rekapAktif->id_rekap,
                ]);

            // Hubungkan transaksi dengan rekap yang ditutup.
            Transaksi::whereNull('id_rekap')
                ->where('status', 'selesai')
                ->update([
                    'id_rekap' => $rekapAktif->id_rekap,
                ]);

            session()->forget([
                'modal_awal',
                'id_rekap',
            ]);
        });

        return redirect()
            ->route('login')
            ->with('success', 'Tutup kas berhasil.');
    }
}
