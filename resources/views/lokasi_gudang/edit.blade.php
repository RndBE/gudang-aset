@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="text-xl font-semibold">Edit Lokasi Gudang</h1>
</div>

@if($errors->any())
    <div class="mb-4 p-3 rounded bg-red-50 text-red-700 text-sm">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="post" action="{{ route('lokasi-gudang.update', $lokasi_gudang->id) }}" class="bg-white border rounded p-4 space-y-4">
    @csrf
    @method('put')

    <div>
        <label class="text-sm font-medium">Gudang</label>
        <input class="mt-1 w-full border rounded px-3 py-2 bg-gray-50" value="{{ $lokasi_gudang->gudang?->nama ?? '' }}" readonly>
    </div>

    <div>
        <label class="text-sm font-medium">Induk (opsional)</label>
        <select name="induk_id" class="mt-1 w-full border rounded px-3 py-2">
            <option value="">-</option>
            @foreach($lokasiInduk as $l)
                <option value="{{ $l->id }}" @selected((string)old('induk_id', $lokasi_gudang->induk_id) === (string)$l->id)>{{ $l->kode }}{{ $l->nama ? ' - '.$l->nama : '' }} ({{ $l->tipe_lokasi }})</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium">Tipe Lokasi</label>
            <select name="tipe_lokasi" class="mt-1 w-full border rounded px-3 py-2" required>
                @foreach(['zona','lorong','rak','ambalan','bin','ruang','halaman','lainnya'] as $t)
                    <option value="{{ $t }}" @selected(old('tipe_lokasi', $lokasi_gudang->tipe_lokasi) === $t)>{{ $t }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">Status</label>
            <select name="status" class="mt-1 w-full border rounded px-3 py-2" required>
                <option value="aktif" @selected(old('status',$lokasi_gudang->status)==='aktif')>aktif</option>
                <option value="nonaktif" @selected(old('status',$lokasi_gudang->status)==='nonaktif')>nonaktif</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium">Kode</label>
            <input name="kode" value="{{ old('kode', $lokasi_gudang->kode) }}" class="mt-1 w-full border rounded px-3 py-2" maxlength="120" required>
        </div>
        <div>
            <label class="text-sm font-medium">Nama (opsional)</label>
            <input name="nama" value="{{ old('nama', $lokasi_gudang->nama) }}" class="mt-1 w-full border rounded px-3 py-2" maxlength="200">
        </div>
    </div>

    <div>
        <label class="text-sm font-medium">Jalur (opsional)</label>
        <input name="jalur" value="{{ old('jalur', $lokasi_gudang->jalur) }}" class="mt-1 w-full border rounded px-3 py-2" maxlength="600">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium">Bisa Picking</label>
            <select name="bisa_picking" class="mt-1 w-full border rounded px-3 py-2" required>
                <option value="1" @selected((string)old('bisa_picking', (int)$lokasi_gudang->bisa_picking) === '1')>ya</option>
                <option value="0" @selected((string)old('bisa_picking', (int)$lokasi_gudang->bisa_picking) === '0')>tidak</option>
            </select>
        </div>
    </div>

    <div class="flex gap-2">
        <a href="{{ route('lokasi-gudang.index', ['gudang_id' => $lokasi_gudang->gudang_id]) }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">Kembali</a>
        <button class="px-3 py-2 rounded bg-gray-900 text-white text-sm">Update</button>
    </div>
</form>
@endsection
