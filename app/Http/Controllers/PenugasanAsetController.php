<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\PenugasanAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PenugasanAsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');
        $status = $request->query('status');

        $data = PenugasanAset::query()
            ->with('aset')
            ->where('instansi_id', auth()->user()->instansi_id)
            ->when($q, function ($query) use ($q) {
                $query->where('nomor_dok_serah_terima', 'like', "%$q%")
                    ->orWhereHas('aset', function ($qa) use ($q) {
                        $qa->where('tag_aset', 'like', "%$q%")
                            ->orWhere('no_serial', 'like', "%$q%");
                    });
            })
            ->when($status, fn($query) => $query->where('status', $status))
            ->latest('dibuat_pada')
            ->paginate(15)
            ->withQueryString();

        return view('penugasan_aset.index', compact('data', 'q', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $aset = Aset::query()
            ->where('instansi_id', auth()->user()->instansi_id)
            ->whereNotIn('status_siklus', ['dihapus'])
            ->orderBy('tag_aset')
            ->get();

        $nomor = 'ST-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));

        return view('penugasan_aset.create', compact('aset', 'nomor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'aset_id' => ['required', 'integer'],
            'ditugaskan_ke_pengguna_id' => ['nullable', 'integer'],
            'ditugaskan_ke_unit_id' => ['nullable', 'integer'],
            'tanggal_tugas' => ['required', 'date'],
            'nomor_dok_serah_terima' => ['required', 'string', 'max:120'],
            'catatan' => ['nullable', 'string'],
        ]);

        $penugasan = PenugasanAset::create([
            'instansi_id' => auth()->user()->instansi_id,
            'aset_id' => $validated['aset_id'],
            'ditugaskan_ke_pengguna_id' => $validated['ditugaskan_ke_pengguna_id'] ?? null,
            'ditugaskan_ke_unit_id' => $validated['ditugaskan_ke_unit_id'] ?? null,
            'tanggal_tugas' => $validated['tanggal_tugas'],
            'tanggal_kembali' => null,
            'status' => 'aktif',
            'nomor_dok_serah_terima' => $validated['nomor_dok_serah_terima'],
            'catatan' => $validated['catatan'] ?? null,
            'dibuat_oleh' => auth()->user()->id,
        ]);

        Aset::where('id', $penugasan->aset_id)->update([
            'status_siklus' => 'ditugaskan',
            'pemegang_pengguna_id' => $penugasan->ditugaskan_ke_pengguna_id,
            'unit_organisasi_saat_ini_id' => $penugasan->ditugaskan_ke_unit_id,
        ]);

        return redirect()->route('penugasan-aset.index')->with('success', 'Penugasan aset berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PenugasanAset $penugasan_aset)
    {
        $penugasan_aset->load('aset');
        return view('penugasan_aset.show', ['data' => $penugasan_aset]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
