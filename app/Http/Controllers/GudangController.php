<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Gudang;
use App\Models\UnitOrganisasi;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function index()
    {
        $instansiId = Auth::user()->instansi_id;

        $data = Gudang::with('unitOrganisasi')
            ->where('instansi_id', $instansiId)
            ->orderBy('nama')
            ->get();

        return view('gudang.index', compact('data'));
    }

    public function create()
    {
        $instansiId = Auth::user()->instansi_id;

        $unit = UnitOrganisasi::where('instansi_id', $instansiId)->orderBy('nama')->get();

        return view('gudang.create', compact('unit'));
    }

    public function store(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;


        $payload = $request->validate([
            'unit_organisasi_id' => ['nullable', 'integer', 'exists:unit_organisasi,id'],
            'kode' => ['required', 'string', 'max:80'],
            'nama' => ['required', 'string', 'max:200'],
            'alamat' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $exists = Gudang::where('instansi_id', $instansiId)
            ->where('kode', $payload['kode'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['kode' => 'Kode gudang sudah digunakan.'])->withInput();
        }

        $payload['instansi_id'] = $instansiId;

        Gudang::create($payload);

        return redirect()->route('gudang.index');
    }

    public function edit(Gudang $gudang)
    {
        $instansiId = Auth::user()->instansi_id;


        abort_unless($gudang->instansi_id == $instansiId, 404);

        $unit = UnitOrganisasi::where('instansi_id', $instansiId)->orderBy('nama')->get();

        return view('gudang.edit', compact('gudang', 'unit'));
    }

    public function update(Request $request, Gudang $gudang)
    {
        $instansiId = Auth::user()->instansi_id;


        abort_unless($gudang->instansi_id == $instansiId, 404);

        $payload = $request->validate([
            'unit_organisasi_id' => ['nullable', 'integer', 'exists:unit_organisasi,id'],
            'kode' => ['required', 'string', 'max:80'],
            'nama' => ['required', 'string', 'max:200'],
            'alamat' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $exists = Gudang::where('instansi_id', $instansiId)
            ->where('kode', $payload['kode'])
            ->where('id', '!=', $gudang->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['kode' => 'Kode gudang sudah digunakan.'])->withInput();
        }

        $gudang->update($payload);

        return redirect()->route('gudang.index');
    }
}
