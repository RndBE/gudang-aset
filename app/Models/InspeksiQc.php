<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspeksiQc extends BaseModel
{
    protected $table = 'inspeksi_qc';

    protected $fillable = [
        'penerimaan_id',
        'nomor_qc',
        'tanggal_qc',
        'pemeriksa_id',
        'status',
        'ringkasan'
    ];

    protected $casts = [
        'tanggal_qc' => 'date',
    ];

    public function penerimaan(): BelongsTo
    {
        return $this->belongsTo(Penerimaan::class, 'penerimaan_id');
    }
    public function pemeriksa(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pemeriksa_id');
    }
    public function detail(): HasMany
    {
        return $this->hasMany(InspeksiQcDetail::class, 'inspeksi_qc_id');
    }
}
