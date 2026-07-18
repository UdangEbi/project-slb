<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\RekapKasir;
use App\Models\KasKeluar;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tahun Dinamis
        $tahunTersedia = Transaksi::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->toArray();
        
        $daftarTahun = !empty($tahunTersedia) ? $tahunTersedia : [date('Y')];
        
        // Memilih tahun terpilih (default tahun terbaru)
        $reqTahun = $request->tahun;
        if ($reqTahun && (!is_numeric($reqTahun) || strlen($reqTahun) != 4 || !in_array((int)$reqTahun, $daftarTahun))) {
            $reqTahun = null; // Fallback jika tidak valid
        }
        $tahun = $reqTahun ?? (in_array(date('Y'), $daftarTahun) ? date('Y') : $daftarTahun[0]);

        // 2. Agregasi Penjualan (Kas Masuk & Omzet Bulanan)
        $kasMasuk = Transaksi::whereYear('tanggal', $tahun)
            ->where('status', 'selesai')
            ->sum('grand_total');
            
        $penjualanBulananDB = Transaksi::whereYear('tanggal', $tahun)
            ->where('status', 'selesai')
            ->selectRaw('MONTH(tanggal) as bulan, SUM(grand_total) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();
            
        // Pemetaan data bulan agar konsisten array 12 bulan
        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $penjualanBulanan = [];
        for ($i = 1; $i <= 12; $i++) {
            $penjualanBulanan[$namaBulan[$i-1]] = $penjualanBulananDB[$i] ?? 0;
        }

        // 3. Omzet Penjualan per Rombel
        $penjualanRombel = DetailTransaksi::join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id_transaksi')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id_produk')
            ->join('kategori_produk', 'produk.kategori_id', '=', 'kategori_produk.id_kategori')
            ->whereYear('transaksi.tanggal', $tahun)
            ->where('transaksi.status', 'selesai')
            ->selectRaw('kategori_produk.nama_kategori, SUM(detail_transaksi.subtotal) as total_omzet')
            ->groupBy('kategori_produk.nama_kategori')
            ->pluck('total_omzet', 'nama_kategori')
            ->toArray();

        // 4. Barang Paling Laris
        $barangTerlaris = DetailTransaksi::join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id_transaksi')
            ->leftJoin('produk', 'detail_transaksi.produk_id', '=', 'produk.id_produk')
            ->whereYear('transaksi.tanggal', $tahun)
            ->where('transaksi.status', 'selesai')
            ->selectRaw('COALESCE(produk.nama_produk, "Produk Dihapus") as nama_barang, SUM(detail_transaksi.qty) as jumlah_terjual')
            ->groupBy('detail_transaksi.produk_id', 'produk.nama_produk')
            ->orderByDesc('jumlah_terjual')
            ->limit(5)
            ->get();

        // 5. Laba Bersih
        $labaBersih = (float) DetailTransaksi::join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id_transaksi')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id_produk')
            ->whereYear('transaksi.tanggal', $tahun)
            ->where('transaksi.status', 'selesai')
            ->sum(DB::raw('(detail_transaksi.qty * detail_transaksi.harga) - (detail_transaksi.qty * produk.harga_beli)'));

        // 5. Data Pembeli
        $pembeli = Transaksi::whereYear('tanggal', $tahun)
            ->where('status', 'selesai')
            ->orderByDesc('tanggal')
            ->limit(10)
            ->get()
            ->map(function ($trx) {
                return [
                    'tanggal' => $trx->tanggal->format('Y-m-d'),
                    'kode_transaksi' => $trx->no_nota,
                    'nama_pembeli' => $trx->nama_pembeli ?? '-',
                    'no_tlp' => $trx->no_tlp ?? '-',
                    'instansi' => $trx->instansi ?? '-',
                    'total' => $trx->grand_total,
                ];
            });

        // 6. Kalkulasi & Default Values
        $kasKeluar = KasKeluar::whereYear('tanggal', $tahun)->sum('nominal');
        $donasi = Transaksi::whereYear('tanggal', $tahun)->sum('donasi');
            
        $saldo = $kasMasuk - $kasKeluar + $donasi;

        return view('admin.dashboard', compact(
            'tahun',
            'daftarTahun',
            'penjualanBulanan',
            'penjualanRombel',
            'pembeli',
            'barangTerlaris',
            'labaBersih',
            'saldo',
            'kasMasuk',
            'kasKeluar',
            'donasi'
        ));
    }
}

