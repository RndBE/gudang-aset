<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $kanal = $request->query('kanal');
        $dibaca = $request->query('dibaca'); // 0 / 1

        $data = Notifikasi::query()
            ->where('instansi_id', auth()->user()->instansi_id)
            ->where('pengguna_id', auth()->id())
            ->when($q, function ($query) use ($q) {
                $query->where('judul', 'like', "%$q%")
                    ->orWhere('isi', 'like', "%$q%")
                    ->orWhere('tipe_entitas', 'like', "%$q%");
            })
            ->when($kanal, fn($query) => $query->where('kanal', $kanal))
            ->when($dibaca !== null && $dibaca !== '', fn($query) => $query->where('sudah_dibaca', (int)$dibaca))
            ->latest('dibuat_pada')
            ->paginate(20)
            ->withQueryString();

        $jumlahBelumDibaca = Notifikasi::query()
            ->where('instansi_id', auth()->user()->instansi_id)
            ->where('pengguna_id', auth()->id())
            ->where('sudah_dibaca', 0)
            ->count();

        $listKanal = ['aplikasi', 'email', 'sms', 'whatsapp', 'lainnya'];

        return view('notifikasi.index', compact('data', 'q', 'kanal', 'dibaca', 'jumlahBelumDibaca', 'listKanal'));
    }

    public function show(Notifikasi $notifikasi)
    {
        if (
            $notifikasi->instansi_id != auth()->user()->instansi_id ||
            $notifikasi->pengguna_id != auth()->id()
        ) {
            abort(403);
        }

        if (!$notifikasi->sudah_dibaca) {
            $notifikasi->update([
                'sudah_dibaca' => 1,
                'dibaca_pada' => now(),
            ]);
        }

        return view('notifikasi.show', ['data' => $notifikasi]);
    }

    public function tandaiDibaca(Notifikasi $notifikasi)
    {
        if (
            $notifikasi->instansi_id != auth()->user()->instansi_id ||
            $notifikasi->pengguna_id != auth()->id()
        ) {
            abort(403);
        }

        $notifikasi->update([
            'sudah_dibaca' => 1,
            'dibaca_pada' => now(),
        ]);

        return back()->with('success', 'Notifikasi ditandai dibaca.');
    }

    public function tandaiSemuaDibaca()
    {
        Notifikasi::query()
            ->where('instansi_id', auth()->user()->instansi_id)
            ->where('pengguna_id', auth()->id())
            ->where('sudah_dibaca', 0)
            ->update([
                'sudah_dibaca' => 1,
                'dibaca_pada' => now(),
            ]);

        return back()->with('success', 'Semua notifikasi sudah ditandai dibaca.');
    }

    public function destroy(Notifikasi $notifikasi)
    {
        if (
            $notifikasi->instansi_id != auth()->user()->instansi_id ||
            $notifikasi->pengguna_id != auth()->id()
        ) {
            abort(403);
        }

        $notifikasi->delete();

        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi dihapus.');
    }
}
