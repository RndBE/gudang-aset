<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function index()
    {
        $data = Izin::orderBy('kode')->get();
        return view('izin.index', compact('data'));
    }

    public function create()
    {
        return view('izin.create');
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'kode' => ['required', 'string', 'max:120', 'unique:izin,kode'],
            'nama' => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        Izin::create($payload);
        return redirect()->route('izin.index');
    }

    public function edit(Izin $izin)
    {
        return view('izin.edit', compact('izin'));
    }

    public function update(Request $request, Izin $izin)
    {
        $payload = $request->validate([
            'kode' => ['required', 'string', 'max:120', 'unique:izin,kode,' . $izin->id],
            'nama' => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $izin->update($payload);
        return redirect()->route('izin.index');
    }
}
