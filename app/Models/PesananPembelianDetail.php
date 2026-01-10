<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananPembelianDetail extends BaseModel
{
    protected $table = 'pesanan_pembelian_detail';

    protected $fillable = [
        'pesanan_pembelian_id',
        'barang_id',
        'deskripsi',
        'qty',
        'harga_satuan',
        'tarif_pajak',
        'nilai_pajak',
        'total_baris'
    ];

    protected $casts = [
        'qty' => 'decimal:4',
        'harga_satuan' => 'decimal:4',
        'tarif_pajak' => 'decimal:4',
        'nilai_pajak' => 'decimal:4',
        'total_baris' => 'decimal:4',
    ];

    public function pesananPembelian(): BelongsTo
    {
        return $this->belongsTo(PesananPembelian::class, 'pesanan_pembelian_id');
    }
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
