@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Barang</h1>
        <div class="flex items-center ">
            <a href="{{ route('barang.import_ocr') }}"
                class="flex items-center btn-active px-4 py-2 lg:px-6 lg:py-3 rounded-lg text-sm  text-center me-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-sparkles me-2">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path
                        d="M16 18a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2zm0 -12a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2zm-7 12a6 6 0 0 1 6 -6a6 6 0 0 1 -6 -6a6 6 0 0 1 -6 6a6 6 0 0 1 6 6z">
                    </path>
                </svg>Mass Upload</a>
            <a href="{{ route('barang.create') }}"
                class="btn-active px-4 py-2 lg:px-6 lg:py-3 rounded-lg text-sm  text-center me-3">Tambah</a>
            <a href="{{ route('export.barang') }}" class="btn-active px-4 py-2 lg:px-6 lg:py-3 rounded-lg text-sm  text-center">Export Barang</a>
        </div>
    </div>
    @if (session('success'))
        <div id="alert-success" class="mb-4 p-3 rounded bg-green-50 border border-green-200 text-green-800 text-sm">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const el = document.getElementById('alert-success');
                if (el) el.remove();
            }, 3000);
        </script>
    @endif

    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-3">Gambar</th>
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
                        <tr class="border-t border-gray-300 ">
                            <td class="p-3 whitespace-nowrap">
                                <img src="{{ asset('storage/' . $row->gambar) }}" class="w-auto h-24 object-contain rounded-t-lg flex">
                                {{-- <div class="w-full h-28 bg-gray-50 rounded-t-lg flex items-center justify-center overflow-hidden">
                                    <img src="{{ asset('storage/' . $row->gambar) }}" class="h-20 w-auto object-contain">
                                </div> --}}
                            </td>
                            <td class="p-3 whitespace-nowrap">{{ $row->sku }}</td>
                            <td class="p-3 whitespace-nowrap">
                                <div class="font-medium">{{ $row->nama }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $row->merek ?? '-' }}{{ $row->model ? ' · ' . $row->model : '' }}</div>
                            </td>
                            <td class="p-3 whitespace-nowrap">{{ $row->kategori?->nama ?? '-' }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $row->satuan?->nama ?? '-' }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $row->tipe_barang }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $row->metode_pelacakan }}</td>
                            <td class="p-3 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 rounded text-xs {{ $row->status === 'aktif' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td class="p-3 whitespace-nowrap text-right">
                                <a class="px-3 py-1 rounded-lg border text-sm hover:bg-gray-50 btn-outline-active"
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
    </div>
@endsection
