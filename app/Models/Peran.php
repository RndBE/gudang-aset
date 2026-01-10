<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peran extends Model
{
    protected $table = 'peran';
    public $timestamps = true;

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'instansi_id',
        'kode',
        'nama',
        'deskripsi'
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function izin()
    {
        return $this->belongsToMany(Izin::class, 'peran_izin', 'peran_id', 'izin_id')
            ->withPivot(['dibuat_pada']);
    }

    public function pengguna()
    {
        return $this->belongsToMany(Pengguna::class, 'pengguna_peran', 'peran_id', 'pengguna_id')
            ->withPivot(['dibuat_pada']);
    }
}
