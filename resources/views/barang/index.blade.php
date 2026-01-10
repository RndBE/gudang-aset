@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Barang</h1>
        <a href="{{ route('barang.create') }}" class="px-3 py-2 rounded bg-gray-900 text-white text-sm">Tambah</a>
    </div>

    <div class="bg-white border rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left p-3">SKU</th>
                    <th class="text-left p-3">Nama</th>
                    <th class="text-left p-3">Kategori</th>
                    <th class="text-left p-3">Satuan</th>
                    <th class="text-left p-3">Tipe</th>
                    <th class="text-left p-3">Pelacakan</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-right p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    <tr class="border-t">
                        <td class="p-3">{{ $row->sku }}</td>
                        <td class="p-3">
                            <div class="font-medium">{{ $row->nama }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $row->merek ?? '-' }}{{ $row->model ? ' · ' . $row->model : '' }}</div>
                        </td>
                        <td class="p-3">{{ $row->kategori?->nama ?? '-' }}</td>
                        <td class="p-3">{{ $row->satuan?->nama ?? '-' }}</td>
                        <td class="p-3">{{ $row->tipe_barang }}</td>
                        <td class="p-3">{{ $row->metode_pelacakan }}</td>
                        <td class="p-3">
                            <span
                                class="px-2 py-1 rounded text-xs {{ $row->status === 'aktif' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $row->status }}
                            </span>
                        </td>
                        <td class="p-3 text-right">
                            <a class="px-3 py-1 rounded border text-sm hover:bg-gray-50"
                                href="{{ route('barang.edit', $row->id) }}">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-3 text-gray-500" colspan="8">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
