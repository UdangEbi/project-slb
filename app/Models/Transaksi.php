<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'no_nota',
        'tanggal',
        'user_id',
        'nama_pembeli',
        'no_tlp',
        'instansi',
        'total',
        'diskon',
        'grand_total',
        'donasi',
        'metode_pembayaran',
        'bayar',
        'kembalian',
        'donasi',
        'status',
        'id_rekap',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id', 'id_transaksi');
    }

    public function rekapKasir()
    {
        return $this->belongsTo(
            RekapKasir::class,
            'id_rekap',
            'id_rekap'
        );
    }
}
