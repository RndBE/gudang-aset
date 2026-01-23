@extends('layouts.app')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold">Edit Aset</h1>
        <a href="{{ route('aset.index', $aset->id) }}"
            class="px-3 py-2 rounded-lg btn-active border text-sm hover:bg-gray-50">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg border-gray-300 bg-red-50 text-red-700 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('aset.update', $aset->id) }}"
        class="bg-white border rounded-lg border-gray-300 text-sm p-4 space-y-4" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label>Nama Barang</label>
                <select name="barang_id" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2" required>
                    <option value="">Pilih Barang</option>
                    @foreach ($barang as $b)
                        <option value="{{ $b->id }}" @selected(old('barang_id', $aset->barang_id) == $b->id)>
                            {{ $b->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Status</label>
                <select name="status_siklus" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2"
                    required>
                    @foreach (['tersedia', 'dipinjam', 'ditugaskan', 'disimpan', 'perawatan'] as $s)
                        <option value="{{ $s }}" @selected(old('status_siklus', $aset->status_siklus) === $s)>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Gudang</label>
                <select name="gudang_saat_ini_id" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2"
                    required>
                    <option value="">Pilih Gudang</option>
                    @foreach ($gudang as $g)
                        <option value="{{ $g->id }}" @selected(old('gudang_saat_ini_id', $aset->gudang_saat_ini_id) == $g->id)>
                            {{ $g->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Lokasi Gudang</label>
                <select name="lokasi_saat_ini_id" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2"
                    required>
                    <option value="">Pilih Lokasi Gudang</option>
                    @foreach ($lokasi as $l)
                        <option value="{{ $l->id }}" @selected(old('lokasi_saat_ini_id', $aset->lokasi_saat_ini_id) == $l->id)>
                            {{ $l->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium">Tag Aset</label>
                <input name="tag_aset" value="{{ old('tag_aset', $aset->tag_aset) }}"
                    class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2" required>
            </div>
            <div>
                <label class="text-sm font-medium">Nomor Serial</label>
                <input name="no_serial" value="{{ old('no_serial', $aset->no_serial) }}"
                    class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Biaya Perolehan</label>
                <input type="number" name="biaya_perolehan" value="{{ old('biaya_perolehan', $aset->biaya_perolehan) }}"
                    class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2" placeholder="Contoh: 15000000"
                    min="0" step="1" required>
            </div>
            <div>
                <label class="text-sm font-medium">Mata Uang</label>
                <input name="mata_uang" value="{{ old('mata_uang', $aset->mata_uang) }}"
                    class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2" placeholder="IDR/USD">
            </div>

            <div>
                <label class="text-sm font-medium">IMEI</label>
                <input name="imei" value="{{ old('imei', $aset->imei) }}"
                    class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2">
            </div>
            <div>
                <label class="text-sm font-medium">Gambar Barang</label>
                @if (!empty($barang?->gambar))
                    <div class="mt-2 mb-3">
                        <img src="{{ asset('storage/' . $barang->gambar) }}"
                            class="h-28 w-28 object-cover rounded border border-gray-200">
                    </div>
                @endif

                <input type="file" name="gambar" accept="image/*"
                    class="mt-1 w-full border rounded-lg text-sm border-gray-300 px-3 py-2">
                <div class="text-xs text-gray-500 mt-1">JPG/JPEG/PNG/WEBP max 4MB</div>
            </div>
        </div>

        {{-- <div>
            <label class="text-sm font-medium">Nomor Polisi</label>
            <input name="no_polisi" value="{{ old('no_polisi', $aset->no_polisi) }}"
                class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2">
        </div> --}}

        <div>
            <label class="text-sm font-medium">Keterangan</label>
            <select name="status_kondisi" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2" required>
                @foreach (['baik', 'rusak_ringan', 'rusak_berat', 'hilang', 'dalam_perbaikan'] as $k)
                    <option value="{{ $k }}" @selected(old('status_kondisi', $aset->status_kondisi) === $k)>
                        {{ ucfirst($k) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('aset.index', $aset->id) }}"
                class="px-3 py-2 rounded-lg border-gray-300 text-sm border btn-outline-active hover:bg-gray-50">
                Batal
            </a>

            <button class="px-3 py-2 rounded-lg btn-active cursor-pointer text-white text-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>
@endsection
