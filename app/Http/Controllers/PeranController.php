<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Peran;
use Illuminate\Http\Request;

class PeranController extends Controller
{
    public function index()
    {
        $data = Peran::with('instansi')->orderBy('nama')->get();
        return view('peran.index', compact('data'));
    }

    public function create()
    {
        $instansi = Instansi::orderBy('nama')->get();
        return view('peran.create', compact('instansi'));
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'instansi_id' => ['required', 'exists:instansi,id'],
            'kode' => ['required', 'string', 'max:80'],
            'nama' => ['required', 'string', 'max:160'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        Peran::create($payload);
        return redirect()->route('peran.index');
    }

    public function edit(Peran $peran)
    {
        $instansi = Instansi::orderBy('nama')->get();
        return view('peran.edit', compact('peran', 'instansi'));
    }

    public function update(Request $request, Peran $peran)
    {
        $payload = $request->validate([
            'instansi_id' => ['required', 'exists:instansi,id'],
            'kode' => ['required', 'string', 'max:80'],
            'nama' => ['required', 'string', 'max:160'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $peran->update($payload);
        return redirect()->route('peran.index');
    }
}
