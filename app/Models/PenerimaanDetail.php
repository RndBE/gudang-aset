<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenerimaanDetail extends BaseModel
{
    protected $table = 'penerimaan_detail';

    protected $fillable = [
        'penerimaan_id',
        'barang_id',
        'po_detail_id',
        'qty_diterima',
        'no_lot',
        'tanggal_kedaluwarsa',
        'biaya_satuan',
        'lokasi_id',
        'catatan'
    ];

    protected $casts = [
        'qty_diterima' => 'decimal:4',
        'biaya_satuan' => 'decimal:4',
        'tanggal_kedaluwarsa' => 'date',
    ];

    public function penerimaan(): BelongsTo
    {
        return $this->belongsTo(Penerimaan::class, 'penerimaan_id');
    }
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
    public function poDetail(): BelongsTo
    {
        return $this->belongsTo(PesananPembelianDetail::class, 'po_detail_id');
    }
    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(LokasiGudang::class, 'lokasi_id');
    }
}
