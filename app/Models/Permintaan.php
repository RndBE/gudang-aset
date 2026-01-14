<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permintaan extends BaseModel
{
    protected $table = 'permintaan';

    protected $fillable = [
        'instansi_id',
        'unit_organisasi_id',
        'nomor_permintaan',
        'tanggal_permintaan',
        'pemohon_id',
        'tipe_permintaan',
        'prioritas',
        'status',
        'tujuan',
        'dibutuhkan_pada',
        'catatan_persetujuan',
        'dibuat_oleh'
    ];

    protected $casts = [
        'tanggal_permintaan' => 'datetime',
        'dibutuhkan_pada' => 'date',
    ];

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function unitOrganisasi(): BelongsTo
    {
        return $this->belongsTo(UnitOrganisasi::class, 'unit_organisasi_id');
    }

    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pemohon_id');
    }

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(PermintaanDetail::class, 'permintaan_id');
    }
}
