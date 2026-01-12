@extends('layouts.app')

@section('content')
    <div class="space-y-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Buat Penghapusan Aset</h1>
            <a href="{{ route('penghapusan-aset.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
                Kembali
            </a>
        </div>

        <!-- Error -->
        @if ($errors->any())
            <div class="p-3 rounded bg-red-50 text-red-700 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('penghapusan-aset.store') }}" class="bg-white border rounded p-4 space-y-4">
            @csrf

            <!-- Nomor -->
            <div>
                <label class="text-sm font-medium">Nomor Penghapusan</label>
                <input name="nomor_penghapusan" value="{{ old('nomor_penghapusan', $nomor) }}"
                    class="mt-1 w-full border rounded px-3 py-2">
            </div>

            <!-- Aset -->
            <div>
                <label class="text-sm font-medium">Aset</label>
                <select name="aset_id" class="mt-1 w-full border rounded px-3 py-2">
                    <option value="">Pilih aset</option>
                    @foreach ($aset as $a)
                        <option value="{{ $a->id }}" @selected(old('aset_id') == $a->id)>
                            {{ $a->tag_aset }} — {{ $a->no_serial ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal & Metode -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Tanggal Penghapusan</label>
                    <input type="date" name="tanggal_penghapusan" value="{{ old('tanggal_penghapusan') }}"
                        class="mt-1 w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="text-sm font-medium">Metode</label>
                    <select name="metode" class="mt-1 w-full border rounded px-3 py-2">
                        @foreach (['hapus', 'hibah', 'lelang', 'rusak_total', 'lainnya'] as $m)
                            <option value="{{ $m }}" @selected(old('metode') == $m)>
                                {{ ucfirst(str_replace('_', ' ', $m)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Alasan -->
            <div>
                <label class="text-sm font-medium">Alasan</label>
                <textarea name="alasan" class="mt-1 w-full border rounded px-3 py-2" rows="4">{{ old('alasan') }}</textarea>
            </div>

            <!-- Action -->
            <div class="flex gap-2">
                <a href="{{ route('penghapusan-aset.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
                    Batal
                </a>
                <button class="px-3 py-2 rounded bg-red-600 text-white text-sm hover:bg-red-700">
                    Simpan Draft
                </button>
            </div>

        </form>

    </div>
@endsection
