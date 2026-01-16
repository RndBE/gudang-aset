<?php

namespace App\Services;

use App\Models\AlurPersetujuan;
use App\Models\LangkahAlurPersetujuan;
use App\Models\PermintaanPersetujuan;
use App\Models\LangkahPermintaanPersetujuan;
use Illuminate\Support\Facades\DB;

class ApprovalService
{
    public function buatPermintaan(string $berlakuUntuk, string $tipeEntitas, int $idEntitas, ?string $ringkasan = null)
    {
        $instansiId = auth()->user()->instansi_id;

        $alur = AlurPersetujuan::query()
            ->where('instansi_id', $instansiId)
            ->where('status', 1)
            ->where('berlaku_untuk', $berlakuUntuk)
            ->first();

        if (!$alur) {
            throw new \Exception("Alur persetujuan untuk [$berlakuUntuk] tidak ditemukan / tidak aktif.");
        }
        // if (!$alur) {
        //     return [
        //         'ok' => false,
        //         'message' => "Alur persetujuan untuk [$berlakuUntuk] belum dibuat / belum aktif. Silakan buat dulu di menu Alur Persetujuan.",
        //     ];
        // }

        $langkahAlur = LangkahAlurPersetujuan::query()
            ->where('alur_persetujuan_id', $alur->id)
            ->orderBy('no_langkah')
            ->get();

        if ($langkahAlur->count() < 1) {
            throw new \Exception("Alur [$berlakuUntuk] belum memiliki langkah.");
        }

        return DB::transaction(function () use ($alur, $langkahAlur, $instansiId, $tipeEntitas, $idEntitas, $ringkasan) {

            $nomor = $this->generateNomor($alur->kode);

            $permintaan = PermintaanPersetujuan::create([
                'instansi_id' => $instansiId,
                'alur_persetujuan_id' => $alur->id,
                'tipe_entitas' => $tipeEntitas,
                'id_entitas' => $idEntitas,
                'nomor_persetujuan' => $nomor,
                'diminta_oleh' => auth()->user()->id,
                'diminta_pada' => now(),
                'status' => 'menunggu',
                'langkah_saat_ini' => 1,
                'ringkasan' => $ringkasan,
            ]);

            foreach ($langkahAlur as $l) {
                LangkahPermintaanPersetujuan::create([
                    'permintaan_persetujuan_id' => $permintaan->id,
                    // 'langkah_alur_id' => $l->id,
                    'no_langkah' => $l->no_langkah,
                    'nama_langkah' => $l->nama_langkah,
                    'status' => ($l->no_langkah == 1) ? 'menunggu' : 'menunggu',
                    'snapshot' => [
                        'tipe_penyetuju' => $l->tipe_penyetuju ?? null,
                        'peran_id' => $l->peran_id ?? null,
                        'izin_id' => data_get($l->kondisi, 'izin_id'),
                        'kondisi' => $l->kondisi,
                    ],
                ]);
            }

            return $permintaan;
        });
    }

    private function generateNomor(string $prefix): string
    {
        return strtoupper($prefix) . '-' . now()->format('YmdHis') . '-' . rand(100, 999);
    }
}
