@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="text-xl font-semibold">Edit Kategori</h1>
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

    <form method="post" action="{{ route('kategori-barang.update', $kategori_barang->id) }}"
        class="bg-white border rounded p-4 space-y-4">
        @csrf
        @method('put')

        <div>
            <label class="text-sm font-medium">Induk (opsional)</label>
            <select name="induk_id" class="mt-1 w-full border rounded px-3 py-2">
                <option value="">-</option>
                @foreach ($induk as $i)
                    <option value="{{ $i->id }}" @selected((string) old('induk_id', $kategori_barang->induk_id) === (string) $i->id)>{{ $i->nama }}
                        ({{ $i->kode }})</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Kode</label>
                <input name="kode" value="{{ old('kode', $kategori_barang->kode) }}"
                    class="mt-1 w-full border rounded px-3 py-2" maxlength="80" required>
            </div>
            <div>
                <label class="text-sm font-medium">Default Aset</label>
                <select name="default_aset" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="0" @selected((string) old('default_aset', (int) $kategori_barang->default_aset) === '0')>tidak</option>
                    <option value="1" @selected((string) old('default_aset', (int) $kategori_barang->default_aset) === '1')>ya</option>
                </select>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Nama</label>
            <input name="nama" value="{{ old('nama', $kategori_barang->nama) }}"
                class="mt-1 w-full border rounded px-3 py-2" maxlength="200" required>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('kategori-barang.index') }}"
                class="px-3 py-2 rounded border text-sm hover:bg-gray-50">Kembali</a>
            <button class="px-3 py-2 rounded bg-gray-900 text-white text-sm">Update</button>
        </div>
    </form>
@endsection
