<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    public $timestamps = false;

    protected $fillable = [
        'instansi_id',
        'kategori_id',
        'satuan_id',
        'sku',
        'nama',
        'merek',
        'model',
        'spesifikasi',
        'gambar',
        'tipe_barang',
        'metode_pelacakan',
        'stok_minimum',
        'titik_pesan_ulang',
        'status',
        'dibuat_pada',
        'diubah_pada',
    ];

    protected $casts = [
        'spesifikasi' => 'array',
        'stok_minimum' => 'decimal:4',
        'titik_pesan_ulang' => 'decimal:4',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }

    public function satuan()
    {
        return $this->belongsTo(SatuanBarang::class, 'satuan_id');
    }
}
