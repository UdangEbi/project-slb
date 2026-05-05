<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $rombel = $request->query('rombel', 'graha');

        $dataBarang = [
            'graha' => [
                ['nama' => 'Sabun Cuci', 'stok' => 15, 'harga' => 75000],
                ['nama' => 'Pewangi Laundry', 'stok' => 8, 'harga' => 25000],
                ['nama' => 'Detergen Cair', 'stok' => 12, 'harga' => 30000],
            ],
            'membatik' => [
                ['nama' => 'Kain Batik', 'stok' => 20, 'harga' => 50000],
                ['nama' => 'Canting', 'stok' => 10, 'harga' => 15000],
                ['nama' => 'Malam Batik', 'stok' => 6, 'harga' => 20000],
            ],
            'perkayuan' => [
                ['nama' => 'Miniatur Kayu', 'stok' => 9, 'harga' => 45000],
                ['nama' => 'Papan Hias', 'stok' => 5, 'harga' => 60000],
            ],
            'busana' => [
                ['nama' => 'Tas Kain', 'stok' => 14, 'harga' => 35000],
                ['nama' => 'Dompet Kain', 'stok' => 18, 'harga' => 25000],
            ],
            'tata-boga' => [
                ['nama' => 'Kue Kering', 'stok' => 30, 'harga' => 20000],
                ['nama' => 'Keripik Pisang', 'stok' => 25, 'harga' => 15000],
            ],
            'kecantikan' => [
                ['nama' => 'Sabun Herbal', 'stok' => 11, 'harga' => 18000],
                ['nama' => 'Lulur Tradisional', 'stok' => 7, 'harga' => 22000],
            ],
            'logam' => [
                ['nama' => 'Gantungan Kunci', 'stok' => 40, 'harga' => 10000],
                ['nama' => 'Hiasan Logam', 'stok' => 13, 'harga' => 30000],
            ],
        ];

        $barang = $dataBarang[$rombel] ?? $dataBarang['graha'];

        return view('kasir.stok.index', compact('rombel', 'barang'));
    }
}
