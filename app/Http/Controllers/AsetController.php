<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aset;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\LokasiGudang;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');
        $status = $request->query('status');

        $data = Aset::query()
            ->with('barang')
            ->when($q, function ($query) use ($q) {
                $query->where('tag_aset', 'like', "%$q%")
                    ->orWhere('no_serial', 'like', "%$q%")
                    ->orWhere('imei', 'like', "%$q%")
                    ->orWhere('no_polisi', 'like', "%$q%");
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status_siklus', $status);
            })
            ->latest('dibuat_pada')
            ->paginate(15)
            ->withQueryString();

        return view('aset.index', compact('data', 'q', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = Barang::query()->where('tipe_barang', 'aset')->orderBy('nama')->get();
        $gudang = Gudang::query()->orderBy('nama')->get();
        $lokasi = LokasiGudang::query()->orderBy('nama')->get();
        return view('aset.create', compact('barang', 'gudang', 'lokasi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => ['required', 'integer'],
            'tag_aset' => ['required', 'string', 'max:120'],
            'no_serial' => ['nullable', 'string', 'max:120'],
            'imei' => ['nullable', 'string', 'max:120'],
            'no_mesin' => ['nullable', 'string', 'max:120'],
            'no_rangka' => ['nullable', 'string', 'max:120'],
            'no_polisi' => ['nullable', 'string', 'max:120'],
            'tanggal_beli' => ['nullable', 'date'],
            'penerimaan_id' => ['nullable', 'integer'],
            'unit_organisasi_saat_ini_id' => ['nullable', 'integer'],
            'gudang_saat_ini_id' => ['nullable', 'integer'],
            'lokasi_saat_ini_id' => ['nullable', 'integer'],
            'pemegang_pengguna_id' => ['nullable', 'integer'],
            'status_kondisi' => ['required', 'in:baik,rusak_ringan,rusak_berat,hilang,dalam_perbaikan,dihapus'],
            'status_siklus' => ['required', 'in:tersedia,dipinjam,ditugaskan,disimpan,perawatan,dihapus'],
            'biaya_perolehan' => ['nullable', 'numeric', 'min:0'],
            'mata_uang' => ['required', 'string', 'max:10'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $validated['instansi_id'] = auth()->user()->instansi_id;

        if ($request->hasFile('gambar')) {
            $ext = $request->file('gambar')->getClientOriginalExtension();
            $filename = Str::uuid()->toString() . '.' . $ext;
            $path = $request->file('gambar')->storeAs('aset', $filename, 'public');
            $validated['gambar'] = $path;
        }

        Aset::create($validated);

        return redirect()->route('aset.index')->with('success', 'Aset berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Aset $aset)
    {
        $aset->load('barang', 'penerimaan.diterimaOleh', 'gudang');
        return view('aset.show', compact('aset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aset $aset)
    {
        $barang = Barang::query()->orderBy('nama')->get();
        $gudang = Gudang::query()->orderBy('nama')->get();
        $lokasi = LokasiGudang::query()->orderBy('nama')->get();
        return view('aset.edit', compact('aset', 'barang', 'gudang', 'lokasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'barang_id' => ['required', 'integer'],
            'tag_aset' => ['required', 'string', 'max:120'],
            'no_serial' => ['nullable', 'string', 'max:120'],
            'imei' => ['nullable', 'string', 'max:120'],
            'no_mesin' => ['nullable', 'string', 'max:120'],
            'no_rangka' => ['nullable', 'string', 'max:120'],
            'no_polisi' => ['nullable', 'string', 'max:120'],
            'tanggal_beli' => ['nullable', 'date'],
            'penerimaan_id' => ['nullable', 'integer'],
            'unit_organisasi_saat_ini_id' => ['nullable', 'integer'],
            'gudang_saat_ini_id' => ['nullable', 'integer'],
            'lokasi_saat_ini_id' => ['nullable', 'integer'],
            'pemegang_pengguna_id' => ['nullable', 'integer'],
            'status_kondisi' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'status_siklus' => ['required', 'in:tersedia,dipinjam,ditugaskan,disimpan,perawatan,dihapus'],
            'biaya_perolehan' => ['nullable', 'numeric', 'min:0'],
            'mata_uang' => ['required', 'string', 'max:10'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('gambar')) {
            if ($aset->gambar && Storage::disk('public')->exists($aset->gambar)) {
                Storage::disk('public')->delete($aset->gambar);
            }

            $ext = $request->file('gambar')->getClientOriginalExtension();
            $filename = Str::uuid()->toString() . '.' . $ext;
            $path = $request->file('gambar')->storeAs('aset', $filename, 'public');
            $validated['gambar'] = $path;
        }

        $aset->update($validated);

        return redirect()->route('aset.index')->with('success', 'Aset berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aset $aset)
    {
        $aset->delete();
        return redirect()->route('aset.index')->with('success', 'Aset berhasil dihapus.');
    }

    public function penghapusanForm(Aset $aset)
    {
        return view('aset.penghapusan', compact('aset'));
    }

    public function penghapusanStore(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'metode' => ['required', 'in:hibah,lelang,rusak,hilang,lainnya'],
            'alasan' => ['required', 'string', 'max:500'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $extra = $aset->extra ?? [];
        $extra['penghapusan'] = [
            'tanggal' => $validated['tanggal'],
            'metode' => $validated['metode'],
            'alasan' => $validated['alasan'],
            'keterangan' => $validated['keterangan'] ?? null,
            'oleh' => auth()->user()->id,
            'waktu' => now()->toDateTimeString(),
        ];

        $aset->update([
            'status_siklus' => 'dihapus',
            'extra' => $extra,
        ]);

        return redirect()
            ->route('aset.show', $aset->id)
            ->with('success', 'Aset berhasil dihapuskan.');
    }
}
