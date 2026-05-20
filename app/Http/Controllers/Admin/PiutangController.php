<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PiutangController extends Controller
{
    public function index()
    {
        $piutang = [
            [
                'tanggal' => '2026-05-01',
                'nama' => 'Ibu Lisa',
                'keterangan' => 'Pembelian produk belum lunas',
                'jumlah' => 50000,
                'status' => 'Belum Lunas',
            ],
            [
                'tanggal' => '2026-05-08',
                'nama' => 'Wali Murid',
                'keterangan' => 'Pembelian batik',
                'jumlah' => 75000,
                'status' => 'Belum Lunas',
            ],
            [
                'tanggal' => '2026-05-12',
                'nama' => 'Bu Boniyati',
                'keterangan' => 'Pembelian produk keterampilan',
                'jumlah' => 30000,
                'status' => 'Lunas',
            ],
        ];

        return view('admin.piutang', compact('piutang'));
    }
}
