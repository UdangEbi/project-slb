<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapKasir extends Model
{
    use HasFactory;

    protected $table = 'rekap_kasir';
    protected $primaryKey = 'id_rekap';
    public $timestamps = false;

    protected $fillable = [
    'user_id',
    'tanggal',
    'waktu_buka',
    'waktu_tutup',
    'modal_awal',
    'total_transaksi',
    'total_penjualan',
    'total_kas_keluar',
    'saldo_akhir',
    'uang_fisik',
    'selisih',
    'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_buka' => 'datetime',
        'waktu_tutup' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
