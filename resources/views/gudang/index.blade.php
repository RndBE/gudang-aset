@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Gudang</h1>
        <a href="{{ route('gudang.create') }}" class="px-3 py-2 rounded bg-gray-900 text-white text-sm">Tambah</a>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 rounded bg-green-50 text-green-700 text-sm">{{ session('status') }}</div>
    @endif

    <div class="bg-white border rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left p-3">Kode</th>
                    <th class="text-left p-3">Nama</th>
                    <th class="text-left p-3">Unit</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-right p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    <tr class="border-t">
                        <td class="p-3">{{ $row->kode }}</td>
                        <td class="p-3">{{ $row->nama }}</td>
                        <td class="p-3">{{ $row->unitOrganisasi?->nama ?? '-' }}</td>
                        <td class="p-3">
                            <span
                                class="px-2 py-1 rounded text-xs {{ $row->status === 'aktif' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $row->status }}
                            </span>
                        </td>
                        <td class="p-3 text-right">
                            <a class="px-3 py-1 rounded border text-sm hover:bg-gray-50"
                                href="{{ route('gudang.edit', $row->id) }}">Edit</a>
                            <a class="ml-2 px-3 py-1 rounded border text-sm hover:bg-gray-50"
                                href="{{ route('lokasi-gudang.index', ['gudang_id' => $row->id]) }}">Lokasi</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-3 text-gray-500" colspan="5">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
