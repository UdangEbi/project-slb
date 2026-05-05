<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $rombel = $request->rombel ?? 'membatik';

        $barang = [
            ['nama' => 'Kain Batik', 'harga' => 75000, 'stok' => 15, 'rombel' => 'membatik'],
            ['nama' => 'Tas Batik', 'harga' => 80000, 'stok' => 8, 'rombel' => 'membatik'],
            ['nama' => 'Blus', 'harga' => 140000, 'stok' => 10, 'rombel' => 'busana'],
            ['nama' => 'Pakaian Anak', 'harga' => 120000, 'stok' => 6, 'rombel' => 'busana'],
            ['nama' => 'Kotak Tisu Kayu', 'harga' => 70000, 'stok' => 12, 'rombel' => 'perkayuan'],
            ['nama' => 'Meja Kecil', 'harga' => 150000, 'stok' => 4, 'rombel' => 'perkayuan'],
        ];

        $barang = array_values(array_filter($barang, function ($item) use ($rombel) {
            return $item['rombel'] === $rombel;
        }));

        return view('kasir.stok', compact('barang', 'rombel'));
    }
}