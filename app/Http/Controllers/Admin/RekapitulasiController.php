<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapitulasiController extends Controller
{
    public function index(Request $request)
    {
        $periodeAwal = $request->periode_awal ?? now()->startOfMonth()->format('Y-m-d');
        $periodeAkhir = $request->periode_akhir ?? now()->format('Y-m-d');
        $rombel = $request->rombel;
        $awal = Carbon::parse($periodeAwal);
        $akhir = Carbon::parse($periodeAkhir);

        if ($awal->diffInDays($akhir) > 30) {
            return back()->with('error', 'Periode maksimal 30 hari.');
        }

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

        $rekapitulasi = $data->filter(function ($item) use ($awal, $akhir) {
            $tanggal = Carbon::parse($item['tanggal']);
            return $tanggal->betweenIncluded($awal, $akhir);
        });

        if ($rombel) {
            $rekapitulasi = $rekapitulasi->where('rombel', $rombel);
        }

        $kasMasuk = $rekapitulasi->sum('kas_masuk');
        $kasKeluar = $rekapitulasi->sum('kas_keluar');
        $saldo = $kasMasuk - $kasKeluar;

        return view('admin.rekapitulasi', compact(
            'rekapitulasi',
            'periodeAwal',
            'periodeAkhir',
            'rombel',
            'kasMasuk',
            'kasKeluar',
            'saldo'
        ));
    }

    public function downloadRekapitulasiPdf(Request $request)
    {
        $periodeAwal = $request->periode_awal ?? now()->startOfMonth()->format('Y-m-d');
        $periodeAkhir = $request->periode_akhir ?? now()->format('Y-m-d');
        $rombel = $request->rombel;

        $awal = Carbon::parse($periodeAwal);
        $akhir = Carbon::parse($periodeAkhir);

        $data = collect([
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

        $rekapitulasi = $data->filter(function ($item) use ($awal, $akhir) {
            $tanggal = Carbon::parse($item['tanggal']);
            return $tanggal->betweenIncluded($awal, $akhir);
        });

        if ($rombel) {
            $rekapitulasi = $rekapitulasi->where('rombel', $rombel);
        }

        $kasMasuk = $rekapitulasi->sum('kas_masuk');
        $kasKeluar = $rekapitulasi->sum('kas_keluar');
        $saldo = $kasMasuk - $kasKeluar;

        $pdf = Pdf::loadView('admin.rekapitulasi-pdf', compact(
            'rekapitulasi',
            'kasMasuk',
            'kasKeluar',
            'saldo',
            'periodeAwal',
            'periodeAkhir',
            'rombel'
        ))->setPaper('a4', 'landscape');

        $namaRombel = $rombel ? str_replace('-', '_', $rombel) : 'semua_rombel';

        $namaFile = 'rekapitulasi_' .
            $periodeAwal . '_sampai_' .
            $periodeAkhir . '_' .
            $namaRombel . '.pdf';

        return $pdf->download($namaFile);
    }
}
