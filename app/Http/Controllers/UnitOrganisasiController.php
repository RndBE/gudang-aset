<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\UnitOrganisasi;
use Illuminate\Http\Request;

class UnitOrganisasiController extends Controller
{
    public function index()
    {
        $data = UnitOrganisasi::with(['instansi', 'induk'])->orderBy('nama')->get();
        return view('unit_organisasi.index', compact('data'));
    }

    public function create()
    {
        $instansi = Instansi::orderBy('nama')->get();
        $induk = UnitOrganisasi::orderBy('nama')->get();
        return view('unit_organisasi.create', compact('instansi', 'induk'));
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'instansi_id' => ['required', 'exists:instansi,id'],
            'induk_id' => ['nullable', 'exists:unit_organisasi,id'],
            'tipe_unit' => ['required', 'in:mabes,polda,polres,polsek,satker,unit,unit_gudang,lainnya'],
            'kode' => ['required', 'string', 'max:80'],
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        UnitOrganisasi::create($payload);
        return redirect()->route('unit-organisasi.index');
    }

    public function edit(UnitOrganisasi $unit_organisasi)
    {
        $instansi = Instansi::orderBy('nama')->get();
        $induk = UnitOrganisasi::where('id', '!=', $unit_organisasi->id)->orderBy('nama')->get();
        return view('unit_organisasi.edit', compact('unit_organisasi', 'instansi', 'induk'));
    }

    public function update(Request $request, UnitOrganisasi $unit_organisasi)
    {
        $payload = $request->validate([
            'instansi_id' => ['required', 'exists:instansi,id'],
            'induk_id' => ['nullable', 'exists:unit_organisasi,id'],
            'tipe_unit' => ['required', 'in:mabes,polda,polres,polsek,satker,unit,unit_gudang,lainnya'],
            'kode' => ['required', 'string', 'max:80'],
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $unit_organisasi->update($payload);
        return redirect()->route('unit-organisasi.index');
    }
}
