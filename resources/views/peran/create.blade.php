@extends('layouts.app')

@section('content')
    <div class="bg-white border rounded p-4 max-w-2xl">
        <div class="text-lg font-semibold mb-4">Tambah Peran</div>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="post" action="{{ route('peran.store') }}" class="space-y-3">
            @csrf

            <div>
                <label class="block text-sm mb-1">Instansi</label>
                <select name="instansi_id" class="w-full border rounded px-3 py-2" required>
                    @foreach ($instansi as $i)
                        <option value="{{ $i->id }}">{{ $i->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Kode</label>
                <input name="kode" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Nama</label>
                <input name="nama" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border rounded px-3 py-2" rows="2"></textarea>
            </div>

            <div class="flex gap-2">
                <button class="bg-black text-white px-3 py-2 rounded">Simpan</button>
                <a class="px-3 py-2 rounded border" href="{{ route('peran.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
