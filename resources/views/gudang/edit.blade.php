@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="text-xl font-semibold">Edit Gudang</h1>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('gudang.update', $gudang->id) }}"
        class="bg-white border rounded-lg border-gray-300 p-4 space-y-4">
        @csrf
        @method('put')

        <div>
            <label class="text-sm font-medium">Unit Organisasi (opsional)</label>
            <select name="unit_organisasi_id" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2">
                <option value="">-</option>
                @foreach ($unit as $u)
                    <option value="{{ $u->id }}" @selected(old('unit_organisasi_id', $gudang->unit_organisasi_id) == $u->id)>{{ $u->nama }}
                        ({{ $u->kode }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Kode</label>
                <input name="kode" value="{{ old('kode', $gudang->kode) }}"
                    class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2" maxlength="80" required>
            </div>
            <div>
                <label class="text-sm font-medium">Status</label>
                <select name="status" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2" required>
                    <option value="aktif" @selected(old('status', $gudang->status) === 'aktif')>aktif</option>
                    <option value="nonaktif" @selected(old('status', $gudang->status) === 'nonaktif')>nonaktif</option>
                </select>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Nama</label>
            <input name="nama" value="{{ old('nama', $gudang->nama) }}"
                class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2" maxlength="200" required>
        </div>

        <div>
            <label class="text-sm font-medium">Alamat (opsional)</label>
            <textarea name="alamat" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2" rows="3">{{ old('alamat', $gudang->alamat) }}</textarea>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('gudang.index') }}"
                class="px-3 py-2 rounded-lg btn-outline-active border text-sm hover:bg-gray-50">Kembali</a>
            <button class="px-3 py-2 rounded-lg btn-active text-white text-sm">Update</button>
        </div>
    </form>
@endsection
