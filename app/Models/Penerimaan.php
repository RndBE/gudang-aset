<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penerimaan extends BaseModel
{
    protected $table = 'penerimaan';

    protected $fillable = [
        'instansi_id',
        'gudang_id',
        'pemasok_id',
        'pesanan_pembelian_id',
        'nomor_penerimaan',
        'tanggal_penerimaan',
        'diterima_oleh',
        'status',
        'catatan',
        'dibuat_oleh'
    ];

    protected $casts = [
        'tanggal_penerimaan' => 'date',
    ];

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }
    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }
    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }
    public function pesananPembelian(): BelongsTo
    {
        return $this->belongsTo(PesananPembelian::class, 'pesanan_pembelian_id');
    }
    public function diterimaOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'diterima_oleh');
    }
    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(PenerimaanDetail::class, 'penerimaan_id');
    }
    public function qc(): HasOne
    {
        return $this->hasOne(InspeksiQc::class, 'penerimaan_id');
    }

    public function aset(): HasMany
    {
        return $this->hasMany(Aset::class, 'penerimaan_id');
    }
}
