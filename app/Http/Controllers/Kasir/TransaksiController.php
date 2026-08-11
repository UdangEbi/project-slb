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

        // Cari rekap kasir yang masih aktif.
        // waktu_tutup NULL berarti kasir belum ditutup.
        $rekapAktif = RekapKasir::where('user_id', session('user_id'))
            ->whereNull('waktu_tutup')
            ->latest('id_rekap')
            ->first();


        // Tetap simpan ke session agar kode lama yang menggunakan
        // session('modal_awal') belum langsung rusak.
        if ($rekapAktif) {
            session([
                'modal_awal' => $rekapAktif->modal_awal,
                'id_rekap'   => $rekapAktif->id_rekap,
            ]);
        } else {
            session()->forget([
                'modal_awal',
                'id_rekap',
            ]);
        }

        return view('kasir.transaksi', compact(
            'produk',
            'kategori',
            'kategoriId',
            'rekapAktif'
        ));
    }
    // ── MODAL AWAL ───────────────────────────────────────────
    public function storeModalAwal(Request $request)
    {
        $request->validate([
            'modal_awal' => 'required',
        ]);

        $nominal = (int) str_replace(
            ['.', ','],
            ['', ''],
            $request->modal_awal
        );

        $userId = session('user_id');

        // Cegah pembuatan rekap baru jika kasir masih aktif.
        $rekapAktif = RekapKasir::where('user_id', $userId)
            ->whereNull('waktu_tutup')
            ->latest('id_rekap')
            ->first();

        if ($rekapAktif) {
            session([
                'modal_awal' => $rekapAktif->modal_awal,
                'id_rekap'   => $rekapAktif->id_rekap,
            ]);

            return redirect()->route('kasir.transaksi');
        }

        $rekap = RekapKasir::create([
            'user_id'          => $userId,
            'tanggal'          => Carbon::today(),
            'waktu_buka'       => Carbon::now(),
            'waktu_tutup'      => null,
            'modal_awal'       => $nominal,
            'total_transaksi'  => 0,
            'total_penjualan'  => 0,
            'total_kas_keluar' => 0,
            'saldo_akhir'      => 0,
            'uang_fisik'       => 0,
            'selisih'          => 0,
            'catatan'          => null,
        ]);

        session([
            'modal_awal' => $rekap->modal_awal,
            'id_rekap'   => $rekap->id_rekap,
        ]);

        return redirect()
            ->route('kasir.transaksi')
            ->with('success', 'Modal awal berhasil disimpan.');
    }

    // ── SIMPAN TRANSAKSI (dipanggil via fetch dari JS) ───────
    public function simpanTransaksi(Request $request)
    {
        $request->validate([
            'keranjang'        => 'required|array|min:1',
            'keranjang.*.id'   => 'required|integer',
            'keranjang.*.qty'  => 'required|integer|min:1',
            'total'            => 'required|numeric',
            'metode'           => 'required|in:cash,qris',
            'bayar'            => 'required|numeric',
            'donasi'           => 'nullable|numeric',
            'nama_pembeli'     => 'required|string|max:255',
            'no_tlp'           => 'required|string|max:20',
            'instansi'         => 'required|string|max:255',
            'donasi'           => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $noNota    = 'TRX-' . Carbon::now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
            $total     = $request->total;
            $bayar     = $request->bayar;
            $donasi    = (int) ($request->donasi ?? 0);
            $metode    = $request->metode === 'cash' ? 'tunai' : 'qris';

            // QRIS tidak mendukung donasi (sesuai aturan di frontend)
            if ($metode === 'qris') {
                $donasi = 0;
            }

            $kembalian = max(0, $bayar - $total - $donasi);

            // 1. Simpan header transaksi
            $transaksi = Transaksi::create([
                'no_nota'           => $noNota,
                'tanggal'           => Carbon::now(),
                'user_id'           => session('user_id'),
                'nama_pembeli'      => $request->nama_pembeli,
                'no_tlp'            => $request->no_tlp,
                'instansi'          => $request->instansi,
                'total'             => $total,
                'diskon'            => 0,
                'donasi'            => $request->donasi ?? 0,
                'grand_total'       => $total,
                'metode_pembayaran' => $metode,
                'bayar'             => $bayar,
                'kembalian'         => $kembalian,
                'donasi'            => $donasi,
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

                // Kurangi stok + catat riwayat
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

            // Ambil detail transaksi lengkap untuk data struk
            $detailStruk = DetailTransaksi::with('produk')
                ->where('transaksi_id', $transaksi->id_transaksi)
                ->get()
                ->map(function ($d) {
                    return [
                        'nama'     => $d->produk->nama_produk,
                        'qty'      => $d->qty,
                        'harga'    => $d->harga,
                        'subtotal' => $d->subtotal,
                    ];
                });

            Carbon::setLocale('id');

            return response()->json([
                'sukses'  => true,
                'no_nota' => $noNota,
                'pesan'   => 'Transaksi berhasil disimpan',
                'struk'   => [
                    'no_nota'      => $noNota,
                    'tanggal'      => Carbon::now()->translatedFormat('l, d F Y'),
                    'jam'          => Carbon::now()->format('H:i:s'),
                    'kasir'        => session('username'),
                    'nama_pembeli' => $request->nama_pembeli,
                    'no_tlp'       => $request->no_tlp,
                    'instansi'     => $request->instansi,
                    'metode'       => $metode,
                    'items'        => $detailStruk,
                    'total'        => $total,
                    'bayar'        => $bayar,
                    'kembalian'    => $kembalian,
                    'donasi'       => $donasi,
                ],
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
