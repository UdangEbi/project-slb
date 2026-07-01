<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPengeluaran extends Model
{
    use HasFactory;

    protected $table = 'kategori_pengeluaran';
    protected $primaryKey = 'id_kategori_pengeluaran';

    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Relasi:
     * Satu kategori memiliki banyak kas keluar.
     */
    public function kasKeluar()
    {
        return $this->hasMany(KasKeluar::class, 'kategori_pengeluaran_id', 'id_kategori_pengeluaran');
    }
}
