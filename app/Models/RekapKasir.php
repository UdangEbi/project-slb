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
        'total_transaksi',
        'total_penjualan',
        'uang_fisik',
        'selisih',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
