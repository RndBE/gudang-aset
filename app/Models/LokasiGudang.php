<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiGudang extends Model
{
    protected $table = 'lokasi_gudang';
    public $timestamps = false;

    protected $fillable = [
        'gudang_id',
        'induk_id',
        'tipe_lokasi',
        'kode',
        'nama',
        'jalur',
        'bisa_picking',
        'status',
        'dibuat_pada',
        'diubah_pada',
    ];

    protected $casts = [
        'bisa_picking' => 'boolean',
    ];

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    public function induk()
    {
        return $this->belongsTo(LokasiGudang::class, 'induk_id');
    }
}
