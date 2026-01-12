<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlurPersetujuan extends Model
{
    protected $table = 'alur_persetujuan';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'instansi_id',
        'kode',
        'nama',
        'berlaku_untuk',
        'status',
        'aturan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'aktif' => 'boolean'
    ];

    public function langkah()
    {
        return $this->hasMany(LangkahAlurPersetujuan::class, 'alur_persetujuan_id')->orderBy('alur_persetujuan_id');
    }
}
