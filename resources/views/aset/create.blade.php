@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="text-xl font-semibold">Tambah Aset</h1>
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

    <form method="POST" action="{{ route('aset.store') }}" class="bg-white border rounded-lg border-gray-300 p-4 space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label>Nama Barang</label>
                <select name="barang_id" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2" required>
                    <option value="">Pilih Barang</option>
                    @foreach ($barang as $b)
                        <option value="{{ $b->id }}" @selected(old('barang_id') == $b->id)>
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
                        <option value="{{ $s }}" @selected(old('status_siklus', 'tersedia') === $s)>
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
                        <option value="{{ $g->id }}" @selected(old('gudang_saat_ini_id') == $g->id)>
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
                        <option value="{{ $l->id }}" @selected(old('lokasi_saat_ini_id') == $l->id)>
                            {{ $l->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- <div>
                <label class="text-sm font-medium">Nomor Polisi</label>
                <input name="no_polisi" value="{{ old('no_polisi') }}" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2">
            </div> --}}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Nomor Serial</label>
                <input name="no_serial" value="{{ old('no_serial') }}"
                    class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2">
            </div>

            <div>
                <label class="text-sm font-medium">IMEI</label>
                <input name="imei" value="{{ old('imei') }}"
                    class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2">
            </div>
            <div>
                <label class="text-sm font-medium">Biaya Perolehan</label>
                <input type="number" name="biaya_perolehan" value="{{ old('biaya_perolehan') }}"
                    class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2" placeholder="Contoh: 15000000"
                    min="0" step="1" required>
            </div>
            <div>
                <label class="text-sm font-medium">Mata Uang</label>
                <input name="mata_uang" value="{{ old('mata_uang') }}"
                    class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2" placeholder="IDR/USD">
            </div>
            <div>
                <label class="text-sm font-medium">Tag Aset</label>
                <input name="tag_aset" value="{{ old('tag_aset') }}"
                    class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2" required>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Keterangan</label>
            {{-- <textarea name="status_kondisi" rows="4" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2"
                placeholder="Keterangan kondisi barang">{{ old('status_kondisi') }}</textarea> --}}
            <select name="status_kondisi" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2" required>
                @foreach (['baik', 'rusak ringan', 'rusak berat', 'hilang', 'dalam perbaikan'] as $k)
                    <option value="{{ $k }}" @selected(old('status_kondisi', 'baik') === $k)>
                        {{ ucfirst($k) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('aset.index') }}"
                class="px-3 py-2 rounded-lg border-gray-300 border text-sm hover:bg-gray-50 btn-outline-active">
                Batal
            </a>

            <button class="px-3 py-2 rounded-lg border-gray-300 btn-active text-white text-sm">
                Simpan
            </button>
        </div>
    </form>
@endsection
