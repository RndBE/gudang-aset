<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaldoStok extends BaseModel
{
    protected $table = 'saldo_stok';

    protected $fillable = [
        'instansi_id',
        'gudang_id',
        'lokasi_id',
        'barang_id',
        'no_lot',
        'tanggal_kedaluwarsa',
        'qty_tersedia',
        'qty_dipesan',
        'qty_bisa_dipakai',
        'pergerakan_terakhir_pada'
    ];

    protected $casts = [
        'tanggal_kedaluwarsa' => 'date',
        'qty_tersedia' => 'decimal:4',
        'qty_dipesan' => 'decimal:4',
        'qty_bisa_dipakai' => 'decimal:4',
        'pergerakan_terakhir_pada' => 'datetime',
    ];

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }
    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(LokasiGudang::class, 'lokasi_id');
    }
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
