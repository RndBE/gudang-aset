@extends('layouts.app')

@section('content')
    <div class="bg-white border rounded p-4 max-w-3xl">
        <div class="text-lg font-semibold mb-4">Tambah Unit Organisasi</div>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="post" action="{{ route('unit-organisasi.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-3">
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
                <label class="block text-sm mb-1">Induk</label>
                <select name="induk_id" class="w-full border rounded px-3 py-2">
                    <option value="">-</option>
                    @foreach ($induk as $u)
                        <option value="{{ $u->id }}">{{ $u->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Tipe Unit</label>
                <select name="tipe_unit" class="w-full border rounded px-3 py-2" required>
                    <option value="mabes">mabes</option>
                    <option value="polda">polda</option>
                    <option value="polres">polres</option>
                    <option value="polsek">polsek</option>
                    <option value="satker" selected>satker</option>
                    <option value="unit">unit</option>
                    <option value="unit_gudang">unit_gudang</option>
                    <option value="lainnya">lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2" required>
                    <option value="aktif">aktif</option>
                    <option value="nonaktif">nonaktif</option>
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

            <div class="md:col-span-2">
                <label class="block text-sm mb-1">Alamat</label>
                <textarea name="alamat" class="w-full border rounded px-3 py-2" rows="2"></textarea>
            </div>

            <div>
                <label class="block text-sm mb-1">Telepon</label>
                <input name="telepon" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm mb-1">Email</label>
                <input name="email" class="w-full border rounded px-3 py-2">
            </div>

            <div class="md:col-span-2 flex gap-2">
                <button class="bg-black text-white px-3 py-2 rounded">Simpan</button>
                <a class="px-3 py-2 rounded border" href="{{ route('unit-organisasi.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
