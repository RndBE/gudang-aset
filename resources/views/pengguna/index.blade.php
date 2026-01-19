@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div class="text-lg font-semibold">Pengguna</div>
        <a class=" text-white px-6 py-3 rounded-lg btn-active" href="{{ route('pengguna.create') }}">Tambah</a>
    </div>

    <div class="bg-white border rounded-lg border-gray-300 overflow-hidden">
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
                    <tr class="border-t border-gray-300">
                        <td class="p-3">{{ $row->nama_lengkap }}</td>
                        <td class="p-3">{{ $row->username }}</td>
                        <td class="p-3">{{ $row->instansi?->nama }}</td>
                        <td class="p-3">{{ $row->unitOrganisasi?->nama }}</td>
                        <td class="p-3">{{ $row->status }}</td>
                        <td class="p-3">
                            <a class="border px-3 py-1 rounded-lg border-gray-300 cursor-pointer"
                                href="{{ route('pengguna.edit', $row) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
