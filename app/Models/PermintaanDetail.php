<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermintaanDetail extends BaseModel
{
    protected $table = 'permintaan_detail';

    protected $fillable = [
        'permintaan_id',
        'barang_id',
        'qty_diminta',
        'qty_disetujui',
        'qty_dipenuhi',
        'catatan'
    ];

    protected $casts = [
        'qty_diminta' => 'decimal:4',
        'qty_disetujui' => 'decimal:4',
        'qty_dipenuhi' => 'decimal:4',
    ];

    public function permintaan(): BelongsTo
    {
        return $this->belongsTo(Permintaan::class, 'permintaan_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
