<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use Notifiable;

    protected $table = 'pengguna';
    public $timestamps = true;

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'instansi_id',
        'unit_organisasi_id',
        'username',
        'email',
        'telepon',
        'hash_password',
        'nama_lengkap',
        'nip_nrk',
        'pangkat',
        'jabatan',
        'status',
        'login_terakhir_pada',
        'remember_token'
    ];

    protected $hidden = [
        'hash_password',
        'remember_token'
    ];

    public function getAuthPassword()
    {
        return $this->hash_password;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function unitOrganisasi()
    {
        return $this->belongsTo(UnitOrganisasi::class, 'unit_organisasi_id');
    }

    public function peran()
    {
        return $this->belongsToMany(Peran::class, 'pengguna_peran', 'pengguna_id', 'peran_id')
            ->withPivot(['dibuat_pada']);
    }

    public function punyaPeran(string $kode): bool
    {
        return $this->peran()->where('kode', $kode)->exists();
    }

    public function punyaIzin(string $kode): bool
    {
        return $this->peran()
            ->whereHas('izin', fn($q) => $q->where('kode', $kode))
            ->exists();
    }
}
