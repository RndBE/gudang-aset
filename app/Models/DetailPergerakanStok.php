<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPergerakanStok extends BaseModel
{
    protected $table = 'detail_pergerakan_stok';

    protected $fillable = [
        'pergerakan_stok_id',
        'barang_id',
        'dari_gudang_id',
        'dari_lokasi_id',
        'ke_gudang_id',
        'ke_lokasi_id',
        'no_lot',
        'tanggal_kedaluwarsa',
        'qty',
        'biaya_satuan'
    ];

    protected $casts = [
        'tanggal_kedaluwarsa' => 'date',
        'qty' => 'decimal:4',
        'biaya_satuan' => 'decimal:4',
    ];

    public function pergerakan(): BelongsTo
    {
        return $this->belongsTo(PergerakanStok::class, 'pergerakan_stok_id');
    }
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
