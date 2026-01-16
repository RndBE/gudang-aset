<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LangkahPermintaanPersetujuan extends Model
{
    protected $table = 'langkah_permintaan_persetujuan';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'permintaan_persetujuan_id',
        'langkah_alur_id',
        'no_langkah',
        'nama_langkah',
        'status',
        'diputuskan_oleh',
        'diputuskan_pada',
        'catatan_keputusan',
        'snapshot',
    ];

    protected $casts = [
        'diputuskan_pada' => 'datetime',
        'snapshot' => 'array'
    ];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanPersetujuan::class, 'permintaan_persetujuan_id');
    }

    public function langkahAlur()
    {
        return $this->belongsTo(LangkahAlurPersetujuan::class, 'langkah_alur_id');
    }

    public function diputuskan()
    {
        return $this->belongsTo(Pengguna::class, 'diputuskan_oleh');
    }

    public function izinIdDariKondisi(): ?int
    {
        $izinId = data_get($this->langkahAlur?->kondisi, 'izin_id');
        return $izinId ? (int) $izinId : null;
    }

    public function bolehDiputuskanOleh(Pengguna $user): bool
    {
        $izinId = $this->izinIdDariKondisi();
        if (!$izinId) return false;

        $izinKode = \App\Models\Izin::where('id', $izinId)->value('kode');
        if (!$izinKode) return false;

        return $user->punyaIzin($izinKode);
    }
}
