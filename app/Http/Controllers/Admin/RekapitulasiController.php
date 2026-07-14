<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailTransaksi;
use App\Models\KasKeluar;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapitulasiController extends Controller
{
    public function index(Request $request)
    {
        $periodeAwal = $request->periode_awal ?? now()->startOfMonth()->format('Y-m-d');
        $periodeAkhir = $request->periode_akhir ?? now()->format('Y-m-d');
        $awal = Carbon::parse($periodeAwal);
        $akhir = Carbon::parse($periodeAkhir);

        if ($awal->diffInDays($akhir) > 30) {
            return back()->with('error', 'Periode maksimal 30 hari.');
        }
        $data = $this->getRekapitulasi($periodeAwal, $periodeAkhir);

        return view('admin.rekapitulasi', [
            'rekapitulasi' => $data['rekapitulasi'],
            'periodeAwal' => $periodeAwal,
            'periodeAkhir' => $periodeAkhir,
            'totalKasMasuk' => $data['totalKasMasuk'],
            'totalKasKeluar' => $data['totalKasKeluar'],
            'saldo' => $data['saldo'],
        ]);
    }

    public function downloadRekapitulasiPdf(Request $request)
    {
        $periodeAwal = $request->periode_awal ?? now()->startOfMonth()->format('Y-m-d');
        $periodeAkhir = $request->periode_akhir ?? now()->format('Y-m-d');
        $data = $this->getRekapitulasi($periodeAwal, $periodeAkhir);

        $pdf = Pdf::loadView('admin.rekapitulasi-pdf', [
            'rekapitulasi' => $data['rekapitulasi'],
            'totalKasMasuk' => $data['totalKasMasuk'],
            'totalKasKeluar' => $data['totalKasKeluar'],
            'saldo' => $data['saldo'],
            'periodeAwal' => $periodeAwal,
            'periodeAkhir' => $periodeAkhir,
            'tanggalCetak' => now(),
        ])->setPaper('a4', 'landscape');

        $namaFile = 'rekapitulasi_' .
            $periodeAwal . '_sampai_' .
            $periodeAkhir . '.pdf';

        return $pdf->download($namaFile);
    }

    private function getRekapitulasi($periodeAwal, $periodeAkhir)
    {
        $awal = Carbon::parse($periodeAwal);
        $akhir = Carbon::parse($periodeAkhir);

        $transaksiMasuk = DetailTransaksi::join(
            'transaksi',
            'detail_transaksi.transaksi_id',
            '=',
            'transaksi.id_transaksi'
        )
            ->join(
                'produk',
                'detail_transaksi.produk_id',
                '=',
                'produk.id_produk'
            )
            ->join(
                'kategori_produk',
                'produk.kategori_id',
                '=',
                'kategori_produk.id_kategori'
            )
            ->whereBetween(
                DB::raw('DATE(transaksi.tanggal)'),
                [$periodeAwal, $periodeAkhir]
            )
            ->where('transaksi.status', 'selesai')
            ->selectRaw("
                transaksi.id_transaksi,
                transaksi.tanggal,
                transaksi.no_nota,
                transaksi.grand_total AS kas_masuk,
                0 AS kas_keluar,
                GROUP_CONCAT(
                    DISTINCT kategori_produk.nama_kategori
                    ORDER BY kategori_produk.nama_kategori
                    SEPARATOR ', '
                ) AS rombel
            ")
            ->groupBy(
                'transaksi.id_transaksi',
                'transaksi.tanggal',
                'transaksi.no_nota',
                'transaksi.grand_total'
            )
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal,
                    'rombel' => $item->rombel,
                    'keterangan' => 'Penjualan - ' . $item->no_nota,
                    'kas_masuk' => $item->kas_masuk,
                    'kas_keluar' => 0,
                ];
            });

        $kasKeluar = KasKeluar::join(
            'kategori_pengeluaran',
            'kas_keluar.kategori_pengeluaran_id',
            '=',
            'kategori_pengeluaran.id_kategori_pengeluaran'
        )
        ->whereBetween(
            DB::raw('DATE(kas_keluar.tanggal)'),
            [$periodeAwal, $periodeAkhir]
        )
        ->select(
            'kas_keluar.tanggal',
            'kategori_pengeluaran.nama_kategori',
            'kas_keluar.keterangan',
            'kas_keluar.nominal'
        )
        ->get()
        ->map(function ($item) {
            return [
                'tanggal' => $item->tanggal,
                'rombel' => strtoupper($item->nama_kategori),
                'keterangan' => $item->keterangan,
                'kas_masuk' => 0,
                'kas_keluar' => $item->nominal,
            ];
        });
        // dd($kasKeluar);
        $rekapitulasi = $transaksiMasuk
            ->merge($kasKeluar)
            ->sortBy('tanggal')
            ->values();

        $totalKasMasuk = $rekapitulasi->sum('kas_masuk');
        $totalKasKeluar = $rekapitulasi->sum('kas_keluar');
        $saldo = $totalKasMasuk - $totalKasKeluar;

        return [
            'rekapitulasi' => $rekapitulasi,
            'totalKasMasuk' => $totalKasMasuk,
            'totalKasKeluar' => $totalKasKeluar,
            'saldo' => $saldo,
        ];
    }
}
