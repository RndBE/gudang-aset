@extends('layouts.app')

@section('content')
    <div class="bg-white border rounded p-4 max-w-2xl">
        <div class="text-lg font-semibold mb-4">Tambah Instansi</div>

        <form method="post" action="{{ route('instansi.store') }}" class="space-y-3">
            @csrf

            <div>
                <label class="block text-sm mb-1">Kode</label>
                <input name="kode" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Nama</label>
                <input name="nama" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2" required>
                    <option value="aktif">aktif</option>
                    <option value="nonaktif">nonaktif</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button class="bg-black text-white px-3 py-2 rounded">Simpan</button>
                <a class="px-3 py-2 rounded border" href="{{ route('instansi.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
