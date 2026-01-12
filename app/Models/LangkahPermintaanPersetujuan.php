<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LangkahPermintaanPersetujuan extends Model
{
    protected $table = 'langkah_permintaan_persetujuan';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'permintaan_id',
        'langkah_alur_id',
        'urutan',
        'nama_langkah',
        'status',
        'disetujui_oleh',
        'disetujui_pada',
        'catatan',
    ];

    protected $casts = [
        'disetujui_pada' => 'datetime',
    ];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanPersetujuan::class, 'permintaan_id');
    }

    public function langkahAlur()
    {
        return $this->belongsTo(LangkahAlurPersetujuan::class, 'langkah_alur_id');
    }
}
