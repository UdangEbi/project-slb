<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $rombel = $request->query('rombel', 'busana');

        $dataBarang = [
            'busana' => [],
            'graha' => [
                ['nama' => 'Sabun Cuci', 'stok' => 15, 'harga' => 75000],
                ['nama' => 'Pewangi Laundry', 'stok' => 8, 'harga' => 25000],
                ['nama' => 'Detergen Cair', 'stok' => 12, 'harga' => 30000],
            ],
            'holtikultura' => [],
            'kecantikan' => [],
            'keramik' => [],
            'logam' => [],
            'lukis' => [],
            'membatik' => [
                ['nama' => 'Kain Batik', 'stok' => 20, 'harga' => 50000],
                ['nama' => 'Canting', 'stok' => 10, 'harga' => 15000],
                ['nama' => 'Malam Batik', 'stok' => 6, 'harga' => 20000],
            ],
            'otomotif' => [],
            'perkayuan' => [
                ['nama' => 'Miniatur Kayu', 'stok' => 9, 'harga' => 45000],
                ['nama' => 'Papan Hias', 'stok' => 5, 'harga' => 60000],
            ],
            'souvenir' => [],
            'tata-boga' => [
                ['nama' => 'Kue Kering', 'stok' => 30, 'harga' => 20000],
                ['nama' => 'Keripik Pisang', 'stok' => 25, 'harga' => 15000],
            ],
            'tik' => [],
            'titip-jual' => [],
        ];

        // JANGAN FALLBACK KE GRAHA
        $barang = $dataBarang[$rombel] ?? [];

        return view('kasir.stok.index', compact('rombel', 'barang'));
    }
}
