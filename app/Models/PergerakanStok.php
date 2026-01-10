<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PergerakanStok extends BaseModel
{
    protected $table = 'pergerakan_stok';

    protected $fillable = [
        'instansi_id',
        'nomor_pergerakan',
        'jenis_pergerakan',
        'tipe_referensi',
        'id_referensi',
        'tanggal_pergerakan',
        'gudang_id',
        'catatan',
        'diposting_oleh',
        'status',
        'dibuat_oleh'
    ];

    protected $casts = [
        'tanggal_pergerakan' => 'datetime',
    ];

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }
    public function dipostingOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'diposting_oleh');
    }
    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPergerakanStok::class, 'pergerakan_stok_id');
    }
}
