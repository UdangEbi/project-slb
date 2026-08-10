<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KasKeluar;
use App\Models\KategoriPengeluaran;
use Carbon\Carbon;
use App\Models\Produk;
use App\Models\DetailTransaksi;

class KasKeluarController extends Controller
{
    public function index()
    {
        $kategori = KategoriPengeluaran::orderBy('nama_kategori')->get();

        $kasKeluar = KasKeluar::with(['kategori', 'user'])
            ->whereNull('id_rekap')
            ->orderByDesc('tanggal')
            ->get();

        $produkTitipJual = Produk::whereHas('kategori', function ($q) {
            $q->where('nama_kategori', 'TITIP JUAL');
        })
            ->orderBy('nama_produk')
            ->get();

        return view('kasir.kas-keluar', compact(
            'kategori',
            'kasKeluar',
            'produkTitipJual'
        ));
    }

    public function store(Request $request)
    {

        $request->merge([
            'nominal' => str_replace('.', '', $request->nominal),
        ]);
        $request->validate([
            'tanggal' => 'required|date',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id_kategori_pengeluaran',
            'kode_produk' => 'nullable|string|max:50',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {

                    // Jika bukan kategori Bayar Titip Jual, keterangan wajib diisi
                    if (
                        $request->kategori_pengeluaran_id != 6 &&
                        empty(trim($value))
                    ) {
                        $fail('Keterangan wajib diisi.');
                    }
                },
            ],
        ]);

        KasKeluar::create([
            'tanggal' => Carbon::parse($request->tanggal),
            'user_id' => session('user_id'),
            'kategori_pengeluaran_id' => $request->kategori_pengeluaran_id,
            'kode_produk' => $request->kode_produk,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('kasir.kas-keluar')
            ->with('success', 'Pengeluaran kas berhasil ditambahkan.');
    }

    public function update(Request $request, KasKeluar $kasKeluar)
    {
        $request->merge([
            'nominal' => str_replace('.', '', $request->nominal),
        ]);
        $request->validate([
            'tanggal' => 'required|date',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id_kategori_pengeluaran',
            'kode_produk' => 'nullable|string|max:50',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {

                    // Jika bukan kategori Bayar Titip Jual, keterangan wajib diisi
                    if (
                        $request->kategori_pengeluaran_id != 6 &&
                        empty(trim($value))
                    ) {
                        $fail('Keterangan wajib diisi.');
                    }
                },
            ],
        ]);

        $kasKeluar->update([
            'tanggal' => Carbon::parse($request->tanggal),
            'kategori_pengeluaran_id' => $request->kategori_pengeluaran_id,
            'kode_produk' => $request->kode_produk,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('kasir.kas-keluar')
            ->with('success', 'Pengeluaran kas berhasil diperbarui.');
    }

    public function getSisaTitipJual($kodeProduk)
    {
        $produk = Produk::where('kode_produk', $kodeProduk)
            ->firstOrFail();

        // Total barang yang benar-benar terjual
        $totalTerjual = \App\Models\DetailTransaksi::where('produk_id', $produk->id_produk)
            ->whereHas('transaksi', function ($q) {
                $q->where('status', 'selesai');
            })
            ->sum('qty');

        // Total kewajiban pembayaran ke penitip
        $totalHarusDibayar = $totalTerjual * $produk->harga_beli;

        // Total yang sudah pernah dibayar
        $totalSudahDibayar = KasKeluar::where('kategori_pengeluaran_id', 6)
            ->where('kode_produk', $produk->kode_produk)
            ->sum('nominal');

        // Sisa yang masih harus dibayar
        $sisaBelumDibayar = max(
            $totalHarusDibayar - $totalSudahDibayar,
            0
        );

        return response()->json([
            'kode_produk' => $produk->kode_produk,
            'nama_produk' => $produk->nama_produk,
            'harga_beli' => $produk->harga_beli,
            'total_terjual' => $totalTerjual,
            'total_harus_dibayar' => $totalHarusDibayar,
            'total_sudah_dibayar' => $totalSudahDibayar,
            'sisa_belum_dibayar' => $sisaBelumDibayar,
        ]);
    }
}
