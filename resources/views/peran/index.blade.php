@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div class="text-lg font-semibold">Peran</div>
        <a class="bg-black text-white px-3 py-2 rounded" href="{{ route('peran.create') }}">Tambah</a>
    </div>

    <div class="bg-white border rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left p-3">Kode</th>
                    <th class="text-left p-3">Nama</th>
                    <th class="text-left p-3">Instansi</th>
                    <th class="text-left p-3 w-32">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr class="border-t">
                        <td class="p-3">{{ $row->kode }}</td>
                        <td class="p-3">{{ $row->nama }}</td>
                        <td class="p-3">{{ $row->instansi?->nama }}</td>
                        <td class="p-3">
                            <a class="underline" href="{{ route('peran.edit', $row) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
