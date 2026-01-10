<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatuanBarang extends Model
{
    protected $table = 'satuan_barang';
    public $timestamps = false;

    protected $fillable = [
        'kode',
        'nama',
        'dibuat_pada',
        'diubah_pada',
    ];
}
