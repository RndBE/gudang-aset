<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanAset extends Model
{
    protected $table = 'peminjaman_aset';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'instansi_id',
        'aset_id',
        'peminjam_pengguna_id',
        'peminjam_unit_id',
        'tanggal_mulai',
        'jatuh_tempo',
        'tanggal_kembali',
        'status',
        'tujuan',
        'kondisi_keluar',
        'kondisi_masuk',
        'nomor_dok_serah_terima',
        'catatan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'jatuh_tempo' => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }

    public function dibuat_oleh()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function peminjam_pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'peminjam_pengguna_id');
    }

    public function peminjam_unit()
    {
        return $this->belongsTo(UnitOrganisasi::class, 'peminjam_unit_id');
    }
}
