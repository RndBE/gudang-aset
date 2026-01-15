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

            $permintaan = PermintaanPersetujuan::create([
                'instansi_id' => auth()->user()->instansi_id,
                'alur_persetujuan_id' => $alur->id,
                'tipe_entitas' => $validated['tipe_entitas'],
                'id_entitas' => $validated['id_entitas'] ?? null,
                'nomor_persetujuan' => $nomor,
                'diminta_oleh' => auth()->user()->id,
                'diminta_pada' => now(),
                'status' => 'menunggu',
                'langkah_saat_ini' => 1,
                'ringkasan' => $validated['ringkasan'] ?? null,
            ]);

            foreach ($langkahAlur as $l) {
                LangkahPermintaanPersetujuan::create([
                    'permintaan_persetujuan_id' => $permintaan->id,
                    'no_langkah' => $l->no_langkah,
                    'nama_langkah' => $l->nama_langkah,
                    'peran_id' => $l->peran_id ?? null,
                    'izin_id' => $l->izin_id ?? null,
                    'status' => ($l->no_langkah == 1) ? 'menunggu' : 'menunggu',
                    'wajib_catatan' => $l->wajib_catatan ?? 0,
                    'batas_waktu_hari' => $l->batas_waktu_hari ?? null,
                ]);
            }

            return redirect()
                ->route('permintaan-persetujuan.index', $permintaan->id)
                ->with('success', 'Permintaan persetujuan berhasil dibuat.');
        });
    }



    public function show(PermintaanPersetujuan $permintaan_persetujuan)
    {
        $permintaan_persetujuan->load('alur', 'langkah');
        return view('permintaan_persetujuan.show', ['data' => $permintaan_persetujuan]);
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

        $langkahAktif->update([
            'status' => 'disetujui',
            'disetujui_oleh' => auth()->user()->id,
            'disetujui_pada' => now(),
            'catatan_keputusan' => $request->catatan_keputusan,
        ]);

        $nextNo = $permintaan_persetujuan->langkah_saat_ini + 1;

        $next = $permintaan_persetujuan->langkah()
            ->where('no_langkah', $nextNo)
            ->first();

        if ($next) {
            $permintaan_persetujuan->update([
                'langkah_saat_ini' => $nextNo,
                'status' => 'menunggu',
            ]);
        } else {
            $permintaan_persetujuan->update([
                'status' => 'disetujui',
            ]);
        }

        return back()->with('success', 'Persetujuan berhasil diproses.');
    }

    public function tolak(Request $request, PermintaanPersetujuan $permintaan_persetujuan)
    {
        $request->validate([
            'catatan' => ['required', 'string'],
        ]);

        $langkahAktif = $permintaan_persetujuan->langkah()
            ->where('urutan', $permintaan_persetujuan->langkah_aktif)
            ->first();

        if (!$langkahAktif || $langkahAktif->status !== 'menunggu') {
            return back()->with('error', 'Langkah aktif tidak valid.');
        }

        $langkahAktif->update([
            'status' => 'ditolak',
            'disetujui_oleh' => auth()->user()->id,
            'disetujui_pada' => now(),
            'catatan' => $request->catatan,
        ]);

        $permintaan_persetujuan->update([
            'status' => 'ditolak',
        ]);

        return back()->with('success', 'Permintaan persetujuan ditolak.');
    }
}
