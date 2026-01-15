@extends('layouts.app')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold">Buat Permintaan Persetujuan</h1>

        <a href="{{ route('permintaan-persetujuan.index') }}"
            class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
            Kembali
        </a>
    </div>

    @if (session('error'))
        <div class="mb-4 p-3 rounded bg-red-50 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-50 text-red-700 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('permintaan-persetujuan.store') }}"
        class="bg-white border rounded p-4 space-y-4">
        @csrf

        <div>
            <label class="text-sm font-medium">Alur Persetujuan</label>
            <select name="alur_persetujuan_id" class="mt-1 w-full border rounded px-3 py-2 text-sm" required>
                <option value="">-- pilih --</option>
                @foreach ($alur as $a)
                    <option value="{{ $a->id }}" @selected(old('alur_persetujuan_id') == $a->id)>
                        {{ $a->nama }} ({{ $a->kode }}) | {{ $a->berlaku_untuk }}
                    </option>
                @endforeach
            </select>
            @error('alur_persetujuan_id')
                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Tipe Entitas</label>
                <input name="tipe_entitas" value="{{ old('tipe_entitas') }}"
                    class="mt-1 w-full border rounded px-3 py-2 text-sm"
                    placeholder="contoh: penugasan_aset" required>
                @error('tipe_entitas')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="text-sm font-medium">ID Entitas</label>
                <input name="id_entitas" value="{{ old('id_entitas') }}"
                    class="mt-1 w-full border rounded px-3 py-2 text-sm"
                    placeholder="contoh: 123" required>
                @error('id_entitas')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Ringkasan</label>
            <textarea name="ringkasan" rows="4"
                class="mt-1 w-full border rounded px-3 py-2 text-sm"
                placeholder="Ringkasan pengajuan (opsional)">{{ old('ringkasan') }}</textarea>
            @error('ringkasan')
                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex gap-2 pt-2">
            <a href="{{ route('permintaan-persetujuan.index') }}"
                class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
                Batal
            </a>

            <button class="px-3 py-2 rounded bg-gray-900 text-white text-sm">
                Buat Permintaan
            </button>
        </div>
    </form>
@endsection
