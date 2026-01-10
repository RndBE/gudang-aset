@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="text-xl font-semibold">Edit Satuan</h1>
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

    <form method="post" action="{{ route('satuan-barang.update', $satuan_barang->id) }}"
        class="bg-white border rounded p-4 space-y-4">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Kode</label>
                <input name="kode" value="{{ old('kode', $satuan_barang->kode) }}"
                    class="mt-1 w-full border rounded px-3 py-2" maxlength="30" required>
            </div>
            <div>
                <label class="text-sm font-medium">Nama</label>
                <input name="nama" value="{{ old('nama', $satuan_barang->nama) }}"
                    class="mt-1 w-full border rounded px-3 py-2" maxlength="60" required>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('satuan-barang.index') }}"
                class="px-3 py-2 rounded border text-sm hover:bg-gray-50">Kembali</a>
            <button class="px-3 py-2 rounded bg-gray-900 text-white text-sm">Update</button>
        </div>
    </form>
@endsection
