<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\DetailTransaksi;
use App\Models\KategoriProduk;
use App\Models\Produk;
use App\Models\RekapKasir;
use App\Models\RiwayatStok;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // ── TAMPILAN KASIR ───────────────────────────────────────
    public function index(Request $request)
    {
        $kategori = KategoriProduk::orderBy('nama_kategori')->get();

        $kategoriId = $request->kategori;

        if (!$kategoriId) {
            $kategoriId = $kategori->first()->id_kategori ?? null;
        }

        $barang = Produk::where('kategori_id', $kategoriId)
            ->where('stok', '>', 0)
            ->orderBy('nama_produk')
            ->get();

        $produk = $barang->map(function ($item) {
            return [
                'id'    => $item->id_produk,
                'nama'  => $item->nama_produk,
                'harga' => $item->harga_jual,
                'kode'  => $item->kode_produk,
            ];
        })->toArray();

        return view('kasir.transaksi', compact('produk', 'kategori', 'kategoriId'));
    }

    // ── MODAL AWAL ───────────────────────────────────────────
    // Catatan: route aktif untuk modal awal saat ini mengarah ke
    // AuthKasirController::storeModalAwal, bukan ke sini.
    // Method ini disesuaikan agar memakai nama route yang valid
    // (kasir.transaksi), seandainya suatu saat dipakai.
    public function storeModalAwal(Request $request)
    {
        $request->validate([
            'modal_awal' => 'required'
        ]);

        // Bersihkan format ribuan → angka murni
        $nominal = (int) str_replace(['.', ','], ['', ''], $request->modal_awal);

        session(['modal_awal' => $nominal]);

        return redirect()->route('kasir.transaksi');
    }

    // ── SIMPAN TRANSAKSI (dipanggil via fetch dari JS) ───────
    public function simpanTransaksi(Request $request)
    {
        $request->validate([
            'keranjang'       => 'required|array|min:1',
            'keranjang.*.id'  => 'required|integer',
            'keranjang.*.qty' => 'required|integer|min:1',
            'total'           => 'required|numeric',
            'metode'          => 'required|in:cash,qris',
            'bayar'           => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $noNota    = 'TRX-' . Carbon::now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
            $total     = $request->total;
            $bayar     = $request->bayar;
            $kembalian = max(0, $bayar - $total);
            $metode    = $request->metode === 'cash' ? 'tunai' : 'qris';

            // 1. Simpan header transaksi
            $transaksi = Transaksi::create([
                'no_nota'           => $noNota,
                'tanggal'           => Carbon::now(),
                'user_id'           => session('user_id'),
                'total'             => $total,
                'diskon'            => 0,
                'grand_total'       => $total,
                'metode_pembayaran' => $metode,
                'bayar'             => $bayar,
                'kembalian'         => $kembalian,
                'status'            => 'selesai',
            ]);

            // 2. Simpan detail + kurangi stok + catat riwayat
            foreach ($request->keranjang as $item) {
                $produk = Produk::findOrFail($item['id']);

                // Cek stok mencukupi
                if ($produk->stok < $item['qty']) {
                    DB::rollBack();
                    return response()->json([
                        'sukses' => false,
                        'pesan'  => "Stok {$produk->nama_produk} tidak cukup (sisa {$produk->stok})"
                    ], 422);
                }

                // Simpan detail transaksi
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id_transaksi,
                    'produk_id'    => $produk->id_produk,
                    'qty'          => $item['qty'],
                    'harga'        => $produk->harga_jual,
                    'subtotal'     => $produk->harga_jual * $item['qty'],
                ]);

                // Kurangi stok + catat riwayat (sama persis pola StokController)
                $stokSebelum = $produk->stok;
                $produk->decrement('stok', $item['qty']);

                RiwayatStok::create([
                    'produk_id'    => $produk->id_produk,
                    'user_id'      => session('user_id'),
                    'tipe'         => 'keluar',
                    'jumlah'       => $item['qty'],
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSebelum - $item['qty'],
                    'keterangan'   => 'Penjualan ' . $noNota,
                    'tanggal'      => Carbon::now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'sukses'  => true,
                'no_nota' => $noNota,
                'pesan'   => 'Transaksi berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'sukses' => false,
                'pesan'  => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}