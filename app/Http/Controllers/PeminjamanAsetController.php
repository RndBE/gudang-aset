<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\PeminjamanAset;
use App\Models\Pengguna;
use App\Models\UnitOrganisasi;
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
        $instansiId = auth()->user()->instansi_id;
        $aset = Aset::query()
            ->where('instansi_id', auth()->user()->instansi_id)
            ->whereNotIn('status_siklus', ['dihapus'])
            ->orderBy('tag_aset')
            ->get();

        $pengguna = Pengguna::query()
            ->where('instansi_id', $instansiId)
            ->orderBy('username')
            ->get();

        $unit = UnitOrganisasi::query()
            ->where('instansi_id', $instansiId)
            ->orderBy('nama')
            ->get();

        $nomor = 'PJ-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));

        return view('peminjaman_aset.create', compact('aset', 'nomor', 'pengguna', 'unit'));
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
    public function edit(PeminjamanAset $peminjaman_aset)
    {
        if ($peminjaman_aset->status !== 'aktif') {
            return redirect()->route('peminjaman-aset.index')->with('error', 'Data tidak bisa diedit karena tidak aktif.');
        }

        $aset = Aset::query()
            ->where('instansi_id', auth()->user()->instansi_id)
            ->whereNotIn('status_siklus', ['dihapus'])
            ->orderBy('tag_aset')
            ->get();

        return view('peminjaman_aset.edit', ['data' => $peminjaman_aset, 'aset' => $aset]);
    }

    public function update(Request $request, PeminjamanAset $peminjaman_aset)
    {
        if ($peminjaman_aset->status !== 'aktif') {
            return redirect()->route('peminjaman-aset.index')->with('error', 'Data tidak bisa diubah.');
        }

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

        $peminjaman_aset->update($validated);

        return redirect()->route('peminjaman-aset.index')->with('success', 'Peminjaman aset diperbarui.');
    }

    public function destroy(PeminjamanAset $peminjaman_aset)
    {
        if ($peminjaman_aset->status === 'aktif') {
            return back()->with('error', 'Peminjaman aktif tidak dapat dihapus. Silakan kembalikan/batalkan.');
        }

        $peminjaman_aset->delete();

        return redirect()->route('peminjaman-aset.index')->with('success', 'Data peminjaman dihapus.');
    }

    public function kembalikan(Request $request, PeminjamanAset $peminjaman_aset)
    {
        if ($peminjaman_aset->status !== 'aktif' && $peminjaman_aset->status !== 'terlambat') {
            return back()->with('error', 'Tidak dapat diproses.');
        }

        $validated = $request->validate([
            'kondisi_masuk' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
        ]);

        $peminjaman_aset->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
            'kondisi_masuk' => $validated['kondisi_masuk'],
        ]);

        Aset::where('id', $peminjaman_aset->aset_id)->update([
            'status_siklus' => 'tersedia',
            'pemegang_pengguna_id' => null,
        ]);

        return back()->with('success', 'Aset berhasil dikembalikan.');
    }

    public function batalkan(PeminjamanAset $peminjaman_aset)
    {
        if ($peminjaman_aset->status !== 'aktif') {
            return back()->with('error', 'Tidak dapat dibatalkan.');
        }

        $peminjaman_aset->update([
            'status' => 'dibatalkan',
        ]);

        Aset::where('id', $peminjaman_aset->aset_id)->update([
            'status_siklus' => 'tersedia',
            'pemegang_pengguna_id' => null,
        ]);

        return back()->with('success', 'Peminjaman berhasil dibatalkan.');
    }
}
