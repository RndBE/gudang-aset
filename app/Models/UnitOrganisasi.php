<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitOrganisasi extends Model
{
    protected $table = 'unit_organisasi';
    public $timestamps = true;

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'instansi_id',
        'induk_id',
        'tipe_unit',
        'kode',
        'nama',
        'alamat',
        'telepon',
        'email',
        'status'
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function induk()
    {
        return $this->belongsTo(UnitOrganisasi::class, 'induk_id');
    }

    public function anak()
    {
        return $this->hasMany(UnitOrganisasi::class, 'induk_id');
    }

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'unit_organisasi_id');
    }
}
