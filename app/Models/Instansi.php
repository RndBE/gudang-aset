<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    protected $table = 'instansi';
    public $timestamps = true;

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'telepon',
        'email',
        'status',
        'dibuat_pada',
        'diubah_pada',
    ];

    public function unitOrganisasi()
    {
        return $this->hasMany(UnitOrganisasi::class, 'instansi_id');
    }

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'instansi_id');
    }

    public function peran()
    {
        return $this->hasMany(Peran::class, 'instansi_id');
    }
}
