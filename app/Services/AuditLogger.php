<?php

namespace App\Services;

use App\Models\LogAudit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public static function log(
        int $instansiId,
        string $aksi,
        ?string $namaTabel = null,
        ?int $idRekaman = null,
        ?string $tipeReferensi = null,
        ?int $idReferensi = null,
        $dataLama = null,
        $dataBaru = null,
        ?int $penggunaId = null
    ): LogAudit {
        $u = Auth::user();

        return LogAudit::create([
            'instansi_id' => $instansiId,
            'pengguna_id' => $penggunaId ?? ($u?->id),
            'aksi' => $aksi,
            'nama_tabel' => $namaTabel,
            'id_rekaman' => $idRekaman,
            'tipe_referensi' => $tipeReferensi,
            'id_referensi' => $idReferensi,
            'ip_address' => Request::ip(),
            'user_agent' => substr((string) Request::userAgent(), 0, 500),
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
        ]);
    }
}
