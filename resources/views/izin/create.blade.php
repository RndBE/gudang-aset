@extends('layouts.app')

@section('content')
    <div class="bg-white border border-gray-300 rounded-lg p-4 max-w-2xl">
        <div class="text-lg font-semibold mb-4">Tambah Izin</div>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="post" action="{{ route('izin.store') }}" class="space-y-3">
            @csrf

            <div>
                <label class="block text-sm mb-1">Kode</label>
                <input name="kode" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Nama</label>
                <input name="nama" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border border-gray-300 rounded-lg px-3 py-2" rows="2"></textarea>
            </div>

            <div class="flex gap-2">
                <a class="px-3 py-2 rounded-lg border btn-outline-active text-sm" href="{{ route('izin.index') }}">Batal</a>

                <button class="cursor-pointer btn-active text-sm text-white px-3 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
@endsection
