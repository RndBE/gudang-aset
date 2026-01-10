<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\SatuanBarang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $instansiId = Auth::user()->instansi_id;

        $data = Barang::with(['kategori', 'satuan'])
            ->where('instansi_id', $instansiId)
            ->orderBy('nama')
            ->get();

        return view('barang.index', compact('data'));
    }

    public function create()
    {
        $instansiId = Auth::user()->instansi_id;

        $kategori = KategoriBarang::where('instansi_id', $instansiId)->orderBy('nama')->get();
        $satuan = SatuanBarang::orderBy('nama')->get();

        return view('barang.create', compact('kategori', 'satuan'));
    }

    public function store(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $payload = $request->validate([
            'kategori_id' => ['required', 'integer', 'exists:kategori_barang,id'],
            'satuan_id' => ['required', 'integer', 'exists:satuan_barang,id'],
            'sku' => ['required', 'string', 'max:120'],
            'nama' => ['required', 'string', 'max:255'],
            'merek' => ['nullable', 'string', 'max:160'],
            'model' => ['nullable', 'string', 'max:160'],
            'spesifikasi_json' => ['nullable', 'string'],
            'tipe_barang' => ['required', 'in:habis_pakai,aset,keduanya'],
            'metode_pelacakan' => ['required', 'in:tanpa,lot,kedaluwarsa,serial'],
            'stok_minimum' => ['required', 'numeric'],
            'titik_pesan_ulang' => ['required', 'numeric'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $exists = Barang::where('instansi_id', $instansiId)
            ->where('sku', $payload['sku'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['sku' => 'SKU sudah digunakan.'])->withInput();
        }

        $spesifikasi = null;
        if (!empty($payload['spesifikasi_json'])) {
            $decoded = json_decode($payload['spesifikasi_json'], true);
            if (!is_array($decoded)) {
                return back()->withErrors(['spesifikasi_json' => 'Format JSON tidak valid.'])->withInput();
            }
            $spesifikasi = $decoded;
        }

        unset($payload['spesifikasi_json']);

        $payload['instansi_id'] = $instansiId;
        $payload['spesifikasi'] = $spesifikasi;

        Barang::create($payload);

        return redirect()->route('barang.index');
    }

    public function edit(Barang $barang)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($barang->instansi_id == $instansiId, 404);

        $kategori = KategoriBarang::where('instansi_id', $instansiId)->orderBy('nama')->get();
        $satuan = SatuanBarang::orderBy('nama')->get();

        return view('barang.edit', compact('barang', 'kategori', 'satuan'));
    }

    public function update(Request $request, Barang $barang)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($barang->instansi_id == $instansiId, 404);

        $payload = $request->validate([
            'kategori_id' => ['required', 'integer', 'exists:kategori_barang,id'],
            'satuan_id' => ['required', 'integer', 'exists:satuan_barang,id'],
            'sku' => ['required', 'string', 'max:120'],
            'nama' => ['required', 'string', 'max:255'],
            'merek' => ['nullable', 'string', 'max:160'],
            'model' => ['nullable', 'string', 'max:160'],
            'spesifikasi_json' => ['nullable', 'string'],
            'tipe_barang' => ['required', 'in:habis_pakai,aset,keduanya'],
            'metode_pelacakan' => ['required', 'in:tanpa,lot,kedaluwarsa,serial'],
            'stok_minimum' => ['required', 'numeric'],
            'titik_pesan_ulang' => ['required', 'numeric'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $exists = Barang::where('instansi_id', $instansiId)
            ->where('sku', $payload['sku'])
            ->where('id', '!=', $barang->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['sku' => 'SKU sudah digunakan.'])->withInput();
        }

        $spesifikasi = null;
        if (!empty($payload['spesifikasi_json'])) {
            $decoded = json_decode($payload['spesifikasi_json'], true);
            if (!is_array($decoded)) {
                return back()->withErrors(['spesifikasi_json' => 'Format JSON tidak valid.'])->withInput();
            }
            $spesifikasi = $decoded;
        }

        unset($payload['spesifikasi_json']);

        $payload['spesifikasi'] = $spesifikasi;

        $barang->update($payload);

        return redirect()->route('barang.index');
    }
}
