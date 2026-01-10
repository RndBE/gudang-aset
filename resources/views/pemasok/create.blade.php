@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="text-xl font-semibold">Tambah Pemasok</h1>
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

    <form method="post" action="{{ route('pemasok.store') }}" class="bg-white border rounded p-4 space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Kode</label>
                <input name="kode" value="{{ old('kode') }}" class="mt-1 w-full border rounded px-3 py-2" maxlength="80"
                    required>
            </div>
            <div>
                <label class="text-sm font-medium">Status</label>
                <select name="status" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>aktif</option>
                    <option value="nonaktif" @selected(old('status') === 'nonaktif')>nonaktif</option>
                </select>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Nama</label>
            <input name="nama" value="{{ old('nama') }}" class="mt-1 w-full border rounded px-3 py-2" maxlength="255"
                required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">NPWP (opsional)</label>
                <input name="npwp" value="{{ old('npwp') }}" class="mt-1 w-full border rounded px-3 py-2"
                    maxlength="50">
            </div>
            <div>
                <label class="text-sm font-medium">Nama Kontak (opsional)</label>
                <input name="nama_kontak" value="{{ old('nama_kontak') }}" class="mt-1 w-full border rounded px-3 py-2"
                    maxlength="160">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Telepon (opsional)</label>
                <input name="telepon" value="{{ old('telepon') }}" class="mt-1 w-full border rounded px-3 py-2"
                    maxlength="50">
            </div>
            <div>
                <label class="text-sm font-medium">Email (opsional)</label>
                <input name="email" value="{{ old('email') }}" class="mt-1 w-full border rounded px-3 py-2"
                    maxlength="255">
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Alamat (opsional)</label>
            <textarea name="alamat" class="mt-1 w-full border rounded px-3 py-2" rows="3">{{ old('alamat') }}</textarea>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('pemasok.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">Batal</a>
            <button class="px-3 py-2 rounded bg-gray-900 text-white text-sm">Simpan</button>
        </div>
    </form>
@endsection
