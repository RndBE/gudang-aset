@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Tambah Alur Persetujuan</h1>
            <a href="{{ route('alur-persetujuan.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-100">
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white border rounded p-6">
            <form method="POST" action="{{ route('alur-persetujuan.store') }}" class="space-y-6">
                @csrf

                <!-- Info Alur -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Nama Alur</label>
                        <input name="nama" value="{{ old('nama') }}" class="w-full border rounded px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Kode</label>
                        <input name="kode" value="{{ old('kode') }}" class="w-full border rounded px-3 py-2 text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm text-gray-600">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="w-full border rounded px-3 py-2 text-sm">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="md:col-span-2 flex items-center gap-2">
                        <input type="checkbox" name="aktif" id="aktif" class="rounded border" checked>
                        <label for="aktif" class="text-sm">Aktif</label>
                    </div>
                </div>

                <!-- Langkah Persetujuan -->
                <div>
                    <div class="font-semibold mb-2">Langkah Persetujuan</div>

                    @for ($i = 1; $i <= 3; $i++)
                        <div class="border rounded p-4 mb-3 bg-gray-50">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500">Urutan</label>
                                    <input name="langkah[{{ $i }}][urutan]" value="{{ $i }}"
                                        class="w-full border rounded px-2 py-1 text-sm">
                                </div>

                                <div>
                                    <label class="text-xs text-gray-500">Nama Langkah</label>
                                    <input name="langkah[{{ $i }}][nama_langkah]"
                                        value="Persetujuan {{ $i }}"
                                        class="w-full border rounded px-2 py-1 text-sm">
                                </div>

                                <div>
                                    <label class="text-xs text-gray-500">Peran ID</label>
                                    <input name="langkah[{{ $i }}][peran_id]"
                                        class="w-full border rounded px-2 py-1 text-sm" placeholder="contoh: 2">
                                </div>

                                <div>
                                    <label class="text-xs text-gray-500">Izin Khusus</label>
                                    <input name="langkah[{{ $i }}][izin_khusus]"
                                        class="w-full border rounded px-2 py-1 text-sm" placeholder="opsional">
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <button class="px-4 py-2 rounded bg-gray-900 text-white text-sm hover:bg-gray-800">
                        Simpan
                    </button>
                    <a href="{{ route('alur-persetujuan.index') }}"
                        class="px-4 py-2 rounded border text-sm hover:bg-gray-100">
                        Batal
                    </a>
                </div>

            </form>
        </div>

    </div>
@endsection
