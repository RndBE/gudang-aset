@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="text-xl font-semibold">Tambah Lokasi Gudang</h1>
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

    <form method="post" action="{{ route('lokasi-gudang.store') }}" class="bg-white border rounded p-4 space-y-4">
        @csrf

        <div>
            <label class="text-sm font-medium">Gudang</label>
            <select name="gudang_id" class="mt-1 w-full border rounded px-3 py-2" required>
                <option value="">Pilih</option>
                @foreach ($gudang as $g)
                    <option value="{{ $g->id }}" @selected((string) old('gudang_id', $gudangId) === (string) $g->id)>{{ $g->nama }}
                        ({{ $g->kode }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">Induk (opsional)</label>
            <select name="induk_id" class="mt-1 w-full border rounded px-3 py-2">
                <option value="">-</option>
                @foreach ($lokasiInduk as $l)
                    <option value="{{ $l->id }}" @selected((string) old('induk_id') === (string) $l->id)>
                        {{ $l->kode }}{{ $l->nama ? ' - ' . $l->nama : '' }} ({{ $l->tipe_lokasi }})</option>
                @endforeach
            </select>
            <div class="text-xs text-gray-500 mt-1">Induk harus satu gudang yang sama.</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Tipe Lokasi</label>
                <select name="tipe_lokasi" class="mt-1 w-full border rounded px-3 py-2" required>
                    @foreach (['zona', 'lorong', 'rak', 'ambalan', 'bin', 'ruang', 'halaman', 'lainnya'] as $t)
                        <option value="{{ $t }}" @selected(old('tipe_lokasi', 'bin') === $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Status</label>
                <select name="status" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>aktif</option>
                    <option value="nonaktif" @selected(old('status') === 'nonaktif')>nonaktif</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Kode</label>
                <input name="kode" value="{{ old('kode') }}" class="mt-1 w-full border rounded px-3 py-2"
                    maxlength="120" required>
            </div>
            <div>
                <label class="text-sm font-medium">Nama (opsional)</label>
                <input name="nama" value="{{ old('nama') }}" class="mt-1 w-full border rounded px-3 py-2"
                    maxlength="200">
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Jalur (opsional)</label>
            <input name="jalur" value="{{ old('jalur') }}" class="mt-1 w-full border rounded px-3 py-2" maxlength="600"
                placeholder="contoh: Z1/L2/R3/B7">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Bisa Picking</label>
                <select name="bisa_picking" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="1" @selected(old('bisa_picking', '1') === '1')>ya</option>
                    <option value="0" @selected(old('bisa_picking') === '0')>tidak</option>
                </select>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('lokasi-gudang.index', ['gudang_id' => $gudangId]) }}"
                class="px-3 py-2 rounded border text-sm hover:bg-gray-50">Batal</a>
            <button class="px-3 py-2 rounded bg-gray-900 text-white text-sm">Simpan</button>
        </div>
    </form>
@endsection
