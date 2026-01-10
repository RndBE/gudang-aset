@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white border rounded p-4">
            <div class="text-lg font-semibold mb-3">Izin untuk Peran</div>

            @foreach ($peran as $p)
                <form method="post" action="{{ route('rbac.peran.izin', $p) }}" class="border rounded p-3 mb-3">
                    @csrf
                    <div class="font-semibold">{{ $p->nama }} ({{ $p->kode }})</div>
                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach ($izin as $iz)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="izin_id[]" value="{{ $iz->id }}"
                                    @checked($p->izin->contains('id', $iz->id))>
                                <span>{{ $iz->kode }}</span>
                            </label>
                        @endforeach
                    </div>
                    <button class="mt-3 bg-black text-white px-3 py-2 rounded">Simpan</button>
                </form>
            @endforeach
        </div>

        <div class="bg-white border rounded p-4">
            <div class="text-lg font-semibold mb-3">Peran untuk Pengguna</div>

            @foreach ($pengguna as $u)
                <form method="post" action="{{ route('rbac.pengguna.peran', $u) }}" class="border rounded p-3 mb-3">
                    @csrf
                    <div class="font-semibold">{{ $u->nama_lengkap }} ({{ $u->username }})</div>
                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach ($peran as $p)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="peran_id[]" value="{{ $p->id }}"
                                    @checked($u->peran->contains('id', $p->id))>
                                <span>{{ $p->kode }}</span>
                            </label>
                        @endforeach
                    </div>
                    <button class="mt-3 bg-black text-white px-3 py-2 rounded">Simpan</button>
                </form>
            @endforeach
        </div>
    </div>
@endsection
