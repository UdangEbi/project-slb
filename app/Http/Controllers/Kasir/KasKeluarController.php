<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KasKeluar;
use App\Models\KategoriPengeluaran;
use Carbon\Carbon;

class KasKeluarController extends Controller
{
    public function index()
    {
        $kategori = KategoriPengeluaran::orderBy('nama_kategori')->get();

        $kasKeluar = KasKeluar::with(['kategori', 'user'])
            ->whereNull('id_rekap')
            ->orderByDesc('tanggal')
            ->get();

        return view('kasir.kas-keluar', compact(
            'kategori',
            'kasKeluar'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id_kategori_pengeluaran',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'required|string|max:255',
        ]);

        KasKeluar::create([
            'tanggal' => Carbon::parse($request->tanggal),
            'user_id' => session('user_id'),
            'kategori_pengeluaran_id' => $request->kategori_pengeluaran_id,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('kasir.kas-keluar')
            ->with('success', 'Pengeluaran kas berhasil ditambahkan.');
    }

    public function update(Request $request, KasKeluar $kasKeluar)
    {
        $request->validate([
        'tanggal' => 'required|date',
        'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id_kategori_pengeluaran',
        'nominal' => 'required|numeric|min:1',
        'keterangan' => 'required|string|max:255',
    ]);

        $kasKeluar->update([
            'tanggal' => Carbon::parse($request->tanggal),
            'kategori_pengeluaran_id' => $request->kategori_pengeluaran_id,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('kasir.kas-keluar')
            ->with('success', 'Pengeluaran kas berhasil diperbarui.');
    }
}
