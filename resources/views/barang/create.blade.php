@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="text-xl font-semibold">Tambah Barang</h1>
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

    <form method="post" action="{{ route('barang.store') }}" class="bg-white border rounded p-4 space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Kategori</label>
                <select name="kategori_id" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="">Pilih</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id }}" @selected((string) old('kategori_id') === (string) $k->id)>{{ $k->nama }}
                            ({{ $k->kode }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium">Satuan</label>
                <select name="satuan_id" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="">Pilih</option>
                    @foreach ($satuan as $s)
                        <option value="{{ $s->id }}" @selected((string) old('satuan_id') === (string) $s->id)>{{ $s->nama }}
                            ({{ $s->kode }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">SKU</label>
                <input name="sku" value="{{ old('sku') }}" class="mt-1 w-full border rounded px-3 py-2"
                    maxlength="120" required>
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
                <label class="text-sm font-medium">Merek (opsional)</label>
                <input name="merek" value="{{ old('merek') }}" class="mt-1 w-full border rounded px-3 py-2"
                    maxlength="160">
            </div>
            <div>
                <label class="text-sm font-medium">Model (opsional)</label>
                <input name="model" value="{{ old('model') }}" class="mt-1 w-full border rounded px-3 py-2"
                    maxlength="160">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Tipe Barang</label>
                <select name="tipe_barang" class="mt-1 w-full border rounded px-3 py-2" required>
                    @foreach (['habis_pakai', 'aset', 'keduanya'] as $t)
                        <option value="{{ $t }}" @selected(old('tipe_barang', 'habis_pakai') === $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium">Metode Pelacakan</label>
                <select name="metode_pelacakan" class="mt-1 w-full border rounded px-3 py-2" required>
                    @foreach (['tanpa', 'lot', 'kedaluwarsa', 'serial'] as $m)
                        <option value="{{ $m }}" @selected(old('metode_pelacakan', 'tanpa') === $m)>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Stok Minimum</label>
                <input name="stok_minimum" value="{{ old('stok_minimum', 0) }}"
                    class="mt-1 w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="text-sm font-medium">Titik Pesan Ulang</label>
                <input name="titik_pesan_ulang" value="{{ old('titik_pesan_ulang', 0) }}"
                    class="mt-1 w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Spesifikasi Barang</label>
            <textarea name="spesifikasi_json" class="mt-1 w-full border rounded px-3 py-2" rows="5"
                placeholder='{"warna":"hitam","ukuran":"M"}'>{{ old('spesifikasi_json') }}</textarea>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('barang.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">Batal</a>
            <button class="px-3 py-2 rounded bg-gray-900 text-white text-sm">Simpan</button>
        </div>
    </form>
@endsection
