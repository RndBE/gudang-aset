@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Pemasok</h1>
        <a href="{{ route('pemasok.create') }}"
            class="btn-active px-4 py-2 lg:px-6 lg:py-3 rounded-lg text-sm  text-center">Tambah</a>
    </div>

    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-3">Kode</th>
                        <th class="text-left p-3">Nama</th>
                        <th class="text-left p-3">Kontak</th>
                        <th class="text-left p-3">Telepon</th>
                        <th class="text-left p-3">Email</th>
                        <th class="text-left p-3">Status</th>
                        <th class="text-right p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr class="border-t border-gray-300">
                            <td class="p-3 whitespace-nowrap">{{ $row->kode }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $row->nama }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $row->nama_kontak ?? '-' }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $row->telepon ?? '-' }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $row->email ?? '-' }}</td>
                            <td class="p-3 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 rounded text-xs {{ $row->status === 'aktif' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td class="p-3 whitespace-nowrap text-right">
                                <a class="px-3 py-1 rounded-lg border text-sm hover:bg-gray-50 btn-outline-active"
                                    href="{{ route('pemasok.edit', $row->id) }}">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-3 text-gray-500" colspan="7">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
