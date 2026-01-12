<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanPersetujuan extends Model
{
    protected $table = 'permintaan_persetujuan';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'instansi_id',
        'alur_id',
        'tipe_entitas',
        'entitas_id',
        'judul',
        'status',
        'langkah_aktif',
        'dibuat_oleh',
    ];

    public function alur()
    {
        return $this->belongsTo(AlurPersetujuan::class, 'alur_id');
    }

    public function langkah()
    {
        return $this->hasMany(LangkahPermintaanPersetujuan::class, 'permintaan_id')->orderBy('urutan');
    }
}
