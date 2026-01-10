<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    protected $table = 'aset';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'instansi_id',
        'barang_id',

        'tag_aset',
        'no_serial',
        'imei',
        'no_mesin',
        'no_rangka',
        'no_polisi',

        'tanggal_beli',
        'penerimaan_id',

        'unit_organisasi_saat_ini_id',
        'gudang_saat_ini_id',
        'lokasi_saat_ini_id',

        'pemegang_pengguna_id',

        'status_kondisi',
        'status_siklus',

        'biaya_perolehan',
        'mata_uang',

        'extra',
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
        'extra' => 'array',
        'biaya_perolehan' => 'decimal:2',
    ];

    public function barang()
    {
        return $this->belongsTo(\App\Models\Barang::class, 'barang_id');
    }

    public function penerimaan()
    {
        return $this->belongsTo(\App\Models\Penerimaan::class, 'penerimaan_id');
    }

    public function gudang()
    {
        return $this->belongsTo(\App\Models\Gudang::class, 'gudang_saat_ini_id');
    }
}
