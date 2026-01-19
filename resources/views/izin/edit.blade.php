@extends('layouts.app')

@section('content')
    <div class="bg-white border rounded-lg border-gray-300 p-4 max-w-2xl">
        <div class="text-lg font-semibold mb-4">Edit Izin</div>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="post" action="{{ route('izin.update', $izin) }}" class="space-y-3">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm mb-1">Kode</label>
                <input name="kode" value="{{ $izin->kode }}"
                    class="w-full border rounded-lg border-gray-300 px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Nama</label>
                <input name="nama" value="{{ $izin->nama }}"
                    class="w-full border rounded-lg border-gray-300 px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border rounded-lg border-gray-300 px-3 py-2 text-sm" rows="2">{{ $izin->deskripsi }}</textarea>
            </div>

            <div class="flex gap-2">
                <a class="px-3 py-2 rounded-lg text-sm border-gray-300 border btn-outline-active"
                    href="{{ route('izin.index') }}">Batal</a>

                <button class="bg-black text-white px-3 py-2 text-sm rounded-lg border-gray-300 btn-active">Simpan</button>
            </div>
        </form>
    </div>
@endsection
