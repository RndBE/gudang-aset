<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LangkahAlurPersetujuan extends Model
{
    protected $table = 'langkah_alur_persetujuan';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'alur_persetujuan_id',
        'no_langkah',
        'nama_langkah',
        'tipe_penyetuju',
        'peran_id',
        'penggunaan_id',
        'unit_penggunaan_id',
        'hapus_semua',
        'kondisi',
        'dibuat_pada',
        'diubah_pada',
        // 'izin_khusus',
        // 'wajib_catatan',
        // 'batas_waktu_hari',
    ];

    protected $casts = [
        'harus_semua    ' => 'boolean',
        'kondisi' => 'array'
    ];

    public function alur()
    {
        return $this->belongsTo(AlurPersetujuan::class, 'alur_persetujuan_id');
    }

    public function peran()
    {
        return $this->belongsTo(Peran::class, 'peran_id');
    }
}
