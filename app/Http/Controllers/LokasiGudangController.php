<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Gudang;
use App\Models\LokasiGudang;
use Illuminate\Http\Request;

class LokasiGudangController extends Controller
{
    public function index(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $gudang = Gudang::where('instansi_id', $instansiId)->orderBy('nama')->get();

        $gudangId = $request->query('gudang_id');

        $q = LokasiGudang::query()->with(['gudang', 'induk'])
            ->whereHas('gudang', fn($x) => $x->where('instansi_id', $instansiId))
            ->orderBy('gudang_id')
            ->orderBy('tipe_lokasi')
            ->orderBy('kode');

        if ($gudangId) {
            $q->where('gudang_id', $gudangId);
        }

        $data = $q->get();

        return view('lokasi_gudang.index', compact('data', 'gudang', 'gudangId'));
    }

    public function create(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $gudang = Gudang::where('instansi_id', $instansiId)->orderBy('nama')->get();

        $gudangId = $request->query('gudang_id');

        $lokasiInduk = LokasiGudang::query()
            ->whereHas('gudang', fn($x) => $x->where('instansi_id', $instansiId))
            ->when($gudangId, fn($q) => $q->where('gudang_id', $gudangId))
            ->orderBy('kode')
            ->get();

        return view('lokasi_gudang.create', compact('gudang', 'lokasiInduk', 'gudangId'));
    }

    public function store(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $payload = $request->validate([
            'gudang_id' => ['required', 'integer', 'exists:gudang,id'],
            'induk_id' => ['nullable', 'integer', 'exists:lokasi_gudang,id'],
            'tipe_lokasi' => ['required', 'in:zona,lorong,rak,ambalan,bin,ruang,halaman,lainnya'],
            'kode' => ['required', 'string', 'max:120'],
            'nama' => ['nullable', 'string', 'max:200'],
            'jalur' => ['nullable', 'string', 'max:600'],
            'bisa_picking' => ['required', 'in:0,1'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $g = Gudang::findOrFail($payload['gudang_id']);
        abort_unless($g->instansi_id == $instansiId, 404);

        if (!empty($payload['induk_id'])) {
            $parent = LokasiGudang::with('gudang')->findOrFail($payload['induk_id']);
            abort_unless($parent->gudang_id == $payload['gudang_id'], 422);
        }

        $exists = LokasiGudang::where('gudang_id', $payload['gudang_id'])
            ->where('kode', $payload['kode'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['kode' => 'Kode lokasi sudah digunakan di gudang ini.'])->withInput();
        }

        $payload['bisa_picking'] = (bool) $payload['bisa_picking'];

        LokasiGudang::create($payload);

        return redirect()->route('lokasi-gudang.index', ['gudang_id' => $payload['gudang_id']]);
    }

    public function edit(LokasiGudang $lokasi_gudang)
    {
        $instansiId = Auth::user()->instansi_id;

        $lokasi_gudang->load('gudang');

        abort_unless($lokasi_gudang->gudang->instansi_id == $instansiId, 404);

        $gudang = Gudang::where('instansi_id', $instansiId)->orderBy('nama')->get();

        $lokasiInduk = LokasiGudang::query()
            ->where('gudang_id', $lokasi_gudang->gudang_id)
            ->where('id', '!=', $lokasi_gudang->id)
            ->orderBy('kode')
            ->get();

        return view('lokasi_gudang.edit', compact('lokasi_gudang', 'gudang', 'lokasiInduk'));
    }

    public function update(Request $request, LokasiGudang $lokasi_gudang)
    {
        $instansiId = Auth::user()->instansi_id;

        $lokasi_gudang->load('gudang');

        abort_unless($lokasi_gudang->gudang->instansi_id == $instansiId, 404);

        $payload = $request->validate([
            'induk_id' => ['nullable', 'integer', 'exists:lokasi_gudang,id'],
            'tipe_lokasi' => ['required', 'in:zona,lorong,rak,ambalan,bin,ruang,halaman,lainnya'],
            'kode' => ['required', 'string', 'max:120'],
            'nama' => ['nullable', 'string', 'max:200'],
            'jalur' => ['nullable', 'string', 'max:600'],
            'bisa_picking' => ['required', 'in:0,1'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        if (!empty($payload['induk_id'])) {
            abort_unless($payload['induk_id'] != $lokasi_gudang->id, 422);

            $parent = LokasiGudang::findOrFail($payload['induk_id']);
            abort_unless($parent->gudang_id == $lokasi_gudang->gudang_id, 422);
        }

        $exists = LokasiGudang::where('gudang_id', $lokasi_gudang->gudang_id)
            ->where('kode', $payload['kode'])
            ->where('id', '!=', $lokasi_gudang->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['kode' => 'Kode lokasi sudah digunakan di gudang ini.'])->withInput();
        }

        $payload['bisa_picking'] = (bool) $payload['bisa_picking'];

        $lokasi_gudang->update($payload);

        return redirect()->route('lokasi-gudang.index', ['gudang_id' => $lokasi_gudang->gudang_id]);
    }
}
