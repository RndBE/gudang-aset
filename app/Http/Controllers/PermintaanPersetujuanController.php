<?php

namespace App\Http\Controllers;

use App\Models\PermintaanPersetujuan;
use App\Models\AlurPersetujuan;
use App\Models\LangkahAlurPersetujuan;
use App\Models\LangkahPermintaanPersetujuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PermintaanPersetujuanController extends Controller
{
    private function generateNomorPersetujuan(string $kodeAlur): string
    {
        $date = now()->format('Ymd');
        $rand = strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
        return "{$kodeAlur}-{$date}-{$rand}";
    }

    public function index(Request $request)
    {
        $q = $request->query('q');
        $status = $request->query('status');

        $data = PermintaanPersetujuan::query()
            ->with('alur')
            ->where('instansi_id', auth()->user()->instansi_id)
            ->when(
                $q,
                fn($query) =>
                $query->where('judul', 'like', "%$q%")
                    ->orWhere('tipe_entitas', 'like', "%$q%")
            )
            ->when($status, fn($query) => $query->where('status', $status))
            ->latest('dibuat_pada')
            ->paginate(15)
            ->withQueryString();

        return view('permintaan_persetujuan.index', compact('data', 'q', 'status'));
    }

    public function create(Request $request)
    {
        $q = $request->query('q');

        $alur = AlurPersetujuan::query()
            ->where('instansi_id', auth()->user()->instansi_id)
            ->where('status', 'aktif')
            ->when(
                $q,
                fn($query) => $query
                    ->where('nama', 'like', "%$q%")
                    ->orWhere('kode', 'like', "%$q%")
                    ->orWhere('berlaku_untuk', 'like', "%$q%")
            )
            ->orderBy('nama')
            ->get();

        return view('permintaan_persetujuan.create', compact('alur', 'q'));
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'alur_persetujuan_id' => ['required', 'integer'],
    //         'tipe_entitas' => ['required', 'string', 'max:80'],
    //         'id_entitas' => ['required', 'integer', 'min:1'],
    //         'ringkasan' => ['nullable', 'string'],
    //     ]);

    //     $alur = AlurPersetujuan::query()
    //         ->where('id', $validated['alur_persetujuan_id'])
    //         ->where('instansi_id', auth()->user()->instansi_id)
    //         ->where('status', 'aktif')
    //         ->first();

    //     if (!$alur) {
    //         return back()->with('error', 'Alur persetujuan tidak ditemukan / tidak aktif.')->withInput();
    //     }

    //     $langkahAlur = LangkahAlurPersetujuan::query()
    //         ->where('alur_persetujuan_id', $alur->id)
    //         ->orderBy('no_langkah')
    //         ->get();

    //     if ($langkahAlur->count() < 1) {
    //         return back()->with('error', 'Alur persetujuan belum memiliki langkah.')->withInput();
    //     }

    //     return DB::transaction(function () use ($validated, $alur, $langkahAlur) {

    //         $nomor = $this->generateNomorPersetujuan($alur->kode);

    //         $permintaan = PermintaanPersetujuan::create([
    //             'instansi_id' => auth()->user()->instansi_id,
    //             'alur_persetujuan_id' => $alur->id,
    //             'tipe_entitas' => $validated['tipe_entitas'],
    //             'id_entitas' => $validated['id_entitas'] ?? null,
    //             'nomor_persetujuan' => $nomor,
    //             'diminta_oleh' => auth()->user()->id,
    //             'diminta_pada' => now(),
    //             'status' => 'menunggu',
    //             'langkah_saat_ini' => 1,
    //             'ringkasan' => $validated['ringkasan'] ?? null,
    //         ]);

    //         foreach ($langkahAlur as $l) {
    //             LangkahPermintaanPersetujuan::create([
    //                 'permintaan_persetujuan_id' => $permintaan->id,
    //                 'no_langkah' => $l->no_langkah,
    //                 'nama_langkah' => $l->nama_langkah,
    //                 'peran_id' => $l->peran_id ?? null,
    //                 'izin_id' => $l->izin_id ?? null,
    //                 'status' => ($l->no_langkah == 1) ? 'menunggu' : 'menunggu',
    //                 'wajib_catatan' => $l->wajib_catatan ?? 0,
    //                 'batas_waktu_hari' => $l->batas_waktu_hari ?? null,
    //             ]);
    //         }

    //         return redirect()
    //             ->route('permintaan-persetujuan.index', $permintaan->id)
    //             ->with('success', 'Permintaan persetujuan berhasil dibuat.');
    //     });
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alur_persetujuan_id' => ['required', 'integer'],
            'tipe_entitas' => ['required', 'string', 'max:80'],
            'id_entitas' => ['required', 'integer', 'min:1'],
            'ringkasan' => ['nullable', 'string'],
        ]);

        $alur = AlurPersetujuan::query()
            ->where('id', $validated['alur_persetujuan_id'])
            ->where('instansi_id', auth()->user()->instansi_id)
            ->where('status', 'aktif')
            ->first();

        if (!$alur) {
            return back()->with('error', 'Alur persetujuan tidak ditemukan / tidak aktif.')->withInput();
        }

        $langkahAlur = LangkahAlurPersetujuan::query()
            ->where('alur_persetujuan_id', $alur->id)
            ->orderBy('no_langkah')
            ->get();

        if ($langkahAlur->count() < 1) {
            return back()->with('error', 'Alur persetujuan belum memiliki langkah.')->withInput();
        }

        return DB::transaction(function () use ($validated, $alur, $langkahAlur) {

            $nomor = $this->generateNomorPersetujuan($alur->kode);

            $firstNo = (int) $langkahAlur->first()->no_langkah;

            $permintaan = PermintaanPersetujuan::create([
                'instansi_id' => auth()->user()->instansi_id,
                'alur_persetujuan_id' => $alur->id,
                'tipe_entitas' => $validated['tipe_entitas'],
                'id_entitas' => $validated['id_entitas'],
                'nomor_persetujuan' => $nomor,
                'diminta_oleh' => auth()->user()->id,
                'diminta_pada' => now(),
                'status' => 'menunggu',
                'langkah_saat_ini' => $firstNo,
                'ringkasan' => $validated['ringkasan'] ?? null,
            ]);

            foreach ($langkahAlur as $l) {
                LangkahPermintaanPersetujuan::create([
                    'permintaan_persetujuan_id' => $permintaan->id,
                    'no_langkah' => (int) $l->no_langkah,
                    'nama_langkah' => $l->nama_langkah,

                    'status' => ((int) $l->no_langkah === $firstNo) ? 'menunggu' : 'dilewati',

                    'snapshot' => [
                        'tipe_penyetuju' => $l->tipe_penyetuju ?? null,
                        'peran_id' => $l->peran_id ?? null,
                        // ✅ izin disimpan di kondisi langkah alur
                        'izin_id' => data_get($l->kondisi, 'izin_id'),
                        // simpan juga kondisi lengkap untuk kebutuhan rule lain
                        'kondisi' => $l->kondisi,
                    ],
                ]);
            }

            return redirect()
                ->route('permintaan-persetujuan.index', $permintaan->id)
                ->with('success', 'Permintaan persetujuan berhasil dibuat.');
        });
    }





    // public function show(PermintaanPersetujuan $permintaan_persetujuan)
    // {
    //     $permintaan_persetujuan->load([
    //         'alur',
    //         'langkah.diputuskan',
    //     ]);

    //     $step = $permintaan_persetujuan->langkah
    //         ->firstWhere('no_langkah', $permintaan_persetujuan->langkah_saat_ini);

    //     $bolehApproveStep = false;

    //     if ($step && $step->status === 'menunggu') {

    //         $tipe = data_get($step->snapshot, 'tipe_penyetuju');
    //         $peranId = (int) data_get($step->snapshot, 'peran_id');

    //         if ($tipe === 'peran' && $peranId > 0) {
    //             $bolehApproveStep = ((int) auth()->user()->id === $peranId);
    //         }
    //     }

    //     return view('permintaan_persetujuan.show', [
    //         'data' => $permintaan_persetujuan,
    //         'step' => $step,
    //         'bolehApproveStep' => $bolehApproveStep,
    //     ]);
    // }
    public function show(PermintaanPersetujuan $permintaan_persetujuan)
    {
        $permintaan_persetujuan->load([
            'alur',
            'langkah.diputuskan',
        ]);

        $step = $permintaan_persetujuan->langkah
            ->firstWhere('no_langkah', $permintaan_persetujuan->langkah_saat_ini);

        $bolehApproveStep = false;

        if ($step && $step->status === 'menunggu') {

            $tipe = data_get($step->snapshot, 'tipe_penyetuju');
            $peranId = (int) data_get($step->snapshot, 'peran_id');

            if ($tipe === 'peran' && $peranId > 0) {
                $bolehApproveStep = ((int) auth()->user()->id === $peranId);
            }
        }

        // ==========================================
        // ✅ ambil entitas sesuai tipe_entitas
        // ==========================================
        $entitas = null;

        $tipeEntitas = $permintaan_persetujuan->tipe_entitas;
        $idEntitas = (int) $permintaan_persetujuan->id_entitas;

        if ($tipeEntitas === 'penugasan_aset') {
            $entitas = \App\Models\PenugasanAset::query()
                ->with([
                    'aset.barang',
                    'aset.gudang',
                    'petugas_ditugaskan',
                    'unit_ditugaskan',
                    'dibuat_Oleh',
                ])
                ->where('instansi_id', auth()->user()->instansi_id)
                ->find($idEntitas);
        }

        if ($tipeEntitas === 'peminjaman_aset') {
            $entitas = \App\Models\PeminjamanAset::query()
                ->with([
                    'aset.barang',
                    'aset.gudang',
                    'peminjam_pengguna',
                    'peminjam_unit',
                    'dibuat_Oleh',
                ])
                ->where('instansi_id', auth()->user()->instansi_id)
                ->find($idEntitas);
        }

        if ($tipeEntitas === 'penghapusan_aset') {
            $entitas = \App\Models\PenghapusanAset::query()
                ->with([
                    'aset.barang',
                    'aset.gudang',
                    'dibuat_Oleh',
                ])
                ->where('instansi_id', auth()->user()->instansi_id)
                ->find($idEntitas);
        }

        return view('permintaan_persetujuan.show', [
            'data' => $permintaan_persetujuan,
            'step' => $step,
            'bolehApproveStep' => $bolehApproveStep,
            'entitas' => $entitas,
        ]);
    }

    private function syncStatusEntitas(PermintaanPersetujuan $pp, string $statusAkhir): void
    {
        $tipe = $pp->tipe_entitas;
        $idEntitas = (int) $pp->id_entitas;

        if ($tipe === 'penugasan_aset') {
            $penugasan = \App\Models\PenugasanAset::find($idEntitas);
            if (!$penugasan) return;

            if ($statusAkhir === 'disetujui') {
                $penugasan->update(['status' => 'sedang ditugaskan']);
                \App\Models\Aset::where('id', $penugasan->aset_id)->update([
                    'status_siklus' => 'ditugaskan',
                    'pemegang_pengguna_id' => $penugasan->ditugaskan_ke_pengguna_id,
                    'unit_organisasi_saat_ini_id' => $penugasan->ditugaskan_ke_unit_id,
                ]);
            } else {
                $penugasan->update(['status' => 'dibatalkan']);
                \App\Models\Aset::where('id', $penugasan->aset_id)->update([
                    'status_siklus' => 'tersedia',
                ]);
            }

            return;
        }

        if ($tipe === 'peminjaman_aset') {
            $peminjaman = \App\Models\PeminjamanAset::find($idEntitas);
            if (!$peminjaman) return;

            if ($statusAkhir === 'disetujui') {
                $peminjaman->update(['status' => 'aktif']);
                \App\Models\Aset::where('id', $peminjaman->aset_id)->update([
                    'status_siklus' => 'dipinjam',
                    'pemegang_pengguna_id' => $peminjaman->peminjam_pengguna_id,
                    'unit_organisasi_saat_ini_id' => $peminjaman->peminjam_unit_id,
                ]);
            } else {
                $peminjaman->update(['status' => 'dibatalkan']);
                \App\Models\Aset::where('id', $peminjaman->aset_id)->update([
                    'status_siklus' => 'tersedia',
                ]);
            }

            return;
        }

        if ($tipe === 'penghapusan_aset') {
            $hapus = \App\Models\PenghapusanAset::find($idEntitas);
            if (!$hapus) return;

            if ($statusAkhir === 'disetujui') {
                $hapus->update(['status' => 'disetujui']); // atau 'dihapus'
                \App\Models\Aset::where('id', $hapus->aset_id)->update([
                    'status_siklus' => 'dihapus',
                ]);
            } else {
                $hapus->update(['status' => 'dibatalkan']);
                \App\Models\Aset::where('id', $hapus->aset_id)->update([
                    'status_siklus' => 'tersedia',
                ]);
            }

            return;
        }
    }


    public function setujui(Request $request, PermintaanPersetujuan $permintaan_persetujuan)
    {
        $request->validate([
            'catatan_keputusan' => ['nullable', 'string'],
        ]);

        $langkahAktif = $permintaan_persetujuan->langkah()
            ->where('no_langkah', $permintaan_persetujuan->langkah_saat_ini)
            ->first();

        if (!$langkahAktif || $langkahAktif->status !== 'menunggu') {
            return back()->with('error', 'Langkah aktif tidak valid.');
        }

        // ✅ VALIDASI BERDASARKAN SNAPSHOT (bukan peran_izin)
        $tipe = data_get($langkahAktif->snapshot, 'tipe_penyetuju');
        $peranId = data_get($langkahAktif->snapshot, 'peran_id');

        if ($tipe === 'peran' && $peranId) {
            if ((string) auth()->user()->id !== (string) $peranId) {
                return back()->with('error', 'Anda tidak berhak menyetujui langkah ini.');
            }
        }

        $langkahAktif->update([
            'status' => 'disetujui',
            'diputuskan_oleh' => auth()->user()->id,
            'diputuskan_pada' => now(),
            'catatan_keputusan' => $request->catatan_keputusan,
        ]);

        $nextNo = (int) $permintaan_persetujuan->langkah_saat_ini + 1;

        $next = $permintaan_persetujuan->langkah()
            ->where('no_langkah', $nextNo)
            ->first();

        if ($next) {
            $next->update([
                'status' => 'menunggu',
            ]);

            $permintaan_persetujuan->update([
                'langkah_saat_ini' => $nextNo,
                'status' => 'menunggu',
            ]);
        } else {
            $permintaan_persetujuan->update([
                'status' => 'disetujui',
            ]);
            // ✅ SYNC status entitas
            $this->syncStatusEntitas($permintaan_persetujuan, 'disetujui');
        }
        // // ✅ Final approve
        // $permintaan_persetujuan->update([
        //     'status' => 'disetujui',
        // ]);

        // ✅ SYNC status entitas
        // $this->syncStatusEntitas($permintaan_persetujuan, 'disetujui');

        return back()->with('success', 'Persetujuan berhasil diproses.');
    }


    public function tolak(Request $request, PermintaanPersetujuan $permintaan_persetujuan)
    {
        $request->validate([
            'catatan_keputusan' => ['required', 'string'],
        ]);

        $langkahAktif = $permintaan_persetujuan->langkah()
            ->where('no_langkah', $permintaan_persetujuan->langkah_saat_ini)
            ->first();

        if (!$langkahAktif || $langkahAktif->status !== 'menunggu') {
            return back()->with('error', 'Langkah aktif tidak valid.');
        }

        // ✅ VALIDASI BERDASARKAN SNAPSHOT
        $tipe = data_get($langkahAktif->snapshot, 'tipe_penyetuju');
        $peranId = data_get($langkahAktif->snapshot, 'peran_id');

        if ($tipe === 'peran' && $peranId) {
            if ((string) auth()->user()->id !== (string) $peranId) {
                return back()->with('error', 'Anda tidak berhak menolak langkah ini.');
            }
        }

        $langkahAktif->update([
            'status' => 'ditolak',
            'diputuskan_oleh' => auth()->user()->id,
            'diputuskan_pada' => now(),
            'catatan_keputusan' => $request->catatan_keputusan,
        ]);

        // ✅ opsional: langkah setelahnya dibuat dibatalkan/ tidak berlaku
        $permintaan_persetujuan->langkah()
            ->where('no_langkah', '>', (int) $permintaan_persetujuan->langkah_saat_ini)
            ->whereIn('status', ['belum', 'menunggu'])
            ->update([
                'status' => 'dibatalkan',
            ]);

        $permintaan_persetujuan->update([
            'status' => 'ditolak',
        ]);

        // ✅ SYNC status entitas
        $this->syncStatusEntitas($permintaan_persetujuan, 'ditolak');

        return back()->with('success', 'Permintaan persetujuan ditolak.');
    }
}
