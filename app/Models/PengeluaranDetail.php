<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengeluaranDetail extends BaseModel
{
    protected $table = 'pengeluaran_detail';

    protected $fillable = [
        'pengeluaran_id',
        'barang_id',
        'lokasi_id',
        'no_lot',
        'tanggal_kedaluwarsa',
        'qty',
        'biaya_satuan',
    ];

    protected $casts = [
        'tanggal_kedaluwarsa' => 'date',
        'qty' => 'decimal:4',
        'biaya_satuan' => 'decimal:4',
    ];

    public function pengeluaran(): BelongsTo
    {
        return $this->belongsTo(Pengeluaran::class, 'pengeluaran_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(LokasiGudang::class, 'lokasi_id');
    }
}
