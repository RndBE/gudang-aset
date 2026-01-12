@extends('layouts.app')

@section('content')
    <div class="space-y-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Buat Penugasan Aset</h1>
            <a href="{{ route('penugasan-aset.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
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

        <form method="POST" action="{{ route('penugasan-aset.store') }}" class="bg-white border rounded p-4 space-y-4">
            @csrf

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

            <!-- Tanggal & Nomor Dok -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Tanggal Tugas</label>
                    <input type="datetime-local" name="tanggal_tugas" value="{{ old('tanggal_tugas') }}"
                        class="mt-1 w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="text-sm font-medium">Nomor Dokumen Serah Terima</label>
                    <input name="nomor_dok_serah_terima" value="{{ old('nomor_dok_serah_terima', $nomor) }}"
                        class="mt-1 w-full border rounded px-3 py-2">
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <label class="text-sm font-medium">Catatan</label>
                <textarea name="catatan" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('catatan') }}</textarea>
            </div>

            <!-- Action -->
            <div class="flex gap-2">
                <a href="{{ route('penugasan-aset.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
                    Batal
                </a>

                <button class="px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
                    Simpan
                </button>
            </div>

        </form>
    </div>
@endsection
