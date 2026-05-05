<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RekapitulasiController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->periode ?? now()->format('Y-m');
        $rombel = $request->rombel;

        $data = collect([
            // ===== APRIL =====
    [
        'tanggal' => '2026-04-01',
        'rombel' => 'graha',
        'keterangan' => 'Penjualan produk Graha',
        'kas_masuk' => 150000,
        'kas_keluar' => 0,
    ],
    [
        'tanggal' => '2026-04-05',
        'rombel' => 'graha',
        'keterangan' => 'Pembelian bahan',
        'kas_masuk' => 0,
        'kas_keluar' => 50000,
    ],
    [
        'tanggal' => '2026-04-10',
        'rombel' => 'membatik',
        'keterangan' => 'Penjualan batik',
        'kas_masuk' => 200000,
        'kas_keluar' => 0,
    ],
    [
        'tanggal' => '2026-04-20',
        'rombel' => 'busana',
        'keterangan' => 'Pembelian kain',
        'kas_masuk' => 0,
        'kas_keluar' => 75000,
    ],

    // ===== MEI =====
    [
        'tanggal' => '2026-05-01',
        'rombel' => 'graha',
        'keterangan' => 'Penjualan produk Graha',
        'kas_masuk' => 180000,
        'kas_keluar' => 0,
    ],
    [
        'tanggal' => '2026-05-03',
        'rombel' => 'graha',
        'keterangan' => 'Pembelian alat',
        'kas_masuk' => 0,
        'kas_keluar' => 40000,
    ],
    [
        'tanggal' => '2026-05-10',
        'rombel' => 'membatik',
        'keterangan' => 'Penjualan batik',
        'kas_masuk' => 220000,
        'kas_keluar' => 0,
    ],
    [
        'tanggal' => '2026-05-15',
        'rombel' => 'busana',
        'keterangan' => 'Penjualan busana',
        'kas_masuk' => 250000,
        'kas_keluar' => 0,
    ],

    // ===== JUNI =====
    [
        'tanggal' => '2026-06-02',
        'rombel' => 'graha',
        'keterangan' => 'Penjualan produk Graha',
        'kas_masuk' => 200000,
        'kas_keluar' => 0,
    ],
    [
        'tanggal' => '2026-06-08',
        'rombel' => 'graha',
        'keterangan' => 'Pembelian bahan',
        'kas_masuk' => 0,
        'kas_keluar' => 60000,
    ],
    [
        'tanggal' => '2026-06-12',
        'rombel' => 'membatik',
        'keterangan' => 'Penjualan batik',
        'kas_masuk' => 300000,
        'kas_keluar' => 0,
    ],
        ]);

        $rekapitulasi = $data->filter(function ($item) use ($periode) {
            return Carbon::parse($item['tanggal'])->format('Y-m') === $periode;
        });

        if ($rombel) {
            $rekapitulasi = $rekapitulasi->where('rombel', $rombel);
        }

        $kasMasuk = $rekapitulasi->sum('kas_masuk');
        $kasKeluar = $rekapitulasi->sum('kas_keluar');
        $saldo = $kasMasuk - $kasKeluar;

        return view('admin.rekapitulasi', compact(
            'rekapitulasi',
            'periode',
            'rombel',
            'kasMasuk',
            'kasKeluar',
            'saldo'
        ));
    }
}
