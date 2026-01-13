<?php

namespace App\Http\Controllers;

use App\Models\LogAudit;
use Illuminate\Http\Request;

class LogAuditController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $aksi = $request->query('aksi');
        $nama_tabel = $request->query('nama_tabel');
        $tanggal_dari = $request->query('tanggal_dari');
        $tanggal_sampai = $request->query('tanggal_sampai');

        $data = LogAudit::query()
            ->with('pengguna')
            ->where('instansi_id', auth()->user()->instansi_id)
            ->when($q, function ($query) use ($q) {
                $query->where('nama_tabel', 'like', "%$q%")
                    ->orWhere('tipe_referensi', 'like', "%$q%")
                    ->orWhere('id_rekaman', 'like', "%$q%")
                    ->orWhere('id_referensi', 'like', "%$q%");
            })
            ->when($aksi, fn($query) => $query->where('aksi', $aksi))
            ->when($nama_tabel, fn($query) => $query->where('nama_tabel', $nama_tabel))
            ->when($tanggal_dari, fn($query) => $query->whereDate('dibuat_pada', '>=', $tanggal_dari))
            ->when($tanggal_sampai, fn($query) => $query->whereDate('dibuat_pada', '<=', $tanggal_sampai))
            ->latest('dibuat_pada')
            ->paginate(20)
            ->withQueryString();

        $listAksi = ['tambah', 'ubah', 'hapus', 'login', 'logout', 'setujui', 'tolak', 'posting', 'batal'];

        $listTabel = LogAudit::query()
            ->where('instansi_id', auth()->user()->instansi_id)
            ->select('nama_tabel')
            ->distinct()
            ->orderBy('nama_tabel')
            ->pluck('nama_tabel');

        return view('log_audit.index', compact(
            'data',
            'q',
            'aksi',
            'nama_tabel',
            'tanggal_dari',
            'tanggal_sampai',
            'listAksi',
            'listTabel'
        ));
    }

    public function show(LogAudit $log_audit)
    {
        if ($log_audit->instansi_id != auth()->user()->instansi_id) {
            abort(403);
        }

        $log_audit->load('pengguna');

        return view('log_audit.show', [
            'data' => $log_audit
        ]);
    }
}
