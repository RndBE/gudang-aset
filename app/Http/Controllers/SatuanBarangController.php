<?php

namespace App\Http\Controllers;

use App\Models\SatuanBarang;
use Illuminate\Http\Request;

class SatuanBarangController extends Controller
{
    public function index()
    {
        $data = SatuanBarang::orderBy('nama')->get();
        return view('satuan_barang.index', compact('data'));
    }

    public function create()
    {
        return view('satuan_barang.create');
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'kode' => ['required', 'string', 'max:30', 'unique:satuan_barang,kode'],
            'nama' => ['required', 'string', 'max:60'],
        ]);

        SatuanBarang::create($payload);

        return redirect()->route('satuan-barang.index');
    }

    public function edit(SatuanBarang $satuan_barang)
    {
        return view('satuan_barang.edit', compact('satuan_barang'));
    }

    public function update(Request $request, SatuanBarang $satuan_barang)
    {
        $payload = $request->validate([
            'kode' => ['required', 'string', 'max:30', 'unique:satuan_barang,kode,' . $satuan_barang->id],
            'nama' => ['required', 'string', 'max:60'],
        ]);

        $satuan_barang->update($payload);

        return redirect()->route('satuan-barang.index');
    }

    public function api()
    {
        $q = SatuanBarang::query()
            ->select(['id',  'nama'])
            ->orderBy('nama', 'asc');


        return response()->json($q->get());
    }
}
