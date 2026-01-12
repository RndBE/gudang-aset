<?php

namespace App\Http\Controllers;

use App\Models\AlurPersetujuan;
use App\Models\LangkahAlurPersetujuan;
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
        return view('alur_persetujuan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'kode' => ['required', 'string', 'max:80'],
            'keterangan' => ['nullable', 'string'],
            'aktif' => ['nullable'],
            'langkah' => ['required', 'array', 'min:1'],
            'langkah.*.urutan' => ['required', 'integer', 'min:1'],
            'langkah.*.nama_langkah' => ['required', 'string', 'max:150'],
            'langkah.*.peran_id' => ['nullable', 'integer'],
            'langkah.*.izin_khusus' => ['nullable', 'string', 'max:120'],
            'langkah.*.wajib_catatan' => ['nullable'],
            'langkah.*.batas_waktu_hari' => ['nullable', 'integer', 'min:0'],
        ]);

        $alur = AlurPersetujuan::create([
            'instansi_id' => auth()->user()->instansi_id,
            'nama' => $validated['nama'],
            'kode' => $validated['kode'],
            'keterangan' => $validated['keterangan'] ?? null,
            'aktif' => isset($validated['aktif']) ? 1 : 0,
            'dibuat_oleh' => auth()->user()->id,
        ]);

        foreach ($validated['langkah'] as $l) {
            LangkahAlurPersetujuan::create([
                'alur_id' => $alur->id,
                'urutan' => $l['urutan'],
                'nama_langkah' => $l['nama_langkah'],
                'peran_id' => $l['peran_id'] ?? null,
                'izin_khusus' => $l['izin_khusus'] ?? null,
                'wajib_catatan' => isset($l['wajib_catatan']) ? 1 : 0,
                'batas_waktu_hari' => $l['batas_waktu_hari'] ?? null,
            ]);
        }

        return redirect()->route('alur-persetujuan.index')->with('success', 'Alur persetujuan berhasil dibuat.');
    }

    public function show(AlurPersetujuan $alur_persetujuan)
    {
        $alur_persetujuan->load('langkah');
        return view('alur_persetujuan.show', ['data' => $alur_persetujuan]);
    }

    public function edit(AlurPersetujuan $alur_persetujuan)
    {
        $alur_persetujuan->load('langkah');
        return view('alur_persetujuan.edit', ['data' => $alur_persetujuan]);
    }

    public function update(Request $request, AlurPersetujuan $alur_persetujuan)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'kode' => ['required', 'string', 'max:80'],
            'keterangan' => ['nullable', 'string'],
            'aktif' => ['nullable'],
        ]);

        $alur_persetujuan->update([
            'nama' => $validated['nama'],
            'kode' => $validated['kode'],
            'keterangan' => $validated['keterangan'] ?? null,
            'aktif' => isset($validated['aktif']) ? 1 : 0,
        ]);

        return redirect()->route('alur-persetujuan.show', $alur_persetujuan->id)
            ->with('success', 'Alur persetujuan berhasil diperbarui.');
    }

    public function destroy(AlurPersetujuan $alur_persetujuan)
    {
        $alur_persetujuan->langkah()->delete();
        $alur_persetujuan->delete();

        return redirect()->route('alur-persetujuan.index')->with('success', 'Alur persetujuan berhasil dihapus.');
    }
}
