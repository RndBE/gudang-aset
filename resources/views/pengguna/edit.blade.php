@extends('layouts.app')

@section('content')
    <div class="bg-white border rounded-lg border-gray-300 p-4 max-w-3xl">
        <div class="text-lg font-semibold mb-4">Edit Pengguna</div>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="post" action="{{ route('pengguna.update', $pengguna) }}" class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm mb-1">Instansi</label>
                <select name="instansi_id" class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2" required>
                    @foreach ($instansi as $i)
                        <option value="{{ $i->id }}" @selected($pengguna->instansi_id == $i->id)>{{ $i->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Unit Organisasi</label>
                <select name="unit_organisasi_id" class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2">
                    <option value="">-</option>
                    @foreach ($unit as $u)
                        <option value="{{ $u->id }}" @selected($pengguna->unit_organisasi_id == $u->id)>{{ $u->nama }}
                            ({{ $u->instansi?->kode }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Nama Lengkap</label>
                <input name="nama_lengkap" value="{{ $pengguna->nama_lengkap }}"
                    class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Username</label>
                <input name="username" value="{{ $pengguna->username }}"
                    class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Email</label>
                <input name="email" value="{{ $pengguna->email }}"
                    class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2">
            </div>

            <div>
                <label class="block text-sm mb-1">Telepon</label>
                <input name="telepon" value="{{ $pengguna->telepon }}"
                    class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2">
            </div>

            <div>
                <label class="block text-sm mb-1">NIP/NRK</label>
                <input name="nip_nrk" value="{{ $pengguna->nip_nrk }}"
                    class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2">
            </div>

            <div>
                <label class="block text-sm mb-1">Pangkat</label>
                <input name="pangkat" value="{{ $pengguna->pangkat }}"
                    class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2">
            </div>

            <div>
                <label class="block text-sm mb-1">Jabatan</label>
                <input name="jabatan" value="{{ $pengguna->jabatan }}"
                    class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2">
            </div>

            <div>
                <label class="block text-sm mb-1">Status</label>
                <select name="status" class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2" required>
                    <option value="aktif" @selected($pengguna->status === 'aktif')>aktif</option>
                    <option value="nonaktif" @selected($pengguna->status === 'nonaktif')>nonaktif</option>
                    <option value="terkunci" @selected($pengguna->status === 'terkunci')>terkunci</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm mb-1">Password Baru (kosongkan jika tidak diganti)</label>
                <input type="password" name="password" class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2">
            </div>

            <div class="md:col-span-2 flex gap-2">
                <a class="px-3 py-2 rounded-lg text-sm border-gray-300 border"
                    href="{{ route('pengguna.index') }}">Batal</a>

                <button class="btn-active text-sm text-white px-3 py-2 rounded-lg border-gray-300">Simpan</button>
            </div>
        </form>
    </div>
@endsection
