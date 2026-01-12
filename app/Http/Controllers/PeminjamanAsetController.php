<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\PeminjamanAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PeminjamanAsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');
        $status = $request->query('status');

        $data = PeminjamanAset::query()
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

        return view('peminjaman_aset.index', compact('data', 'q', 'status'));
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

        $nomor = 'PJ-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));

        return view('peminjaman_aset.create', compact('aset', 'nomor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'aset_id' => ['required', 'integer'],
            'peminjam_pengguna_id' => ['nullable', 'integer'],
            'peminjam_unit_id' => ['nullable', 'integer'],
            'tanggal_mulai' => ['required', 'date'],
            'jatuh_tempo' => ['required', 'date'],
            'tujuan' => ['required', 'string'],
            'kondisi_keluar' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'nomor_dok_serah_terima' => ['required', 'string', 'max:120'],
            'catatan' => ['nullable', 'string'],
        ]);

        $peminjaman = PeminjamanAset::create([
            'instansi_id' => auth()->user()->instansi_id,
            'aset_id' => $validated['aset_id'],
            'peminjam_pengguna_id' => $validated['peminjam_pengguna_id'] ?? null,
            'peminjam_unit_id' => $validated['peminjam_unit_id'] ?? null,
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'jatuh_tempo' => $validated['jatuh_tempo'],
            'tanggal_kembali' => null,
            'status' => 'aktif',
            'tujuan' => $validated['tujuan'],
            'kondisi_keluar' => $validated['kondisi_keluar'],
            'kondisi_masuk' => null,
            'nomor_dok_serah_terima' => $validated['nomor_dok_serah_terima'],
            'catatan' => $validated['catatan'] ?? null,
            'dibuat_oleh' => auth()->user()->id,
        ]);

        Aset::where('id', $peminjaman->aset_id)->update([
            'status_siklus' => 'dipinjam',
            'pemegang_pengguna_id' => $peminjaman->peminjam_pengguna_id,
            'unit_organisasi_saat_ini_id' => $peminjaman->peminjam_unit_id,
        ]);

        return redirect()->route('peminjaman-aset.index')->with('success', 'Peminjaman aset berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PeminjamanAset $peminjaman_aset)
    {
        $peminjaman_aset->load('aset');
        return view('peminjaman_aset.show', ['data' => $peminjaman_aset]);
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
