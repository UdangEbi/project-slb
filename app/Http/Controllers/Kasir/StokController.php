<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use App\Models\Produk;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
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
        $request->validate([
            'kategori_id' => 'required|exists:kategori_produk,id_kategori',
            'kode_produk' => 'required|unique:produk,kode_produk',
            'nama_produk' => 'required|max:150',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|max:50',
        ]);

        DB::transaction(function () use ($request) {

            $produk = Produk::create([
                'kategori_id' => $request->kategori_id,
                'kode_produk' => $request->kode_produk,
                'nama_produk' => $request->nama_produk,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'stok' => $request->stok,
                'satuan' => $request->satuan,
            ]);

            RiwayatStok::create([
                'produk_id' => $produk->id_produk,
                'user_id' => session('user_id'),
                'tipe' => 'masuk',
                'jumlah' => $request->stok,
                'stok_sebelum' => 0,
                'stok_sesudah' => $request->stok,
                'keterangan' => 'Stok awal produk',
                'tanggal' => now(),
            ]);
        });

        return redirect()
            ->back()
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function tambahStok(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id_produk',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            $produk = Produk::findOrFail($request->produk_id);

            $stokSebelum = $produk->stok;

            $produk->increment('stok', $request->jumlah);

            RiwayatStok::create([
                'produk_id' => $produk->id_produk,
                'user_id' => session('user_id'),
                'tipe' => 'masuk',
                'jumlah' => $request->jumlah,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSebelum + $request->jumlah,
                'keterangan' => $request->keterangan ?? 'Tambah stok',
                'tanggal' => now(),
            ]);
        });

        return redirect()
            ->back()
            ->with('success', 'Stok berhasil ditambahkan.');
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
}