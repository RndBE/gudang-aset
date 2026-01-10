<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    protected $table = 'kategori_barang';
    public $timestamps = false;

    protected $fillable = [
        'instansi_id',
        'induk_id',
        'kode',
        'nama',
        'default_aset',
        'dibuat_pada',
        'diubah_pada',
    ];

    protected $casts = [
        'default_aset' => 'boolean',
    ];

    public function induk()
    {
        return $this->belongsTo(KategoriBarang::class, 'induk_id');
    }
}
