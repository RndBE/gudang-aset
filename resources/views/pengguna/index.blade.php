@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div class="text-lg font-semibold">Pengguna</div>
        <a class="bg-black text-white px-3 py-2 rounded" href="{{ route('pengguna.create') }}">Tambah</a>
    </div>

    <div class="bg-white border rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left p-3">Nama</th>
                    <th class="text-left p-3">Username</th>
                    <th class="text-left p-3">Instansi</th>
                    <th class="text-left p-3">Unit</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-left p-3 w-32">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr class="border-t">
                        <td class="p-3">{{ $row->nama_lengkap }}</td>
                        <td class="p-3">{{ $row->username }}</td>
                        <td class="p-3">{{ $row->instansi?->nama }}</td>
                        <td class="p-3">{{ $row->unitOrganisasi?->nama }}</td>
                        <td class="p-3">{{ $row->status }}</td>
                        <td class="p-3">
                            <a class="underline" href="{{ route('pengguna.edit', $row) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
    