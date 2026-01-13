<?php

namespace App\Http\Controllers;

use App\Models\PermintaanPersetujuan;
use App\Models\LangkahPermintaanPersetujuan;
use Illuminate\Http\Request;

class PermintaanPersetujuanController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $status = $request->query('status');

        $data = PermintaanPersetujuan::query()
            ->with('alur')
            ->where('instansi_id', auth()->user()->instansi_id)
            ->when(
                $q,
                fn($query) =>
                $query->where('judul', 'like', "%$q%")
                    ->orWhere('tipe_entitas', 'like', "%$q%")
            )
            ->when($status, fn($query) => $query->where('status', $status))
            ->latest('dibuat_pada')
            ->paginate(15)
            ->withQueryString();

        return view('permintaan_persetujuan.index', compact('data', 'q', 'status'));
    }

    public function show(PermintaanPersetujuan $permintaan_persetujuan)
    {
        $permintaan_persetujuan->load('alur', 'langkah');
        return view('permintaan_persetujuan.show', ['data' => $permintaan_persetujuan]);
    }

    public function setujui(Request $request, PermintaanPersetujuan $permintaan_persetujuan)
    {
        $request->validate([
            'catatan' => ['nullable', 'string'],
        ]);

        $langkahAktif = $permintaan_persetujuan->langkah()
            ->where('no_langkah', $permintaan_persetujuan->langkah_saat_ini)
            ->first();

        if (!$langkahAktif || $langkahAktif->status !== 'menunggu') {
            return back()->with('error', 'Langkah aktif tidak valid.');
        }

        $langkahAktif->update([
            'status' => 'disetujui',
            'disetujui_oleh' => auth()->user()->id,
            'disetujui_pada' => now(),
            'catatan_keputusan' => $request->catatan_keputusan,
        ]);

        $next = $permintaan_persetujuan->langkah()
            ->where('no_langkah', $permintaan_persetujuan->langkah_saat_ini + 1)
            ->first();

        if ($next) {
            $permintaan_persetujuan->update([
                'langkah' => $next->urutan,
                'status' => 'berjalan',
            ]);
        } else {
            $permintaan_persetujuan->update([
                'status' => 'disetujui',
            ]);
        }

        return back()->with('success', 'Persetujuan berhasil diproses.');
    }

    public function tolak(Request $request, PermintaanPersetujuan $permintaan_persetujuan)
    {
        $request->validate([
            'catatan' => ['required', 'string'],
        ]);

        $langkahAktif = $permintaan_persetujuan->langkah()
            ->where('urutan', $permintaan_persetujuan->langkah_aktif)
            ->first();

        if (!$langkahAktif || $langkahAktif->status !== 'menunggu') {
            return back()->with('error', 'Langkah aktif tidak valid.');
        }

        $langkahAktif->update([
            'status' => 'ditolak',
            'disetujui_oleh' => auth()->user()->id,
            'disetujui_pada' => now(),
            'catatan' => $request->catatan,
        ]);

        $permintaan_persetujuan->update([
            'status' => 'ditolak',
        ]);

        return back()->with('success', 'Permintaan persetujuan ditolak.');
    }
}
