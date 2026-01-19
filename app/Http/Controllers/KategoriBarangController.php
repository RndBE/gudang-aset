<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $instansiId = Auth::user()->instansi_id;

        $data = KategoriBarang::with('induk')
            ->where('instansi_id', $instansiId)
            ->orderBy('nama')
            ->get();

        return view('kategori_barang.index', compact('data'));
    }

    public function create()
    {
        $instansiId = Auth::user()->instansi_id;

        $induk = KategoriBarang::where('instansi_id', $instansiId)->orderBy('nama')->get();

        return view('kategori_barang.create', compact('induk'));
    }

    public function store(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $payload = $request->validate([
            'induk_id' => ['nullable', 'integer', 'exists:kategori_barang,id'],
            'kode' => ['required', 'string', 'max:80'],
            'nama' => ['required', 'string', 'max:200'],
            'default_aset' => ['required', 'in:0,1'],
        ]);

        $exists = KategoriBarang::where('instansi_id', $instansiId)
            ->where('kode', $payload['kode'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['kode' => 'Kode kategori sudah digunakan.'])->withInput();
        }

        $payload['instansi_id'] = $instansiId;
        $payload['default_aset'] = (bool) $payload['default_aset'];

        KategoriBarang::create($payload);

        return redirect()->route('kategori-barang.index');
    }

    public function edit(KategoriBarang $kategori_barang)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($kategori_barang->instansi_id == $instansiId, 404);

        $induk = KategoriBarang::where('instansi_id', $instansiId)
            ->where('id', '!=', $kategori_barang->id)
            ->orderBy('nama')
            ->get();

        return view('kategori_barang.edit', compact('kategori_barang', 'induk'));
    }

    public function update(Request $request, KategoriBarang $kategori_barang)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($kategori_barang->instansi_id == $instansiId, 404);

        $payload = $request->validate([
            'induk_id' => ['nullable', 'integer', 'exists:kategori_barang,id'],
            'kode' => ['required', 'string', 'max:80'],
            'nama' => ['required', 'string', 'max:200'],
            'default_aset' => ['required', 'in:0,1'],
        ]);

        $exists = KategoriBarang::where('instansi_id', $instansiId)
            ->where('kode', $payload['kode'])
            ->where('id', '!=', $kategori_barang->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['kode' => 'Kode kategori sudah digunakan.'])->withInput();
        }

        $payload['default_aset'] = (bool) $payload['default_aset'];

        $kategori_barang->update($payload);

        return redirect()->route('kategori-barang.index');
    }

    public function api()
    {
        $q = KategoriBarang::query()
            ->select(['id',  'nama'])
            ->orderBy('nama', 'asc');


        return response()->json($q->get());
    }
}
