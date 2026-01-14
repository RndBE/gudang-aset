<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengeluaran extends BaseModel
{
    protected $table = 'pengeluaran';

    protected $fillable = [
        'instansi_id',
        'gudang_id',
        'unit_organisasi_id',
        'permintaan_id',
        'nomor_pengeluaran',
        'tanggal_pengeluaran',
        'diserahkan_ke_pengguna_id',
        'diserahkan_ke_unit_id',
        'status',
        'catatan',
        'dibuat_oleh',
        'diposting_oleh',
    ];

    protected $casts = [
        'tanggal_pengeluaran' => 'datetime',
    ];

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    public function unitOrganisasi(): BelongsTo
    {
        return $this->belongsTo(UnitOrganisasi::class, 'unit_organisasi_id');
    }

    public function permintaan(): BelongsTo
    {
        return $this->belongsTo(Permintaan::class, 'permintaan_id');
    }

    public function diserahkanKePengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'diserahkan_ke_pengguna_id');
    }

    public function diserahkanKeUnit(): BelongsTo
    {
        return $this->belongsTo(UnitOrganisasi::class, 'diserahkan_ke_unit_id');
    }

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function dipostingOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'diposting_oleh');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(PengeluaranDetail::class, 'pengeluaran_id');
    }
}
