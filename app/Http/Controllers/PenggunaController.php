<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Pengguna;
use App\Models\UnitOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        $data = Pengguna::with(['instansi', 'unitOrganisasi'])
            ->orderBy('nama_lengkap')
            ->get();

        return view('pengguna.index', compact('data'));
    }

    public function create()
    {
        $instansi = Instansi::orderBy('nama')->get();
        $unit = UnitOrganisasi::with('instansi')->orderBy('nama')->get();
        return view('pengguna.create', compact('instansi', 'unit'));
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'instansi_id' => ['required', 'exists:instansi,id'],
            'unit_organisasi_id' => ['nullable', 'exists:unit_organisasi,id'],
            'username' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip_nrk' => ['nullable', 'string', 'max:120'],
            'pangkat' => ['nullable', 'string', 'max:120'],
            'jabatan' => ['nullable', 'string', 'max:160'],
            'status' => ['required', 'in:aktif,nonaktif,terkunci'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $instansiId = (int) $payload['instansi_id'];

        $request->validate([
            'username' => ['unique:pengguna,username,NULL,id,instansi_id,' . $instansiId],
            'email' => ['nullable', 'unique:pengguna,email,NULL,id,instansi_id,' . $instansiId],
        ]);

        $data = [
            'instansi_id' => $payload['instansi_id'],
            'unit_organisasi_id' => $payload['unit_organisasi_id'] ?? null,
            'username' => $payload['username'],
            'email' => $payload['email'] ?? null,
            'telepon' => $payload['telepon'] ?? null,
            'hash_password' => Hash::make($payload['password']),
            'nama_lengkap' => $payload['nama_lengkap'],
            'nip_nrk' => $payload['nip_nrk'] ?? null,
            'pangkat' => $payload['pangkat'] ?? null,
            'jabatan' => $payload['jabatan'] ?? null,
            'status' => $payload['status'],
        ];

        Pengguna::create($data);

        return redirect()->route('pengguna.index');
    }

    public function edit(Pengguna $pengguna)
    {
        $instansi = Instansi::orderBy('nama')->get();
        $unit = UnitOrganisasi::with('instansi')->orderBy('nama')->get();
        return view('pengguna.edit', compact('pengguna', 'instansi', 'unit'));
    }

    public function update(Request $request, Pengguna $pengguna)
    {
        $payload = $request->validate([
            'instansi_id' => ['required', 'exists:instansi,id'],
            'unit_organisasi_id' => ['nullable', 'exists:unit_organisasi,id'],
            'username' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip_nrk' => ['nullable', 'string', 'max:120'],
            'pangkat' => ['nullable', 'string', 'max:120'],
            'jabatan' => ['nullable', 'string', 'max:160'],
            'status' => ['required', 'in:aktif,nonaktif,terkunci'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $instansiId = (int) $payload['instansi_id'];

        $request->validate([
            'username' => ['unique:pengguna,username,' . $pengguna->id . ',id,instansi_id,' . $instansiId],
            'email' => ['nullable', 'unique:pengguna,email,' . $pengguna->id . ',id,instansi_id,' . $instansiId],
        ]);

        $data = [
            'instansi_id' => $payload['instansi_id'],
            'unit_organisasi_id' => $payload['unit_organisasi_id'] ?? null,
            'username' => $payload['username'],
            'email' => $payload['email'] ?? null,
            'telepon' => $payload['telepon'] ?? null,
            'nama_lengkap' => $payload['nama_lengkap'],
            'nip_nrk' => $payload['nip_nrk'] ?? null,
            'pangkat' => $payload['pangkat'] ?? null,
            'jabatan' => $payload['jabatan'] ?? null,
            'status' => $payload['status'],
        ];

        if (!empty($payload['password'])) {
            $data['hash_password'] = Hash::make($payload['password']);
        }

        $pengguna->update($data);

        return redirect()->route('pengguna.index');
    }
}
