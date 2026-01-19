<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aset;
use App\Models\PenghapusanAset;
use Illuminate\Support\Str;
use App\Services\ApprovalService;
use Illuminate\Support\Facades\DB;

class PenghapusanAsetController extends Controller
{

    public function __construct(private ApprovalService $approvalService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');
        $status = $request->query('status');
        $approvalStatus = $request->query('approval_status');

        $data = \App\Models\PenghapusanAset::query()
            ->with(['aset', 'permintaanPersetujuan'])
            ->where('instansi_id', auth()->user()->instansi_id)

            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('nomor_dokumen', 'like', "%$q%")
                        ->orWhere('alasan', 'like', "%$q%")
                        ->orWhereHas('aset', function ($qa) use ($q) {
                            $qa->where('tag_aset', 'like', "%$q%")
                                ->orWhere('no_serial', 'like', "%$q%");
                        });
                });
            })

            ->when($status, fn($query) => $query->where('status', $status))

            ->when($approvalStatus, function ($query) use ($approvalStatus) {
                $query->whereHas('permintaanPersetujuan', function ($q) use ($approvalStatus) {
                    $q->where('status', $approvalStatus);
                });
            })

            ->latest('dibuat_pada')
            ->paginate(15)
            ->withQueryString();

        return view('penghapusan_aset.index', compact('data', 'q', 'status', 'approvalStatus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $aset = Aset::query()
            ->where('instansi_id', auth()->user()->instansi_id)
            ->where('status_siklus', 'tersedia')
            ->orderBy('tag_aset')
            ->get();

        $nomor = 'DEL-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));

        return view('penghapusan_aset.create', compact('aset', 'nomor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'aset_id' => ['required', 'integer'],
            'nomor_penghapusan' => ['required', 'string', 'max:120'],
            'tanggal_penghapusan' => ['required', 'date'],
            'metode' => ['required', 'in:hapus,hibah,lelang,rusak_total,lainnya'],
            'alasan' => ['required', 'string'],
        ]);

        // PenghapusanAset::create([
        //     'instansi_id' => auth()->user()->instansi_id,
        //     'aset_id' => $validated['aset_id'],
        //     'nomor_penghapusan' => $validated['nomor_penghapusan'],
        //     'tanggal_penghapusan' => $validated['tanggal_penghapusan'],
        //     'metode' => $validated['metode'],
        //     'alasan' => $validated['alasan'],
        //     'status' => 'draft',
        //     'dibuat_oleh' => auth()->user()->id,
        //     'disetujui_oleh' => null,
        // ]);
        return DB::transaction(function () use ($validated) {
            $penghapusan = PenghapusanAset::create([
                'instansi_id' => auth()->user()->instansi_id,
                'aset_id' => $validated['aset_id'],
                'nomor_penghapusan' => $validated['nomor_penghapusan'],
                'tanggal_penghapusan' => $validated['tanggal_penghapusan'],
                'metode' => $validated['metode'],
                'alasan' => $validated['alasan'],
                'status' => 'draft',
                'dibuat_oleh' => auth()->user()->id,
                'disetujui_oleh' => null,
            ]);
            Aset::where('id', $penghapusan->aset_id)->update([
                'status_siklus' => 'dihapus',
                'pemegang_pengguna_id' => $penghapusan->ditugaskan_ke_pengguna_id,
                'unit_organisasi_saat_ini_id' => $penghapusan->ditugaskan_ke_unit_id,
            ]);

            $permintaan = $this->approvalService->buatPermintaan(
                berlakuUntuk: 'penghapusan_aset',
                tipeEntitas: 'penghapusan_aset',
                idEntitas: $penghapusan->id,
                ringkasan: "penghapusan aset #" . ($penghapusan->aset?->tag_aset ?? $penghapusan->aset_id)
            );

            $penghapusan->update([
                'permintaan_persetujuan_id' => $permintaan->id,
            ]);

            return redirect()
                ->route('penghapusan-aset.index', $penghapusan->id)
                ->with('success', 'Pengajuan penghapusan berhasil dibuat dan diajukan ke approval.');
        });

        // return redirect()->route('penghapusan-aset.index')->with('success', 'Pengajuan penghapusan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PenghapusanAset $penghapusan_aset)
    {
        $penghapusan_aset->load('aset', 'disetujui', 'dibuat_oleh', 'instansi');
        return view('penghapusan_aset.show', ['data' => $penghapusan_aset]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenghapusanAset $penghapusan_aset)
    {
        if ($penghapusan_aset->status !== 'draft') {
            return redirect()->route('penghapusan-aset.index')->with('error', 'Data tidak bisa diedit karena bukan draft.');
        }


        $aset = Aset::query()
            ->where('instansi_id', auth()->user()->instansi_id)
            ->where('status_siklus', '!=', 'dihapus')
            ->orderBy('tag_aset')
            ->get();

        $asetDipilih = Aset::query()
            ->where('instansi_id', auth()->user()->instansi_id)
            ->where('id', $penghapusan_aset->aset_id)
            ->first();

        if ($asetDipilih && !$aset->contains('id', $asetDipilih->id)) {
            $aset->prepend($asetDipilih);
        }

        return view('penghapusan_aset.edit', ['data' => $penghapusan_aset, 'aset' => $aset]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenghapusanAset $penghapusan_aset)
    {
        if ($penghapusan_aset->status !== 'draft') {
            return redirect()->route('penghapusan-aset.index')->with('error', 'Data tidak bisa diupdate karena bukan draft.');
        }

        $validated = $request->validate([
            'aset_id' => ['required', 'integer'],
            'nomor_penghapusan' => ['required', 'string', 'max:120'],
            'tanggal_penghapusan' => ['required', 'date'],
            'metode' => ['required', 'in:hapus,hibah,lelang,rusak_total,lainnya'],
            'alasan' => ['required', 'string'],
        ]);

        $penghapusan_aset->update($validated);

        return redirect()->route('penghapusan-aset.index')->with('success', 'Pengajuan penghapusan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenghapusanAset $penghapusan_aset)
    {
        if ($penghapusan_aset->status !== 'draft') {
            return redirect()->route('penghapusan-aset.index')->with('error', 'Hanya draft yang boleh dihapus.');
        }

        $penghapusan_aset->delete();

        return redirect()->route('penghapusan-aset.index')->with('success', 'Data draft penghapusan berhasil dihapus.');
    }
    public function setujui(PenghapusanAset $penghapusan_aset)
    {
        if ($penghapusan_aset->status !== 'draft') {
            return back()->with('error', 'Penghapusan tidak bisa disetujui karena bukan draft.');
        }

        $penghapusan_aset->update([
            'status' => 'disetujui',
            'disetujui_oleh' => auth()->user()->id,
        ]);

        return back()->with('success', 'Penghapusan aset disetujui.');
    }

    public function eksekusi(PenghapusanAset $penghapusan_aset)
    {
        if ($penghapusan_aset->status !== 'disetujui') {
            return back()->with('error', 'Eksekusi hanya bisa dilakukan jika status sudah disetujui.');
        }

        $penghapusan_aset->update([
            'status' => 'dieksekusi',
        ]);

        Aset::where('id', $penghapusan_aset->aset_id)->update([
            'status_siklus' => 'dihapus',
        ]);

        return back()->with('success', 'Penghapusan dieksekusi dan aset dinonaktifkan.');
    }

    public function batalkan(PenghapusanAset $penghapusan_aset)
    {
        if (!in_array($penghapusan_aset->status, ['draft', 'disetujui'])) {
            return back()->with('error', 'Penghapusan tidak dapat dibatalkan pada status ini.');
        }

        $penghapusan_aset->update([
            'status' => 'dibatalkan',
        ]);

        Aset::where('id', $penghapusan_aset->aset_id)->update([
            'status_siklus' => 'tersedia',
        ]);

        return back()->with('success', 'Penghapusan aset dibatalkan.');
    }
}
