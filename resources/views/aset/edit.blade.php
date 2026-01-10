@extends('layouts.app')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold">Edit Aset</h1>
        <a href="{{ route('aset.index', $aset->id) }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
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

    <form method="POST" action="{{ route('aset.update', $aset->id) }}" class="bg-white border rounded p-4 space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Tag Aset</label>
                <input name="tag_aset" value="{{ old('tag_aset', $aset->tag_aset) }}"
                    class="mt-1 w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="text-sm font-medium">Status</label>
                <select name="status_siklus" class="mt-1 w-full border rounded px-3 py-2" required>
                    @foreach (['tersedia', 'dipinjam', 'ditugaskan', 'disimpan', 'perawatan'] as $s)
                        <option value="{{ $s }}" @selected(old('status_siklus', $aset->status_siklus) === $s)>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Nomor Serial</label>
                <input name="no_serial" value="{{ old('no_serial', $aset->no_serial) }}"
                    class="mt-1 w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="text-sm font-medium">IMEI</label>
                <input name="imei" value="{{ old('imei', $aset->imei) }}" class="mt-1 w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Nomor Polisi</label>
            <input name="no_polisi" value="{{ old('no_polisi', $aset->no_polisi) }}"
                class="mt-1 w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="text-sm font-medium">Keterangan</label>
            <input name="no_polisi" value="{{ old('no_polisi', $aset->status_kondisi) }}"
                class="mt-1 w-full border rounded px-3 py-2">
        </div>

        <div class="flex gap-2">
            <a href="{{ route('aset.index', $aset->id) }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
                Batal
            </a>

            <button class="px-3 py-2 rounded bg-gray-900 text-white text-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>
@endsection
