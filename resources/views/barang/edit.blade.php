@extends('layouts.app')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Edit Barang</h1>

        <a href="{{ route('barang.index') }}" class="px-3 py-2 rounded-lg btn-active  border text-sm hover:bg-gray-50">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('barang.update', $barang->id) }}"
        class="bg-white border rounded-lg text-sm border-gray-300     p-4 space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Kategori</label>
                <select name="kategori_id" class="mt-1 w-full border rounded-lg text-sm border-gray-300  px-3 py-2"
                    required>
                    <option value="">Pilih</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id }}"
                            {{ (string) old('kategori_id', $barang->kategori_id) === (string) $k->id ? 'selected' : '' }}>
                            {{ $k->nama }} ({{ $k->kode }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Satuan</label>
                <select name="satuan_id" class="mt-1 w-full border rounded-lg text-sm border-gray-300    px-3 py-2"
                    required>
                    <option value="">Pilih</option>
                    @foreach ($satuan as $s)
                        <option value="{{ $s->id }}"
                            {{ (string) old('satuan_id', $barang->satuan_id) === (string) $s->id ? 'selected' : '' }}>
                            {{ $s->nama }} ({{ $s->kode }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">SKU</label>
                <input name="sku" value="{{ old('sku', $barang->sku) }}"
                    class="mt-1 w-full border rounded-lg text-sm border-gray-300     px-3 py-2" maxlength="120" required>
            </div>

            <div>
                <label class="text-sm font-medium">Status</label>
                <select name="status" class="mt-1 w-full border rounded-lg text-sm border-gray-300   px-3 py-2" required>
                    <option value="aktif" {{ old('status', $barang->status) === 'aktif' ? 'selected' : '' }}>aktif
                    </option>
                    <option value="nonaktif" {{ old('status', $barang->status) === 'nonaktif' ? 'selected' : '' }}>nonaktif
                    </option>
                </select>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Nama</label>
            <input name="nama" value="{{ old('nama', $barang->nama) }}"
                class="mt-1 w-full border rounded-lg text-sm border-gray-300     px-3 py-2" maxlength="255" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Merek (opsional)</label>
                <input name="merek" value="{{ old('merek', $barang->merek) }}"
                    class="mt-1 w-full border rounded-lg text-sm border-gray-300     px-3 py-2" maxlength="160">
            </div>

            <div>
                <label class="text-sm font-medium">Model (opsional)</label>
                <input name="model" value="{{ old('model', $barang->model) }}"
                    class="mt-1 w-full border rounded-lg text-sm border-gray-300     px-3 py-2" maxlength="160">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Tipe Barang</label>
                <select name="tipe_barang" class="mt-1 w-full border rounded-lg text-sm border-gray-300  px-3 py-2"
                    required>
                    @foreach (['habis_pakai', 'aset', 'keduanya'] as $t)
                        <option value="{{ $t }}"
                            {{ old('tipe_barang', $barang->tipe_barang) === $t ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Metode Pelacakan</label>
                <select name="metode_pelacakan" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    @foreach (['tanpa', 'lot', 'kedaluwarsa', 'serial'] as $m)
                        <option value="{{ $m }}"
                            {{ old('metode_pelacakan', $barang->metode_pelacakan) === $m ? 'selected' : '' }}>
                            {{ $m }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @php
            $fmtDecimal = function ($val) {
                if ($val === null || $val === '') {
                    return '';
                }
                $num = (string) $val;
                if (!str_contains($num, '.')) {
                    return $num;
                }
                return rtrim(rtrim($num, '0'), '.');
            };
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Stok Minimum</label>
                <input type="number" step="0.01" inputmode="decimal" min="0" name="stok_minimum"
                    value="{{ old('stok_minimum', $fmtDecimal($barang->stok_minimum ?? 0)) }}"
                    class="mt-1 w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div>
                <label class="text-sm font-medium">Titik Pesan Ulang</label>
                <input type="number" step="0.01" inputmode="decimal" min="0" name="titik_pesan_ulang"
                    value="{{ old('titik_pesan_ulang', $fmtDecimal($barang->titik_pesan_ulang ?? 0)) }}"
                    class="mt-1 w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
        </div>


        @php
            $spJson = old('spesifikasi_json');

            if ($spJson === null) {
                if (is_array($barang->spesifikasi)) {
                    $parts = [];

                    foreach ($barang->spesifikasi as $k => $v) {
                        if (is_string($k)) {
                            $parts[] = $k . ': ' . $v;
                        } else {
                            $parts[] = $v;
                        }
                    }

                    $spJson = implode(', ', $parts);
                } else {
                    $spJson = '';
                }
            }
        @endphp

        <div>
            <label class="text-sm font-medium">Spesifikasi</label>
            <textarea name="spesifikasi_json" class="mt-1 w-full border border-gray-300 rounded px-3 py-2" rows="6">{{ $spJson }}</textarea>

            {{-- <div class="text-xs text-gray-500 mt-1">
                Bisa input kalimat biasa atau JSON valid.
            </div> --}}
        </div>

        <div class="flex gap-2">
            <a href="{{ route('barang.index') }}" class="px-3 py-2 rounded-lg btn-active border text-sm hover:bg-gray-50">
                Batal
            </a>

            <button class="px-3 py-2 rounded-lg  btn-outline-active text-white text-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>
@endsection
