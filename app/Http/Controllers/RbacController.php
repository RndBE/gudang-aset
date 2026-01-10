<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use App\Models\Pengguna;
use App\Models\Peran;
use Illuminate\Http\Request;

class RbacController extends Controller
{
    public function index()
    {
        $peran = Peran::with('izin')->orderBy('nama')->get();
        $izin = Izin::orderBy('kode')->get();
        $pengguna = Pengguna::with('peran')->orderBy('nama_lengkap')->get();

        return view('rbac.index', compact('peran', 'izin', 'pengguna'));
    }

    public function simpanIzinPeran(Request $request, Peran $peran)
    {
        $payload = $request->validate([
            'izin_id' => ['array'],
            'izin_id.*' => ['integer', 'exists:izin,id'],
        ]);

        $peran->izin()->sync($payload['izin_id'] ?? []);
        return redirect()->route('rbac.index');
    }

    public function simpanPeranPengguna(Request $request, Pengguna $pengguna)
    {
        $payload = $request->validate([
            'peran_id' => ['array'],
            'peran_id.*' => ['integer', 'exists:peran,id'],
        ]);

        $pengguna->peran()->sync($payload['peran_id'] ?? []);
        return redirect()->route('rbac.index');
    }
}
