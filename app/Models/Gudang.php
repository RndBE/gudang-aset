<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    protected $table = 'gudang';
    public $timestamps = false;

    protected $fillable = [
        'instansi_id',
        'unit_organisasi_id',
        'kode',
        'nama',
        'alamat',
        'status',
        'dibuat_pada',
        'diubah_pada',
    ];

    public function lokasi()
    {
        return $this->hasMany(LokasiGudang::class, 'gudang_id');
    }

    public function unitOrganisasi()
    {
        return $this->belongsTo(UnitOrganisasi::class, 'unit_organisasi_id');
    }

    public function gudang()
    {
        return $this->hasMany(Aset::class, 'gudang_saat_ini_id');
    }
}
