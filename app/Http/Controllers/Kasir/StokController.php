<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use App\Models\Produk;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use App\Models\KasKeluar;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $kategori = KategoriProduk::orderBy('nama_kategori')->get();

        $kategoriId = $request->kategori;

        if (!$kategoriId && $kategori->isNotEmpty()) {
            $kategoriId = $kategori->first()->id_kategori;
        }

        $barang = Produk::with('kategori')
            ->where('kategori_id', $kategoriId)
            ->orderBy('nama_produk')
            ->get();

        return view(
            'kasir.stok.index',
            compact(
                'kategori',
                'barang',
                'kategoriId'
            )
        );
    }

    public function store(Request $request)
    {
        // Hilangkan titik pada format rupiah
        $request->merge([
            'harga_beli' => str_replace('.', '', $request->harga_beli),
            'harga_jual' => str_replace('.', '', $request->harga_jual),
        ]);

        // Validasi
        $request->validate([
            'kategori_id' => 'required|exists:kategori_produk,id_kategori',
            'nama_produk' => 'required|max:150',
            'harga_beli'  => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|gt:harga_beli',
            'stok'        => 'required|integer|min:0',
            'satuan'      => 'required|max:50',
        ], [
            'harga_jual.gt' => 'Harga jual harus lebih tinggi dari harga beli.',
        ]);

        DB::transaction(function () use ($request) {

            $produk = Produk::create([
                'kategori_id' => $request->kategori_id,
                'kode_produk' => $this->generateKodeProduk($request->kategori_id),
                'nama_produk' => strtoupper($request->nama_produk),
                'harga_beli'  => $request->harga_beli,
                'harga_jual'  => $request->harga_jual,
                'stok'        => $request->stok,
                'satuan'      => strtoupper($request->satuan),
            ]);

            RiwayatStok::create([
                'produk_id'     => $produk->id_produk,
                'user_id'       => session('user_id'),
                'tipe'          => 'masuk',
                'jumlah'        => $request->stok,
                'stok_sebelum'  => 0,
                'stok_sesudah'  => $request->stok,
                'keterangan'    => 'Stok awal produk',
                'tanggal'       => now(),
            ]);
        });

        return redirect()
            ->route('kasir.stok', [
                'kategori' => $request->kategori_id
            ])
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function tambahStok(Request $request)
    {
        // Hilangkan titik dari format rupiah
        $request->merge([
            'harga_beli' => str_replace('.', '', $request->harga_beli),
            'harga_jual' => str_replace('.', '', $request->harga_jual),
        ]);

        $request->validate([
            'produk_id'  => 'required|exists:produk,id_produk',
            'jumlah'     => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|gt:harga_beli',
            'keterangan' => 'nullable|string',
        ], [
            'harga_jual.gt' => 'Harga jual harus lebih tinggi dari harga beli.',
        ]);

        // Ambil produk dan kategori sebelum transaction
        $produk = Produk::findOrFail($request->produk_id);
        $kategoriId = $produk->kategori_id;

        DB::transaction(function () use ($request, $produk) {

            // Update harga
            $produk->update([
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
            ]);

            // Tambah stok jika jumlah > 0
            if ($request->jumlah > 0) {

                $stokSebelum = $produk->stok;

                $produk->increment('stok', $request->jumlah);

                RiwayatStok::create([
                    'produk_id'     => $produk->id_produk,
                    'user_id'       => session('user_id'),
                    'tipe'          => 'masuk',
                    'jumlah'        => $request->jumlah,
                    'stok_sebelum'  => $stokSebelum,
                    'stok_sesudah'  => $stokSebelum + $request->jumlah,
                    'keterangan'    => 'Tambah stok',
                    'tanggal'       => now(),
                ]);
            }
        });

        return redirect()->route('kasir.stok', [
            'kategori' => $kategoriId
        ]);
    }

    public function penyesuaian(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id_produk',
            'stok_baru' => 'required|integer|min:0',
            'keterangan' => 'required|string',
        ]);

        DB::transaction(function () use ($request) {

            $produk = Produk::findOrFail($request->produk_id);

            $stokSebelum = $produk->stok;

            $produk->update([
                'stok' => $request->stok_baru,
            ]);

            RiwayatStok::create([
                'produk_id' => $produk->id_produk,
                'user_id' => session('user_id'),
                'tipe' => 'penyesuaian',
                'jumlah' => abs($stokSebelum - $request->stok_baru),
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $request->stok_baru,
                'keterangan' => $request->keterangan,
                'tanggal' => now(),
            ]);
        });

        return redirect()
            ->back()
            ->with('success', 'Penyesuaian stok berhasil.');
    }

    public function riwayat()
    {
        $riwayat = RiwayatStok::with([
            'produk',
            'user'
        ])
            ->latest('tanggal')
            ->paginate(20);

        return view(
            'kasir.stok.riwayat',
            compact('riwayat')
        );
    }

    public function create(Request $request)
    {
        $kategori = KategoriProduk::orderBy('nama_kategori')->get();

        $kategoriId = $request->kategori;

        if (!$kategoriId) {
            $kategoriId = $kategori->first()->id_kategori;
        }

        $kodeProduk = $this->generateKodeProduk($kategoriId);

        return view('kasir.stok.tambah-barang', compact(
            'kategori',
            'kategoriId',
            'kodeProduk'
        ));
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);

        $kategori = KategoriProduk::orderBy('nama_kategori')->get();

        $kategoriId = $produk->kategori_id;

        return view('kasir.stok.tambah-stok', compact(
            'produk',
            'kategori',
            'kategoriId'
        ));
    }

    private function generateKodeProduk($kategoriId)
    {
        $prefix = [
            1  => 'BS',
            2  => 'GR',
            3  => 'HL',
            4  => 'KC',
            5  => 'KR',
            6  => 'LG',
            7  => 'LK',
            8  => 'MB',
            9  => 'OT',
            10 => 'PK',
            11 => 'SV',
            12 => 'TB',
            13 => 'TK',
            14 => 'TJ',
        ];

        $kodePrefix = $prefix[$kategoriId];

        $last = Produk::where('kategori_id', $kategoriId)
            ->where('kode_produk', 'like', $kodePrefix . '%')
            ->orderByDesc('kode_produk')
            ->first();

        if ($last) {
            $nomor = intval(substr($last->kode_produk, 2)) + 1;
        } else {
            $nomor = 1;
        }

        return $kodePrefix . str_pad($nomor, 3, '0', STR_PAD_LEFT);
    }

    public function getKodeProduk($kategoriId)
    {
        return response()->json([
            'kode' => $this->generateKodeProduk($kategoriId)
        ]);
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Jangan boleh dihapus jika sudah pernah dipakai transaksi
        if ($produk->detailTransaksi()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Barang tidak dapat dihapus karena sudah pernah digunakan dalam transaksi.');
        }

        // Hapus riwayat stok terlebih dahulu
        $produk->riwayatStok()->delete();

        $produk->delete();

        return redirect()
            ->back()
            ->with('success', 'Barang berhasil dihapus.');
    }

    public function titipJual(Request $request)
    {
        $kategori = KategoriProduk::orderBy('nama_kategori')->get();

        $kategoriId = 14;

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        // Ambil seluruh produk kategori TITIP JUAL
        $produk = Produk::where('kategori_id', 14)
            ->orderBy('nama_produk')
            ->get();

        foreach ($produk as $item) {

            /*
        |--------------------------------------------------------------------------
        | 1. TOTAL BARANG MASUK
        |--------------------------------------------------------------------------
        */

            $queryMasuk = RiwayatStok::where('produk_id', $item->id_produk)
                ->where('tipe', 'masuk');

            if ($tanggalAwal) {
                $queryMasuk->whereDate('tanggal', '>=', $tanggalAwal);
            }

            if ($tanggalAkhir) {
                $queryMasuk->whereDate('tanggal', '<=', $tanggalAkhir);
            }

            $item->total_masuk = $queryMasuk->sum('jumlah');


            /*
        |--------------------------------------------------------------------------
        | 2. TOTAL BENAR-BENAR TERJUAL
        |--------------------------------------------------------------------------
        |
        | Jangan lagi menggunakan riwayat_stok tipe keluar.
        | Penjualan dihitung dari detail transaksi.
        |
        */

            $queryTerjual = DetailTransaksi::where('produk_id', $item->id_produk)
                ->whereHas('transaksi', function ($q) use ($tanggalAwal, $tanggalAkhir) {

                    $q->where('status', 'selesai');

                    if ($tanggalAwal) {
                        $q->whereDate('tanggal', '>=', $tanggalAwal);
                    }

                    if ($tanggalAkhir) {
                        $q->whereDate('tanggal', '<=', $tanggalAkhir);
                    }
                });

            $item->total_terjual = $queryTerjual->sum('qty');


            /*
        |--------------------------------------------------------------------------
        | 3. TOTAL YANG HARUS DIBAYARKAN
        |--------------------------------------------------------------------------
        */

            $item->total_bayar =
                $item->harga_beli * $item->total_terjual;


            /*
        |--------------------------------------------------------------------------
        | 4. TOTAL YANG SUDAH DIBAYAR KE PENITIP
        |--------------------------------------------------------------------------
        |
        | Kategori 6 = Bayar Titip Jual
        |
        */

            $item->sudah_dibayar = KasKeluar::where(
                'kategori_pengeluaran_id',
                6
            )
                ->where('kode_produk', $item->kode_produk)
                ->sum('nominal');


            /*
        |--------------------------------------------------------------------------
        | 5. SISA YANG MASIH HARUS DIBAYAR
        |--------------------------------------------------------------------------
        */

            $item->belum_dibayar = max(
                $item->total_bayar - $item->sudah_dibayar,
                0
            );


            /*
        |--------------------------------------------------------------------------
        | 6. STATUS PEMBAYARAN
        |--------------------------------------------------------------------------
        */

            if ($item->total_terjual == 0) {

                $item->status_bayar = 'belum_ada_penjualan';
            } elseif ($item->belum_dibayar == 0) {

                $item->status_bayar = 'lunas';
            } elseif ($item->sudah_dibayar > 0) {

                $item->status_bayar = 'sebagian';
            } else {

                $item->status_bayar = 'belum_dibayar';
            }


            /*
        |--------------------------------------------------------------------------
        | 7. PERSENTASE
        |--------------------------------------------------------------------------
        */

            $item->persentase =
                $item->total_masuk == 0
                ? 0
                : round(
                    ($item->total_terjual / $item->total_masuk) * 100,
                    1
                );
        }


        /*
    |--------------------------------------------------------------------------
    | CARD ATAS
    |--------------------------------------------------------------------------
    */

        $totalProduk = $produk->count();

        $totalTerjual = $produk->sum('total_terjual');

        $totalSisa = $produk->sum('stok');

        // Yang benar-benar masih menjadi kewajiban
        $totalBelumDibayar = $produk->sum('belum_dibayar');

        // Historis pembayaran yang sudah dilakukan
        $totalSudahDibayar = $produk->sum('sudah_dibayar');

        // Total hasil penjualan titipan
        $totalBayar = $produk->sum('total_bayar');


        return view(
            'kasir.stok.titip-jual',
            compact(
                'produk',
                'tanggalAwal',
                'tanggalAkhir',
                'totalProduk',
                'totalTerjual',
                'totalSisa',
                'totalBayar',
                'totalBelumDibayar',
                'totalSudahDibayar',
                'kategori',
                'kategoriId'
            )
        );
    }
}
