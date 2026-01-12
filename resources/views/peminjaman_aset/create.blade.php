@extends('layouts.app')

@section('content')
    <div class="space-y-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Buat Peminjaman Aset</h1>
            <a href="{{ route('peminjaman-aset.index') }}" class="px-3 py-2 border rounded text-sm hover:bg-gray-50">
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded bg-red-50 text-red-700 text-sm">
                <div class="font-semibold mb-2">Validasi gagal:</div>
                <ul class="list-disc ml-5 space-y-1">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('peminjaman-aset.store') }}" class="bg-white border rounded p-6 space-y-5">
            @csrf

            <!-- Nomor Dok -->
            <div>
                <label class="block text-sm font-medium mb-1">Nomor Dok Serah Terima</label>
                <input name="nomor_dok_serah_terima" value="{{ old('nomor_dok_serah_terima', $nomor) }}"
                    class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-100">
            </div>

            <!-- Aset -->
            <div>
                <label class="block text-sm font-medium mb-1">Aset</label>
                <select name="aset_id" class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-100">
                    <option value="">-- pilih aset --</option>
                    @foreach ($aset as $a)
                        <option value="{{ $a->id }}" @selected(old('aset_id') == $a->id)>
                            {{ $a->tag_aset }} | Serial: {{ $a->no_serial ?? '-' }} | IMEI: {{ $a->imei ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                    <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                        class="w-full border rounded px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Jatuh Tempo</label>
                    <input type="datetime-local" name="jatuh_tempo" value="{{ old('jatuh_tempo') }}"
                        class="w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>

            <!-- Peminjam -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Peminjam Pengguna (ID)</label>
                    <input name="peminjam_pengguna_id" value="{{ old('peminjam_pengguna_id') }}" placeholder="misal: 12"
                        class="w-full border rounded px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Peminjam Unit (ID)</label>
                    <input name="peminjam_unit_id" value="{{ old('peminjam_unit_id') }}" placeholder="misal: 4"
                        class="w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>

            <!-- Tujuan -->
            <div>
                <label class="block text-sm font-medium mb-1">Tujuan Peminjaman</label>
                <textarea name="tujuan" rows="3" class="w-full border rounded px-3 py-2 text-sm">{{ old('tujuan') }}</textarea>
            </div>

            <!-- Kondisi & Catatan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Kondisi Keluar</label>
                    <select name="kondisi_keluar" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="">-- pilih --</option>
                        @foreach (['baik', 'rusak_ringan', 'rusak_berat'] as $k)
                            <option value="{{ $k }}" @selected(old('kondisi_keluar') == $k)>
                                {{ ucfirst(str_replace('_', ' ', $k)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Catatan</label>
                    <textarea name="catatan" rows="3" class="w-full border rounded px-3 py-2 text-sm">{{ old('catatan') }}</textarea>
                </div>
            </div>

            <!-- Action -->
            <div class="flex gap-2 pt-4 border-t">
                <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                    Simpan
                </button>
                <a href="{{ route('peminjaman-aset.index') }}" class="px-4 py-2 rounded border hover:bg-gray-50">
                    Batal
                </a>
            </div>

        </form>

    </div>
@endsection
