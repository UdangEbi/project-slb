<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasKeluar extends Model
{
    use HasFactory;

    protected $table = 'kas_keluar';
    protected $primaryKey = 'id_kas_keluar';

    protected $fillable = [
        'tanggal',
        'user_id',
        'kategori_pengeluaran_id',
        'nominal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke user (kasir)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi ke kategori pengeluaran
     */
    public function kategori()
    {
        return $this->belongsTo(
            KategoriPengeluaran::class,
            'kategori_pengeluaran_id',
            'id_kategori_pengeluaran'
        );
    }
}
