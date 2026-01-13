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
        'alur_persetujuan_id',
        'tipe_entitas',
        'id_entitas',
        'nomor_persetujuan',
        'diminta_oleh',
        'diminta_pada',
        'status',
        'langkah_saat_ini',
        'ringkasan',
    ];

    public function alur()
    {
        return $this->belongsTo(AlurPersetujuan::class, 'alur_persetujuan_id');
    }

    public function langkah()
    {
        return $this->hasMany(LangkahPermintaanPersetujuan::class, 'permintaan_persetujuan_id')->orderBy('permintaan_persetujuan_id');
    }

    public function diminta()
    {
        return $this->belongsTo(Pengguna::class,'diminta_oleh');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }
}
