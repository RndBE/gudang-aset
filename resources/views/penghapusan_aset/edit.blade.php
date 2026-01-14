@extends('layouts.app')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold">Edit Penghapusan Aset</h1>

        <a href="{{ route('penghapusan-aset.index', $data->id) }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
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

    <form method="POST" action="{{ route('penghapusan-aset.update', $data->id) }}"
        class="bg-white border rounded p-4 space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Nomor Penghapusan</label>
                <input name="nomor_penghapusan" value="{{ old('nomor_penghapusan', $data->nomor_penghapusan) }}"
                    class="mt-1 w-full border rounded px-3 py-2" required>
                @error('nomor_penghapusan')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="text-sm font-medium">Pilih Aset</label>
                <select name="aset_id" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="">-- pilih --</option>

                    @foreach ($aset as $a)
                        @php
                            $selectedId = old('aset_id', $data->aset_id);
                        @endphp

                        <option value="{{ $a->id }}" @selected((string) $selectedId === (string) $a->id)>
                            {{ $a->tag_aset }} | {{ $a->no_serial ?? '-' }}
                            @if ((string) $selectedId === (string) $a->id)
                                {{-- (dipilih) --}}
                            @endif
                        </option>
                    @endforeach
                </select>

                @error('aset_id')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Tanggal Penghapusan</label>
                <input type="date" name="tanggal_penghapusan"
                    value="{{ old('tanggal_penghapusan', optional($data->tanggal_penghapusan)->format('Y-m-d')) }}"
                    class="mt-1 w-full border rounded px-3 py-2" required>
                @error('tanggal_penghapusan')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="text-sm font-medium">Metode</label>
                <select name="metode" class="mt-1 w-full border rounded px-3 py-2" required>
                    @foreach (['hapus', 'hibah', 'lelang', 'rusak_total', 'lainnya'] as $m)
                        <option value="{{ $m }}" @selected(old('metode', $data->metode) == $m)>
                            {{ ucfirst(str_replace('_', ' ', $m)) }}
                        </option>
                    @endforeach
                </select>
                @error('metode')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Alasan</label>
            <textarea name="alasan" rows="4" class="mt-1 w-full border rounded px-3 py-2" required>{{ old('alasan', $data->alasan) }}</textarea>
            @error('alasan')
                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 pt-2">
            <div class="flex gap-2">
                <a href="{{ route('penghapusan-aset.index', $data->id) }}"
                    class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
                    Batal
                </a>

                <button class="px-3 py-2 rounded bg-gray-900 text-white text-sm">
                    Simpan Perubahan
                </button>
            </div>

            {{-- <form method="POST" action="{{ route('penghapusan-aset.destroy', $data->id) }}"
                onsubmit="return confirm('Hapus draft ini?')">
                @csrf
                @method('DELETE')
                <button class="px-3 py-2 rounded border border-red-300 bg-red-50 text-red-700 text-sm hover:bg-red-100">
                    Hapus Draft
                </button>
            </form> --}}
        </div>
    </form>
@endsection
