<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    protected $table = 'pemasok';
    public $timestamps = false;

    protected $fillable = [
        'instansi_id',
        'kode',
        'nama',
        'npwp',
        'alamat',
        'nama_kontak',
        'telepon',
        'email',
        'status',
        'dibuat_pada',
        'diubah_pada',
    ];
}
