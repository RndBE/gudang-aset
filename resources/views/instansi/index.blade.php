@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div class="text-xl font-semibold">Instansi</div>
        <a class="btn-active px-6 py-3 rounded-lg" href="{{ route('instansi.create') }}">Tambah</a>
    </div>

    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left p-3">Kode</th>
                    <th class="text-left p-3">Nama</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-left p-3 w-32">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr class="border-t border-gray-300">
                        <td class="p-3">{{ $row->kode }}</td>
                        <td class="p-3">{{ $row->nama }}</td>
                        <td class="p-3">{{ $row->status }}</td>
                        <td class="p-3">
                            <a class="underline" href="{{ route('instansi.edit', $row) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
