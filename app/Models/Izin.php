<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    protected $table = 'izin';
    public $timestamps = true;

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi'
    ];

    public function peran()
    {
        return $this->belongsToMany(Peran::class, 'peran_izin', 'izin_id', 'peran_id')
            ->withPivot(['dibuat_pada']);
    }
}
