<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAudit extends Model
{
    protected $table = 'log_audit';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = null;

    protected $fillable = [
        'instansi_id',
        'pengguna_id',
        'aksi',
        'nama_tabel',
        'id_rekaman',
        'tipe_referensi',
        'id_referensi',
        'ip_address',
        'user_agent',
        'data_lama',
        'data_baru',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
        'data_lama' => 'array',
        'data_baru' => 'array',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}
