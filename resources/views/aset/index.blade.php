@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Aset</h1>
        <div class="flex items-center ">
            <a href="{{ route('aset.create') }}" class="px-6 py-3 rounded-lg btn-active text-sm me-3">
                Tambah Aset
            </a>
            <a href="{{ route('export.aset') }}" class="px-6 py-3 rounded-lg btn-active text-sm">Export Aset</a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-3 p-3 rounded-lg bg-green-50 text-green-700 text-sm">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const el = document.getElementById('alert-success');
                if (el) el.remove();
            }, 3000);
        </script>
    @endif

    <form class="flex gap-2 mb-4">
        <input name="q" value="{{ $q }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64"
            placeholder="Cari tag / serial / imei / no polisi">

        <select name="status" class="border rounded-lg px-3 py-2 text-sm border-gray-300">
            <option value="">Semua Status</option>
            @foreach (['tersedia', 'dipinjam', 'ditugaskan', 'disimpan', 'perawatan', 'dihapus'] as $s)
                <option value="{{ $s }}" {{ $status == $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                </option>
            @endforeach
        </select>

        <button class="px-4 py-2 rounded-lg bg-gray-200 cursor-pointer text-sm">
            Filter
        </button>
    </form>

    <div class="bg-white border rounded-lg border-gray-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="border-b border-gray-300">
                        <th class="text-left p-3">Nama Barang</th>
                        <th class="text-left p-3">Tanggal Beli</th>
                        <th class="text-left p-3">Tag</th>
                        <th class="text-left p-3">Serial</th>
                        <th class="text-left p-3">IMEI</th>
                        {{-- <th class="text-left p-3">No Polisi</th> --}}
                        <th class="text-left p-3">Status</th>
                        <th class="text-right p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr class="border-t border-gray-300">
                            <td class="p-3 font-medium">{{ $row->barang?->nama ?? '-' }}</td>
                            <td class="p-3 font-medium">{{ $row->dibuat_pada?->translatedFormat('d F Y, H:i') ?? '-' }}</td>
                            <td class="p-3 font-medium">{{ $row->tag_aset }}</td>
                            <td class="p-3">{{ $row->no_serial ?? '-' }}</td>
                            <td class="p-3">{{ $row->imei ?? '-' }}</td>
                            {{-- <td class="p-3">{{ $row->no_polisi ?? '-' }}</td> --}}
                            <td class="p-3">
                                @php
                                    $warna = match ($row->status_siklus) {
                                        'tersedia' => 'bg-green-100 text-green-800',
                                        'dipinjam' => 'bg-sky-100 text-sky-800',
                                        'ditugaskan' => 'bg-lime-100 text-lime-800',
                                        'disimpan' => 'bg-blue-100 text-blue-800',
                                        'perawatan' => 'bg-yellow-100 text-yellow-800',
                                        'dihapus' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp

                                <span class="px-2 py-1 rounded-lg border-gray-300 text-xs {{ $warna }}">
                                    {{ $row->status_siklus }}
                                </span>
                            </td>
                            <td class="p-3 text-right space-x-2 flex">
                                <a class="px-3 py-1 rounded-lg border-gray-300 border text-sm hover:bg-gray-50"
                                    href="{{ route('aset.show', $row->id) }}">Detail</a>

                                <a class="px-3 py-1 rounded-lg border-gray-300 border text-sm hover:bg-gray-50"
                                    href="{{ route('aset.edit', $row->id) }}">Edit</a>

                                <a class="px-3 py-1 rounded-lg border-gray-300 border text-sm text-red-600 hover:bg-red-50"
                                    href="*">Hapus</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-3 text-gray-500 text-center">
                                Belum ada data aset.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <div class="mt-4">
        {{ $data->links() }}
    </div>
@endsection
