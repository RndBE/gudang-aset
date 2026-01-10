@extends('layouts.app')

@section('content')
    <div class="bg-white border rounded p-4 max-w-3xl">
        <div class="text-lg font-semibold mb-4">Tambah Pengguna</div>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="post" action="{{ route('pengguna.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @csrf

            <div>
                <label class="block text-sm mb-1">Instansi</label>
                <select name="instansi_id" class="w-full border rounded px-3 py-2" required>
                    @foreach ($instansi as $i)
                        <option value="{{ $i->id }}">{{ $i->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Unit Organisasi</label>
                <select name="unit_organisasi_id" class="w-full border rounded px-3 py-2">
                    <option value="">-</option>
                    @foreach ($unit as $u)
                        <option value="{{ $u->id }}">{{ $u->nama }} ({{ $u->instansi?->kode }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Nama Lengkap</label>
                <input name="nama_lengkap" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Username</label>
                <input name="username" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Email</label>
                <input name="email" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm mb-1">Telepon</label>
                <input name="telepon" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm mb-1">NIP/NRK</label>
                <input name="nip_nrk" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm mb-1">Pangkat</label>
                <input name="pangkat" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm mb-1">Jabatan</label>
                <input name="jabatan" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2" required>
                    <option value="aktif">aktif</option>
                    <option value="nonaktif">nonaktif</option>
                    <option value="terkunci">terkunci</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="md:col-span-2 flex gap-2">
                <button class="bg-black text-white px-3 py-2 rounded">Simpan</button>
                <a class="px-3 py-2 rounded border" href="{{ route('pengguna.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
