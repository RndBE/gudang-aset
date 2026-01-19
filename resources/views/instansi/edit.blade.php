@extends('layouts.app')

@section('content')
    <div class="bg-white border rounded-lg border-gray-300 p-4 max-w-2xl">
        <div class="text-lg font-semibold mb-4">Edit Instansi</div>

        <form method="post" action="{{ route('instansi.update', $instansi) }}" class="space-y-3">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm mb-1">Kode</label>
                <input name="kode" value="{{ $instansi->kode }}" class="w-full border rounded-lg border-gray-300 px-3 py-2"
                    required>
            </div>

            <div>
                <label class="block text-sm mb-1">Nama</label>
                <input name="nama" value="{{ $instansi->nama }}"
                    class="w-full border rounded-lg border-gray-300 px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Status</label>
                <select name="status" class="w-full border rounded-lg border-gray-300 px-3 py-2" required>
                    <option value="aktif" @selected($instansi->status === 'aktif')>aktif</option>
                    <option value="nonaktif" @selected($instansi->status === 'nonaktif')>nonaktif</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button class="bg-black text-white px-3 py-2 rounded-lg text-sm cursor-pointer btn-active">Simpan</button>
                <a class="px-3 py-2 rounded-lg btn-outline-active text-sm border"
                    href="{{ route('instansi.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
