<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaldoStok extends Model
{
    protected $table = 'saldo_stok';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $guarded = [];

    protected $casts = [
        'tanggal_kedaluwarsa' => 'date',
        'pergerakan_terakhir_pada' => 'datetime',
        'qty_tersedia' => 'decimal:4',
        'qty_dipesan' => 'decimal:4',
        'qty_bisa_dipakai' => 'decimal:4',
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(LokasiGudang::class, 'lokasi_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
