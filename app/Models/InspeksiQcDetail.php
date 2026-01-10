<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspeksiQcDetail extends BaseModel
{
    protected $table = 'inspeksi_qc_detail';

    protected $fillable = [
        'inspeksi_qc_id',
        'penerimaan_detail_id',
        'hasil',
        'catatan_cacat',
        'qty_diterima',
        'qty_ditolak'
    ];

    protected $casts = [
        'qty_diterima' => 'decimal:4',
        'qty_ditolak' => 'decimal:4',
    ];

    public function inspeksiQc(): BelongsTo
    {
        return $this->belongsTo(InspeksiQc::class, 'inspeksi_qc_id');
    }
    public function penerimaanDetail(): BelongsTo
    {
        return $this->belongsTo(PenerimaanDetail::class, 'penerimaan_detail_id');
    }
}
