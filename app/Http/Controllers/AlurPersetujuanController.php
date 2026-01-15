<?php

namespace App\Http\Controllers;

use App\Models\AlurPersetujuan;
use App\Models\LangkahAlurPersetujuan;
use App\Models\Peran;
use App\Models\Izin;
use Illuminate\Http\Request;

class AlurPersetujuanController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $data = AlurPersetujuan::query()
            ->where('instansi_id', auth()->user()->instansi_id)
            ->when($q, fn($query) => $query->where('nama', 'like', "%$q%"))
            ->latest('dibuat_pada')
            ->paginate(15)
            ->withQueryString();

        return view('alur_persetujuan.index', compact('data', 'q'));
    }

    public function create()
    {
        $peran = Peran::query()->orderBy('nama')->get();
        $izin = izin::query()->orderBy('nama')->get();
        return view('alur_persetujuan.create', compact('peran', 'izin'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'kode' => ['required', 'string', 'max:80'],
            'keterangan' => ['nullable', 'string'],
            'aktif' => ['nullable'],
            'berlaku_untuk' => ['required', 'string', 'max:80'],
            'langkah' => ['required', 'array', 'min:1'],
            'langkah.*.urutan' => ['required', 'integer', 'min:1'],
            'langkah.*.nama_langkah' => ['required', 'string', 'max:150'],
            'langkah.*.peran_id' => ['nullable', 'integer'],
            'langkah.*.izin_id' => ['nullable', 'string', 'max:120'],
            'langkah.*.wajib_catatan' => ['nullable'],
            'langkah.*.batas_waktu_hari' => ['nullable', 'integer', 'min:0'],
        ]);

        $alur = AlurPersetujuan::create([
            'instansi_id' => auth()->user()->instansi_id,
            'nama' => $validated['nama'],
            'kode' => $validated['kode'],
            'berlaku_untuk' => $validated['berlaku_untuk'],
            'keterangan' => $validated['keterangan'] ?? null,
            'aktif' => isset($validated['aktif']) ? 1 : 0,
            'dibuat_oleh' => auth()->user()->id,
        ]);

        foreach ($validated['langkah'] as $l) {
            LangkahAlurPersetujuan::create([
                'alur_persetujuan_id' => $alur->id,
                'no_langkah' => $l['urutan'],
                'nama_langkah' => $l['nama_langkah'],
                'peran_id' => $l['peran_id'] ?? null,
                'izin_id' => $l['izin_id'] ?? null,
                'wajib_catatan' => isset($l['wajib_catatan']) ? 1 : 0,
                'batas_waktu_hari' => $l['batas_waktu_hari'] ?? null,
            ]);
        }
        // $langkahSorted = collect($validated['langkah'])
        //     ->sortBy(fn($x) => (int) ($x['no_langkah'] ?? $x['urutan'] ?? 0))
        //     ->values();

        // $no = 1;
        // foreach ($langkahSorted as $l) {

        //     $tipe = $l['tipe_penyetuju'] ?? 'peran';

        //     LangkahAlurPersetujuan::create([
        //         'alur_persetujuan_id' => $alur->id,
        //         'no_langkah' => $no,
        //         'nama_langkah' => $l['nama_langkah'],

        //         'tipe_penyetuju' => $tipe,
        //         'peran_id' => $tipe === 'peran' || $tipe === 'unit_peran' ? ($l['peran_id'] ?? null) : null,
        //         'pengguna_id' => $tipe === 'pengguna' ? ($l['pengguna_id'] ?? null) : null,
        //         'unit_organisasi_id' => $tipe === 'unit_peran' ? ($l['unit_organisasi_id'] ?? null) : null,

        //         'harus_semua' => isset($l['harus_semua']) ? 1 : 0,

        //         'kondisi' => [
        //             'izin_id' => $l['izin_id'] ?? null,
        //         ],
        //     ]);

        //     $no++;
        // }

        return redirect()->route('alur-persetujuan.index')->with('success', 'Alur persetujuan berhasil dibuat.');
    }

    public function show(AlurPersetujuan $alur_persetujuan)
    {
        $alur_persetujuan->load('langkah.peran.izin');
        return view('alur_persetujuan.show', ['data' => $alur_persetujuan]);
    }

    public function edit(AlurPersetujuan $alur_persetujuan)
    {
        if ($alur_persetujuan->instansi_id !== auth()->user()->instansi_id) {
            abort(404);
        }

        $alur_persetujuan->load('langkah');

        $peran = Peran::query()
            ->where('instansi_id', auth()->user()->instansi_id)
            ->orderBy('nama')
            ->get();

        $izin = Izin::query()
            ->orderBy('nama')
            ->get();

        $langkah = $alur_persetujuan->langkah()
            ->orderBy('no_langkah')
            ->get()
            ->values();

        return view('alur_persetujuan.edit', [
            'data' => $alur_persetujuan,
            'langkah' => $langkah,
            'peran' => $peran,
            'izin' => $izin,
        ]);
    }

    public function update(Request $request, AlurPersetujuan $alur_persetujuan)
    {
        if ($alur_persetujuan->instansi_id !== auth()->user()->instansi_id) {
            abort(404);
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:200'],
            'kode' => ['required', 'string', 'max:80'],
            'berlaku_untuk' => ['required', 'string', 'max:80'],
            'status' => ['required', 'in:aktif,nonaktif'],

            'langkah' => ['required', 'array', 'min:1'],
            'langkah.*.no_langkah' => ['required', 'integer', 'min:1'],
            'langkah.*.nama_langkah' => ['required', 'string', 'max:150'],
            'langkah.*.peran_id' => ['nullable', 'integer'],
            'langkah.*.izin_id' => ['nullable', 'integer'],
            'langkah.*.wajib_catatan' => ['nullable'],
            'langkah.*.batas_waktu_hari' => ['nullable', 'integer', 'min:0'],
        ]);

        $alur_persetujuan->update([
            'nama' => $validated['nama'],
            'kode' => $validated['kode'],
            'berlaku_untuk' => $validated['berlaku_untuk'],
            'status' => $validated['status'],
        ]);

        LangkahAlurPersetujuan::where('alur_persetujuan_id', $alur_persetujuan->id)->delete();

        $langkahSorted = collect($validated['langkah'])
            ->sortBy(fn($x) => (int) $x['no_langkah'])
            ->values();

        $no = 1;
        foreach ($langkahSorted as $l) {
            LangkahAlurPersetujuan::create([
                'alur_persetujuan_id' => $alur_persetujuan->id,
                'no_langkah' => $no,
                'nama_langkah' => $l['nama_langkah'],
                'peran_id' => $l['peran_id'] ?? null,
                'izin_id' => $l['izin_id'] ?? null,
                'wajib_catatan' => isset($l['wajib_catatan']) ? 1 : 0,
                'batas_waktu_hari' => $l['batas_waktu_hari'] ?? null,
            ]);
            $no++;
        }

        return redirect()->route('alur-persetujuan.index')
            ->with('success', 'Alur persetujuan berhasil diperbarui.');
    }


    public function destroy(AlurPersetujuan $alur_persetujuan)
    {
        $alur_persetujuan->langkah()->delete();
        $alur_persetujuan->delete();

        return redirect()->route('alur-persetujuan.index')->with('success', 'Alur persetujuan berhasil dihapus.');
    }
}
