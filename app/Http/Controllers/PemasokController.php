<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    public function index()
    {
        $instansiId = Auth::user()->instansi_id;

        $data = Pemasok::where('instansi_id', $instansiId)->orderBy('nama')->get();

        return view('pemasok.index', compact('data'));
    }

    public function create()
    {
        return view('pemasok.create');
    }

    public function store(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $payload = $request->validate([
            'kode' => ['required', 'string', 'max:80'],
            'nama' => ['required', 'string', 'max:255'],
            'npwp' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string'],
            'nama_kontak' => ['nullable', 'string', 'max:160'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $exists = Pemasok::where('instansi_id', $instansiId)
            ->where('kode', $payload['kode'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['kode' => 'Kode pemasok sudah digunakan.'])->withInput();
        }

        $payload['instansi_id'] = $instansiId;

        Pemasok::create($payload);

        return redirect()->route('pemasok.index');
    }

    public function edit(Pemasok $pemasok)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($pemasok->instansi_id == $instansiId, 404);

        return view('pemasok.edit', compact('pemasok'));
    }

    public function update(Request $request, Pemasok $pemasok)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($pemasok->instansi_id == $instansiId, 404);

        $payload = $request->validate([
            'kode' => ['required', 'string', 'max:80'],
            'nama' => ['required', 'string', 'max:255'],
            'npwp' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string'],
            'nama_kontak' => ['nullable', 'string', 'max:160'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $exists = Pemasok::where('instansi_id', $instansiId)
            ->where('kode', $payload['kode'])
            ->where('id', '!=', $pemasok->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['kode' => 'Kode pemasok sudah digunakan.'])->withInput();
        }

        $pemasok->update($payload);

        return redirect()->route('pemasok.index');
    }
}
