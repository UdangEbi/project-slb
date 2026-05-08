<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $rombel = $request->rombel ?? 'graha';

        $produk = [
            ['nama' => 'Kain Batik', 'harga' => 75000, 'rombel' => 'membatik'],
            ['nama' => 'Tas Batik', 'harga' => 80000, 'rombel' => 'membatik'],
            ['nama' => 'Sarung Bantal', 'harga' => 60000, 'rombel' => 'membatik'],

            ['nama' => 'Blus', 'harga' => 140000, 'rombel' => 'busana'],
            ['nama' => 'Pakaian Anak', 'harga' => 120000, 'rombel' => 'busana'],
            ['nama' => 'Apron Batik', 'harga' => 65000, 'rombel' => 'busana'],

            ['nama' => 'Kotak Tisu Kayu', 'harga' => 70000, 'rombel' => 'perkayuan'],
            ['nama' => 'Meja Kecil', 'harga' => 150000, 'rombel' => 'perkayuan'],
        ];

        $produk = array_filter($produk, function ($item) use ($rombel) {
            return $item['rombel'] === $rombel;
        });

        return view('kasir.transaksi', compact('produk', 'rombel'));
    }
}