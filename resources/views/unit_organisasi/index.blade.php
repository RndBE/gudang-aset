@extends('layouts.app')

@section('content')
    <div class="flex text-center items-center justify-between mb-4">
        <div class="text-xl font-semibold">Unit Organisasi</div>
        <a class="btn-active px-4 py-2 lg:px-6 lg:py-3 rounded-lg text-sm  text-center"
            href="{{ route('unit-organisasi.create') }}">Tambah</a>
    </div>

    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="min-w-225 w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-3 whitespace-nowrap">Kode</th>
                        <th class="text-left p-3 whitespace-nowrap">Nama</th>
                        <th class="text-left p-3 whitespace-nowrap hidden md:table-cell">Tipe</th>
                        <th class="text-left p-3 whitespace-nowrap hidden lg:table-cell">Instansi</th>
                        <th class="text-left p-3 whitespace-nowrap hidden lg:table-cell">Induk</th>
                        <th class="text-left p-3 whitespace-nowrap">Status</th>
                        <th class="text-left p-3 whitespace-nowrap w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr class="border-t border-gray-300">
                            <td class="p-3 whitespace-nowrap">{{ $row->kode }}</td>
                            <td class="p-3">{{ $row->nama }}</td>
                            <td class="p-3 whitespace-nowrap hidden md:table-cell">{{ $row->tipe_unit }}</td>
                            <td class="p-3 hidden lg:table-cell">{{ $row->instansi?->nama }}</td>
                            <td class="p-3 hidden lg:table-cell">{{ $row->induk?->nama }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $row->status }}</td>
                            <td class="p-3 whitespace-nowrap">
                                <a class="px-3 py-2 rounded-lg btn-outline-active inline-flex items-center justify-center"
                                    href="{{ route('unit-organisasi.edit', $row) }}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
