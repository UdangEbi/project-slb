<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->tahun ?? 2026;

        $daftarTahun = [2024, 2025, 2026];

        // Dummy data per tahun
        $dummyData = [
        2024 => [
            'penjualanBulanan' => [
                'Jan' => 120000,
                'Feb' => 165000,
                'Mar' => 145000,
                'Apr' => 190000,
                'Mei' => 210000,
                'Jun' => 175000,
                'Jul' => 235000,
                'Agu' => 260000,
                'Sep' => 225000,
                'Okt' => 300000,
                'Nov' => 280000,
                'Des' => 350000,
            ],

            'penjualanRombel' => [
                'Graha' => 320000,
                'Membatik' => 430000,
                'Perkayuan' => 280000,
                'Busana' => 390000,
                'Tataboga' => 360000,
                'Kecantikan' => 240000,
                'Logam' => 210000,
            ],

            'pembeli' => [
                [
                    'tanggal' => '2024-02-01',
                    'kode_transaksi' => 'TRX-24001',
                    'nama_pembeli' => 'Mbak Cahyani',
                    'total' => 30000,
                ],
                [
                    'tanggal' => '2024-02-01',
                    'kode_transaksi' => 'TRX-24002',
                    'nama_pembeli' => 'Bu Boniyati',
                    'total' => 30000,
                ],
                [
                    'tanggal' => '2024-07-11',
                    'kode_transaksi' => 'TRX-24003',
                    'nama_pembeli' => 'Wali Murid',
                    'total' => 16000,
                ],
                [
                    'tanggal' => '2024-09-11',
                    'kode_transaksi' => 'TRX-24004',
                    'nama_pembeli' => 'Ibu Lisa',
                    'total' => 5000,
                ],
                [
                    'tanggal' => '2024-10-03',
                    'kode_transaksi' => 'TRX-24005',
                    'nama_pembeli' => 'B. Nurvita',
                    'total' => 50000,
                ],
            ],
            'labaBersih' => 1650000,
            'jumlahProdukTerjual' => 126,
            'donasi' => 150000,
        ],
        2025 => [
            'penjualanBulanan' => [
                'Jan' => 150000,
                'Feb' => 210000,
                'Mar' => 180000,
                'Apr' => 260000,
                'Mei' => 230000,
                'Jun' => 315000,
                'Jul' => 290000,
                'Agu' => 340000,
                'Sep' => 310000,
                'Okt' => 420000,
                'Nov' => 390000,
                'Des' => 480000,
            ],

            'penjualanRombel' => [
                'Graha' => 410000,
                'Membatik' => 590000,
                'Perkayuan' => 330000,
                'Busana' => 510000,
                'Tataboga' => 460000,
                'Kecantikan' => 290000,
                'Logam' => 230000,
            ],

        'pembeli' => [
            [
                'tanggal' => '2025-01-10',
                'kode_transaksi' => 'TRX-25001',
                'nama_pembeli' => 'Ibu Guru',
                'total' => 20000,
            ],
            [
                'tanggal' => '2025-02-18',
                'kode_transaksi' => 'TRX-25002',
                'nama_pembeli' => 'Wali Murid',
                'total' => 35000,
            ],
            [
                'tanggal' => '2025-03-07',
                'kode_transaksi' => 'TRX-25003',
                'nama_pembeli' => 'Bu Lisa',
                'total' => 15000,
            ],
            [
                'tanggal' => '2025-04-02',
                'kode_transaksi' => 'TRX-25004',
                'nama_pembeli' => 'Dinas Sosial',
                'total' => 150000,
            ],
            [
                'tanggal' => '2025-05-11',
                'kode_transaksi' => 'TRX-25005',
                'nama_pembeli' => 'Mbak Cahyani',
                'total' => 30000,
            ],
            [
                'tanggal' => '2025-06-20',
                'kode_transaksi' => 'TRX-25006',
                'nama_pembeli' => 'Ibu Yuni',
                'total' => 25000,
            ],
            [
                'tanggal' => '2025-07-14',
                'kode_transaksi' => 'TRX-25007',
                'nama_pembeli' => 'Bu Boniyati',
                'total' => 30000,
            ],
            [
                'tanggal' => '2025-08-09',
                'kode_transaksi' => 'TRX-25008',
                'nama_pembeli' => 'Khusus Wali Murid',
                'total' => 125000,
            ],
            [
                'tanggal' => '2025-09-22',
                'kode_transaksi' => 'TRX-25009',
                'nama_pembeli' => 'Kecamatan Lowokwaru',
                'total' => 125000,
            ],
            [
                'tanggal' => '2025-10-30',
                'kode_transaksi' => 'TRX-25010',
                'nama_pembeli' => 'B. Nurvita',
                'total' => 50000,
            ],
            [
                'tanggal' => '2025-11-13',
                'kode_transaksi' => 'TRX-25011',
                'nama_pembeli' => 'Dinas Pendidikan',
                'total' => 175000,
            ],
            [
                'tanggal' => '2025-12-04',
                'kode_transaksi' => 'TRX-25012',
                'nama_pembeli' => 'Ibu Kepala Sekolah',
                'total' => 45000,
            ],
        ],
            'labaBersih' => 1650000,
            'jumlahProdukTerjual' => 126,
            'donasi' => 150000,
        ],
            2026 => [
                'penjualanBulanan' => [
                    'Jan' => 185000,
                    'Feb' => 245000,
                    'Mar' => 210000,
                    'Apr' => 325000,
                    'Mei' => 280000,
                ],

                'penjualanRombel' => [
                    'Graha' => 520000,
                    'Membatik' => 760000,
                    'Perkayuan' => 410000,
                    'Busana' => 650000,
                    'Tataboga' => 580000,
                    'Kecantikan' => 340000,
                    'Logam' => 245000,
                ],

                'pembeli' => [
                    [
                        'tanggal' => '2026-01-08',
                        'kode_transaksi' => 'TRX-26001',
                        'nama_pembeli' => 'Ibu Lisa',
                        'total' => 5000,
                    ],
                    [
                        'tanggal' => '2026-01-15',
                        'kode_transaksi' => 'TRX-26002',
                        'nama_pembeli' => 'Wali Murid',
                        'total' => 16000,
                    ],
                    [
                        'tanggal' => '2026-02-01',
                        'kode_transaksi' => 'TRX-26003',
                        'nama_pembeli' => 'Bu Boniyati',
                        'total' => 30000,
                    ],
                    [
                        'tanggal' => '2026-02-19',
                        'kode_transaksi' => 'TRX-26004',
                        'nama_pembeli' => 'Mbak Cahyani',
                        'total' => 30000,
                    ],
                    [
                        'tanggal' => '2026-03-05',
                        'kode_transaksi' => 'TRX-26005',
                        'nama_pembeli' => 'Ibu Yuni',
                        'total' => 25000,
                    ],
                    [
                        'tanggal' => '2026-04-11',
                        'kode_transaksi' => 'TRX-26006',
                        'nama_pembeli' => 'B. Nurvita',
                        'total' => 50000,
                    ],
                    [
                        'tanggal' => '2026-05-20',
                        'kode_transaksi' => 'TRX-26007',
                        'nama_pembeli' => 'Dinas Pendidikan',
                        'total' => 150000,
                    ],
                    [
                        'tanggal' => '2026-06-12',
                        'kode_transaksi' => 'TRX-26008',
                        'nama_pembeli' => 'Wali Murid',
                        'total' => 125000,
                    ],
                ],
                'labaBersih' => 815000,
                'jumlahProdukTerjual' => 185,
                'donasi' => 250000,
            ],
        ];

        $barangTerlaris = [
            [
                'nama_barang' => 'Pouch Batik',
                'jumlah_terjual' => 21,
            ],
            [
                'nama_barang' => 'Bando',
                'jumlah_terjual' => 13,
            ],
            [
                'nama_barang' => 'Dompet Batik',
                'jumlah_terjual' => 10,
            ],
            [
                'nama_barang' => 'Sabun Cuci Piring',
                'jumlah_terjual' => 8,
            ],
            [
                'nama_barang' => 'Taplak Meja Batik',
                'jumlah_terjual' => 6,
            ],
        ];

        $dataTahun = $dummyData[$tahun] ?? $dummyData[2026];

        $penjualanBulanan = $dataTahun['penjualanBulanan'];
        $penjualanRombel = $dataTahun['penjualanRombel'];
        $pembeli = $dataTahun['pembeli'];

        $labaBersih = $dataTahun['labaBersih'];
        $jumlahProdukTerjual = $dataTahun['jumlahProdukTerjual'];
        $totalPenjualan = array_sum($penjualanBulanan);

        $kasMasuk = $totalPenjualan;
        $kasKeluar = $kasMasuk - $labaBersih;
        $donasi = $dataTahun['donasi'];
        $saldo = $kasMasuk - $kasKeluar + $donasi;

        return view('admin.dashboard', compact(
            'tahun',
            'daftarTahun',
            'penjualanBulanan',
            'penjualanRombel',
            'pembeli',
            'barangTerlaris',
            'labaBersih',
            'jumlahProdukTerjual',
            'totalPenjualan',
            'saldo',
            'kasMasuk',
            'kasKeluar',
            'donasi'

        ));
    }
}
