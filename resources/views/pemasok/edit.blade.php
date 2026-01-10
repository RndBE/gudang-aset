@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="text-xl font-semibold">Edit Pemasok</h1>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-50 text-red-700 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('pemasok.update', $pemasok->id) }}" class="bg-white border rounded p-4 space-y-4">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Kode</label>
                <input name="kode" value="{{ old('kode', $pemasok->kode) }}" class="mt-1 w-full border rounded px-3 py-2"
                    maxlength="80" required>
            </div>
            <div>
                <label class="text-sm font-medium">Status</label>
                <select name="status" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="aktif" @selected(old('status', $pemasok->status) === 'aktif')>aktif</option>
                    <option value="nonaktif" @selected(old('status', $pemasok->status) === 'nonaktif')>nonaktif</option>
                </select>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Nama</label>
            <input name="nama" value="{{ old('nama', $pemasok->nama) }}" class="mt-1 w-full border rounded px-3 py-2"
                maxlength="255" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">NPWP (opsional)</label>
                <input name="npwp" value="{{ old('npwp', $pemasok->npwp) }}" class="mt-1 w-full border rounded px-3 py-2"
                    maxlength="50">
            </div>
            <div>
                <label class="text-sm font-medium">Nama Kontak (opsional)</label>
                <input name="nama_kontak" value="{{ old('nama_kontak', $pemasok->nama_kontak) }}"
                    class="mt-1 w-full border rounded px-3 py-2" maxlength="160">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Telepon (opsional)</label>
                <input name="telepon" value="{{ old('telepon', $pemasok->telepon) }}"
                    class="mt-1 w-full border rounded px-3 py-2" maxlength="50">
            </div>
            <div>
                <label class="text-sm font-medium">Email (opsional)</label>
                <input name="email" value="{{ old('email', $pemasok->email) }}"
                    class="mt-1 w-full border rounded px-3 py-2" maxlength="255">
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Alamat (opsional)</label>
            <textarea name="alamat" class="mt-1 w-full border rounded px-3 py-2" rows="3">{{ old('alamat', $pemasok->alamat) }}</textarea>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('pemasok.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">Kembali</a>
            <button class="px-3 py-2 rounded bg-gray-900 text-white text-sm">Update</button>
        </div>
    </form>
@endsection
