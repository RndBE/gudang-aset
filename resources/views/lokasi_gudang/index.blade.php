@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Lokasi Gudang</h1>
        <a href="{{ route('lokasi-gudang.create', ['gudang_id' => $gudangId]) }}"
            class="px-3 py-2 rounded bg-gray-900 text-white text-sm">Tambah</a>
    </div>

    <form method="get" class="mb-4 bg-white border rounded p-3 flex flex-col md:flex-row gap-3 md:items-end">
        <div class="w-full md:w-80">
            <label class="text-sm font-medium">Filter Gudang</label>
            <select name="gudang_id" class="mt-1 w-full border rounded px-3 py-2">
                <option value="">Semua</option>
                @foreach ($gudang as $g)
                    <option value="{{ $g->id }}" @selected((string) $gudangId === (string) $g->id)>{{ $g->nama }}
                        ({{ $g->kode }})</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button class="px-3 py-2 rounded bg-gray-900 text-white text-sm">Terapkan</button>
            <a href="{{ route('lokasi-gudang.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">Reset</a>
        </div>
    </form>

    <div class="bg-white border rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left p-3">Gudang</th>
                    <th class="text-left p-3">Tipe</th>
                    <th class="text-left p-3">Kode</th>
                    <th class="text-left p-3">Nama</th>
                    <th class="text-left p-3">Induk</th>
                    <th class="text-left p-3">Picking</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-right p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    <tr class="border-t">
                        <td class="p-3">{{ $row->gudang?->nama ?? '-' }}</td>
                        <td class="p-3">{{ $row->tipe_lokasi }}</td>
                        <td class="p-3">{{ $row->kode }}</td>
                        <td class="p-3">{{ $row->nama ?? '-' }}</td>
                        <td class="p-3">
                            @if ($row->induk)
                                {{ $row->induk->kode }}{{ $row->induk->nama ? ' - ' . $row->induk->nama : '' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="p-3">
                            <span
                                class="px-2 py-1 rounded text-xs {{ $row->bisa_picking ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $row->bisa_picking ? 'ya' : 'tidak' }}
                            </span>
                        </td>
                        <td class="p-3">
                            <span
                                class="px-2 py-1 rounded text-xs {{ $row->status === 'aktif' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $row->status }}
                            </span>
                        </td>
                        <td class="p-3 text-right">
                            <a class="px-3 py-1 rounded border text-sm hover:bg-gray-50"
                                href="{{ route('lokasi-gudang.edit', $row->id) }}">Edit</a>
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
