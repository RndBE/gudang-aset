<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenugasanAset extends Model
{
    protected $table = 'penugasan_aset';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'instansi_id',
        'aset_id',
        'ditugaskan_ke_pengguna_id',
        'ditugaskan_ke_unit_id',
        'tanggal_tugas',
        'tanggal_kembali',
        'status',
        'nomor_dok_serah_terima',
        'catatan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_tugas' => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function dibuat_oleh()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function unit_ditugaskan()
    {
        return $this->belongsTo(UnitOrganisasi::class, 'ditugaskan_ke_unit_id');
    }

    public function petugas_ditugaskan()
    {
        return $this->belongsTo(Pengguna::class, 'ditugaskan_ke_pengguna_id');
    }

    public function permintaanPersetujuan()
    {
        return $this->hasOne(\App\Models\PermintaanPersetujuan::class, 'id_entitas', 'id')
            ->where('tipe_entitas', 'penugasan_aset');
    }
}
