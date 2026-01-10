<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PesananPembelian extends BaseModel
{
    protected $table = 'pesanan_pembelian';

    protected $fillable = [
        'instansi_id',
        'unit_organisasi_id',
        'pemasok_id',
        'kontrak_id',
        'nomor_po',
        'tanggal_po',
        'tanggal_estimasi',
        'mata_uang',
        'subtotal',
        'pajak',
        'total',
        'status',
        'catatan',
        'dibuat_oleh'
    ];

    protected $casts = [
        'tanggal_po' => 'date',
        'tanggal_estimasi' => 'date',
        'subtotal' => 'decimal:2',
        'pajak' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }
    public function unitOrganisasi(): BelongsTo
    {
        return $this->belongsTo(UnitOrganisasi::class, 'unit_organisasi_id');
    }
    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }
    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(PesananPembelianDetail::class, 'pesanan_pembelian_id');
    }
    public function penerimaan(): HasMany
    {
        return $this->hasMany(Penerimaan::class, 'pesanan_pembelian_id');
    }
}
