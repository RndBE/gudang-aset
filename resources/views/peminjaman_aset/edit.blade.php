@extends('layouts.app')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold">Edit Peminjaman Aset</h1>

        <a href="{{ route('peminjaman-aset.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
            Kembali
        </a>
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

    @if (session('error'))
        <div class="mb-4 p-3 rounded bg-red-50 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('peminjaman-aset.update', $data->id) }}"
        class="bg-white border rounded p-4 space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Nomor Dokumen</label>
                <input name="nomor_dok_serah_terima"
                    value="{{ old('nomor_dok_serah_terima', $data->nomor_dok_serah_terima) }}"
                    class="mt-1 w-full border rounded px-3 py-2" required>
                @error('nomor_dok_serah_terima')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="text-sm font-medium">Status</label>
                <select name="status" class="mt-1 w-full border rounded px-3 py-2" required>
                    @foreach (['draft', 'aktif', 'dikembalikan', 'dibatalkan'] as $s)
                        <option value="{{ $s }}" @selected(old('status', $data->status) === $s)>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Tanggal Peminjaman</label>
                <input type="datetime-local" name="tanggal_mulai"
                    value="{{ old('tanggal_mulai', optional($data->tanggal_mulai)->format('Y-m-d\TH:i')) }}"
                    class="mt-1 w-full border rounded px-3 py-2" required>
                @error('tanggal_mulai')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="text-sm font-medium">Jatuh Tempo</label>
                <input type="datetime-local" name="jatuh_tempo"
                    value="{{ old('jatuh_tempo', optional($data->jatuh_tempo)->format('Y-m-d\TH:i')) }}"
                    class="mt-1 w-full border rounded px-3 py-2" required>
                @error('jatuh_tempo')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Pilih Aset</label>
            <select name="aset_id" class="mt-1 w-full border rounded px-3 py-2" required>
                <option value="">-- pilih aset --</option>

                @foreach ($aset as $a)
                    @php $selectedId = old('aset_id', $data->aset_id); @endphp
                    <option value="{{ $a->id }}" @selected((string) $selectedId === (string) $a->id)>
                        {{ $a->tag_aset }}
                        @if ($a->no_serial)
                            | SN: {{ $a->no_serial }}
                        @endif
                        @if ($a->imei)
                            | IMEI: {{ $a->imei }}
                        @endif
                    </option>
                @endforeach
            </select>
            @error('aset_id')
                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
            @enderror

            @if ($selectedId)
                <p class="mt-2 text-xs text-gray-500">
                    Aset yang dipilih akan tetap tampil meskipun asetnya sudah tidak aktif (bergantung query controller).
                </p>
            @endif
        </div>
        <!-- Kondisi & Catatan -->
        {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-4"> --}}
        <div>
            <div>
                <label class="block text-sm font-medium mb-1">Kondisi keluar</label>
                <select name="kondisi_keluar" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">-- pilih --</option>
                    @foreach (['baik', 'rusak_ringan', 'rusak_berat'] as $k)
                        <option value="{{ $k }}" @selected(old('kondisi_keluar', $data->kondisi_keluar) == $k)>
                            {{ ucfirst(str_replace('_', ' ', $k)) }}
                        </option>
                    @endforeach
                </select>

                @error('kondisi_keluar')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Tujuan -->
        <div>
            <label class="block text-sm font-medium mb-1">Tujuan Peminjaman</label>
            <textarea name="tujuan" rows="3" class="w-full border rounded px-3 py-2 text-sm">{{ old('tujuan', $data->tujuan) }}</textarea>
            @error('tujuan')
                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>


        {{-- <div>
            <label class="text-sm font-medium">Catatan / Keterangan</label>
            <textarea name="catatan" rows="4" class="mt-1 w-full border rounded px-3 py-2"
                placeholder="Catatan peminjaman (opsional)">{{ old('catatan', $data->catatan) }}</textarea>
            @error('catatan')
                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div> --}}

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 pt-2">
            <div class="flex gap-2">
                <a href="{{ route('peminjaman-aset.index', $data->id) }}"
                    class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
                    Batal
                </a>

                <button class="px-3 py-2 rounded bg-gray-900 text-white text-sm">
                    Simpan Perubahan
                </button>
            </div>

            @if (old('status', $data->status) === 'draft')
                <form method="POST" action="{{ route('peminjaman-aset.destroy', $data->id) }}"
                    onsubmit="return confirm('Hapus draft peminjaman ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-2 rounded border border-red-300 bg-red-50 text-red-700 text-sm hover:bg-red-100">
                        Hapus Draft
                    </button>
                </form>
            @endif
        </div>
    </form>
@endsection
