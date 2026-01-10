@extends('layouts.app')

@section('content')
    <div class="bg-white border rounded p-4 max-w-3xl">
        <div class="text-lg font-semibold mb-4">Edit Unit Organisasi</div>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="post" action="{{ route('unit-organisasi.update', $unit_organisasi) }}"
            class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm mb-1">Instansi</label>
                <select name="instansi_id" class="w-full border rounded px-3 py-2" required>
                    @foreach ($instansi as $i)
                        <option value="{{ $i->id }}" @selected($unit_organisasi->instansi_id == $i->id)>{{ $i->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Induk</label>
                <select name="induk_id" class="w-full border rounded px-3 py-2">
                    <option value="">-</option>
                    @foreach ($induk as $u)
                        <option value="{{ $u->id }}" @selected($unit_organisasi->induk_id == $u->id)>{{ $u->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Tipe Unit</label>
                <select name="tipe_unit" class="w-full border rounded px-3 py-2" required>
                    @foreach (['mabes', 'polda', 'polres', 'polsek', 'satker', 'unit', 'unit_gudang', 'lainnya'] as $t)
                        <option value="{{ $t }}" @selected($unit_organisasi->tipe_unit === $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2" required>
                    <option value="aktif" @selected($unit_organisasi->status === 'aktif')>aktif</option>
                    <option value="nonaktif" @selected($unit_organisasi->status === 'nonaktif')>nonaktif</option>
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Kode</label>
                <input name="kode" value="{{ $unit_organisasi->kode }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Nama</label>
                <input name="nama" value="{{ $unit_organisasi->nama }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm mb-1">Alamat</label>
                <textarea name="alamat" class="w-full border rounded px-3 py-2" rows="2">{{ $unit_organisasi->alamat }}</textarea>
            </div>

            <div>
                <label class="block text-sm mb-1">Telepon</label>
                <input name="telepon" value="{{ $unit_organisasi->telepon }}" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm mb-1">Email</label>
                <input name="email" value="{{ $unit_organisasi->email }}" class="w-full border rounded px-3 py-2">
            </div>

            <div class="md:col-span-2 flex gap-2">
                <button class="bg-black text-white px-3 py-2 rounded">Simpan</button>
                <a class="px-3 py-2 rounded border" href="{{ route('unit-organisasi.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
