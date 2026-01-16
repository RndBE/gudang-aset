<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenghapusanAset extends Model
{
    protected $table = 'penghapusan_aset';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'instansi_id',
        'aset_id',
        'nomor_penghapusan',
        'tanggal_penghapusan',
        'metode',
        'alasan',
        'disetujui_oleh',
        'status',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_penghapusan' => 'date',
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }

    public function disetujui()
    {
        return $this->belongsTo(Pengguna::class, 'disetujui_oleh');
    }

    public function dibuat_oleh()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }
    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function permintaanPersetujuan()
    {
        return $this->hasOne(\App\Models\PermintaanPersetujuan::class, 'id_entitas', 'id')
            ->where('tipe_entitas', 'penghapusan_aset');
    }
}
