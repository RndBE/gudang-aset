<?php

namespace App\Models;

class UrutanNomorDokumen extends BaseModel
{
    protected $table = 'urutan_nomor_dokumen';

    protected $fillable = [
        'instansi_id',
        'unit_organisasi_id',
        'tipe_dokumen',
        'tahun',
        'nomor_terakhir',
        'awalan',
        'akhiran',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'nomor_terakhir' => 'integer',
    ];
}
