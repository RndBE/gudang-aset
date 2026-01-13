@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-xl font-semibold">Edit Penugasan Aset</h1>
                <p class="text-sm text-gray-500">
                    {{ $data->aset->tag_aset ?? '-' }} • Dokumen: {{ $data->nomor_dok_serah_terima ?? '-' }}
                </p>
            </div>

            <a href="{{ route('penugasan-aset.index', $data->id) }}"
                class="px-3 py-2 border rounded text-sm hover:bg-gray-100">
                Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded">
                <div class="font-semibold mb-1">Validasi gagal:</div>
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT: Summary -->
            <div class="bg-white border rounded">
                <div class="border-b px-4 py-3 font-semibold">
                    Ringkasan Penugasan
                </div>

                <div class="p-4 space-y-4 text-sm">

                    <div>
                        <div class="text-gray-500">Status</div>
                        @php
                            $c = 'bg-gray-100 text-gray-700';
                            if ($data->status === 'aktif') {
                                $c = 'bg-green-100 text-green-700';
                            }
                            if ($data->status === 'dikembalikan') {
                                $c = 'bg-blue-100 text-blue-700';
                            }
                            if ($data->status === 'dibatalkan') {
                                $c = 'bg-red-100 text-red-700';
                            }
                        @endphp
                        <span class="inline-flex px-2 py-1 text-xs rounded {{ $c }}">
                            {{ ucfirst($data->status) }}
                        </span>
                    </div>

                    <div>
                        <div class="text-gray-500">Tanggal Tugas</div>
                        <div class="font-medium">
                            {{ $data->tanggal_tugas?->translatedFormat('d F Y, H:i') ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-gray-500">Tanggal Kembali</div>
                        <div class="font-medium">
                            {{ $data->tanggal_kembali?->translatedFormat('d F Y, H:i') ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-gray-500">Nomor Dokumen</div>
                        <div class="font-medium">{{ $data->nomor_dok_serah_terima ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-gray-500">Catatan</div>
                        <div class="font-medium" style="white-space: pre-line;">
                            {{ $data->catatan ?? '-' }}
                        </div>
                    </div>
                    <div class="pt-2 border-t flex gap-2">
                        @if ($data->status === 'aktif')
                            <form method="POST" action="{{ route('penugasan-aset.kembalikan', $data->id) }}">

                                @csrf
                                <button class="px-3 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700"
                                    onclick="return confirm('Kembalikan aset ini?')">
                                    Kembalikan
                                </button>
                            </form>

                            <form method="POST" action="{{ route('penugasan-aset.batalkan', $data->id) }}">

                                @csrf
                                <button class="px-3 py-2 rounded bg-yellow-500 text-white text-sm hover:bg-yellow-600"
                                    onclick="return confirm('Batalkan penugasan ini?')">
                                    Batalkan
                                </button>
                            </form>
                        @else
                            <span class="px-3 py-2 rounded bg-gray-100 text-gray-500 text-sm">
                                Aksi tidak tersedia
                            </span>
                        @endif
                    </div>

                </div>
            </div>

            <!-- RIGHT: Form -->
            <div class="lg:col-span-2 bg-white border rounded">
                <div class="border-b px-4 py-3 font-semibold">
                    Form Edit Penugasan
                </div>

                <div class="p-4 space-y-6">

                    <!-- Informasi Aset -->
                    <div class="border rounded p-4">
                        <div class="font-semibold mb-3">Informasi Aset</div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-gray-500">Tag Aset</div>
                                <div class="font-medium">{{ $data->aset->tag_aset ?? '-' }}</div>
                            </div>

                            <div>
                                <div class="text-gray-500">No Serial</div>
                                <div class="font-medium">{{ $data->aset->no_serial ?? '-' }}</div>
                            </div>

                            <div>
                                <div class="text-gray-500">IMEI</div>
                                <div class="font-medium">{{ $data->aset->imei ?? '-' }}</div>
                            </div>

                            <div>
                                <div class="text-gray-500">No Polisi</div>
                                <div class="font-medium">{{ $data->aset->no_polisi ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('penugasan-aset.update', $data->id) }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Tanggal Tugas -->
                            <div>
                                <label class="text-sm font-medium">Tanggal Tugas</label>
                                <input type="datetime-local" name="tanggal_tugas"
                                    value="{{ old('tanggal_tugas', optional($data->tanggal_tugas)->format('Y-m-d\TH:i')) }}"
                                    class="w-full border rounded px-3 py-2 text-sm mt-1 @error('tanggal_tugas') border-red-400 @enderror">
                                @error('tanggal_tugas')
                                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Kembali -->
                            <div>
                                <label class="text-sm font-medium">Tanggal Kembali</label>
                                <input type="datetime-local" name="tanggal_kembali"
                                    value="{{ old('tanggal_kembali', optional($data->tanggal_kembali)->format('Y-m-d\TH:i')) }}"
                                    class="w-full border rounded px-3 py-2 text-sm mt-1 @error('tanggal_kembali') border-red-400 @enderror">
                                <div class="text-xs text-gray-500 mt-1">Kosongkan jika belum dikembalikan.</div>
                                @error('tanggal_kembali')
                                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pengguna -->
                            <div>
                                <label class="text-sm font-medium">Ditugaskan ke Pengguna</label>
                                <select name="ditugaskan_ke_pengguna_id"
                                    class="w-full border rounded px-3 py-2 text-sm mt-1 @error('ditugaskan_ke_pengguna_id') border-red-400 @enderror">
                                    <option value="">-- pilih pengguna --</option>
                                    @foreach ($pengguna as $p)
                                        <option value="{{ $p->id }}" @selected(old('ditugaskan_ke_pengguna_id', $data->ditugaskan_ke_pengguna_id) == $p->id)>
                                            {{ $p->nama_lengkap }}
                                            {{-- {{ $p->username }} (ID: {{ $p->id }}) --}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ditugaskan_ke_pengguna_id')
                                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Unit -->
                            <div>
                                <label class="text-sm font-medium">Ditugaskan ke Unit</label>
                                <select name="ditugaskan_ke_unit_id"
                                    class="w-full border rounded px-3 py-2 text-sm mt-1 @error('ditugaskan_ke_unit_id') border-red-400 @enderror">
                                    <option value="">-- pilih unit --</option>
                                    @foreach ($unit as $u)
                                        <option value="{{ $u->id }}" @selected(old('ditugaskan_ke_unit_id', $data->ditugaskan_ke_unit_id) == $u->id)>
                                            {{ $u->nama_unit ?? ($u->nama ?? 'Unit ' . $u->id) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ditugaskan_ke_unit_id')
                                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="text-sm font-medium">Status</label>
                                <select name="status"
                                    class="w-full border rounded px-3 py-2 text-sm mt-1 @error('status') border-red-400 @enderror">
                                    @foreach (['aktif', 'dikembalikan', 'dibatalkan'] as $s)
                                        <option value="{{ $s }}" @selected(old('status', $data->status) == $s)>
                                            {{ ucfirst($s) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dokumen -->
                            <div>
                                <label class="text-sm font-medium">Nomor Dokumen Serah Terima</label>
                                <input name="nomor_dok_serah_terima"
                                    value="{{ old('nomor_dok_serah_terima', $data->nomor_dok_serah_terima) }}"
                                    class="w-full border rounded px-3 py-2 text-sm mt-1 @error('nomor_dok_serah_terima') border-red-400 @enderror">
                                @error('nomor_dok_serah_terima')
                                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Catatan -->
                        <div>
                            <label class="text-sm font-medium">Catatan</label>
                            <textarea name="catatan" rows="3"
                                class="w-full border rounded px-3 py-2 text-sm mt-1 @error('catatan') border-red-400 @enderror">{{ old('catatan', $data->catatan) }}</textarea>
                            @error('catatan')
                                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-2">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('penugasan-aset.index', $data->id) }}"
                                class="px-4 py-2 border rounded text-sm hover:bg-gray-100">
                                Batal
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
