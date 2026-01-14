@extends('layouts.app')

@section('content')
    <div class="space-y-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Penghapusan Aset</h1>
            <a href="{{ route('penghapusan-aset.create') }}"
                class="px-3 py-2 rounded bg-red-600 text-white text-sm hover:bg-red-700">
                Buat Penghapusan
            </a>
        </div>

        <!-- Alert -->
        @if (session('success'))
            <div class="p-3 rounded bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(() => {
                    const el = document.getElementById('alert-success');
                    if (el) el.remove();
                }, 3000);
            </script>
        @endif

        <!-- Search -->
        <form class="flex gap-2">
            <input name="q" value="{{ $q }}" class="w-full md:w-1/3 border rounded px-3 py-2"
                placeholder="Cari tag / serial / alasan...">

            <button class="px-4 py-2 rounded bg-gray-800 text-white text-sm hover:bg-gray-900">
                Cari
            </button>
        </form>

        <!-- Table -->
        <div class="bg-white border rounded overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="p-3 border-b">No Penghapusan</th>
                        <th class="p-3 border-b">Tanggal</th>
                        <th class="p-3 border-b">Tag Aset</th>
                        <th class="p-3 border-b">Serial</th>
                        <th class="p-3 border-b">Metode</th>
                        <th class="p-3 border-b">Status</th>
                        <th class="p-3 border-b .w-[220px]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b">
                                {{ $row->nomor_penghapusan ?? '-' }}
                            </td>
                            <td class="p-3 border-b">
                                {{ optional($row->tanggal_penghapusan)->locale('id')->translatedFormat('j F Y') }}
                            </td>

                            <td class="p-3 border-b">
                                {{ $row->aset->tag_aset ?? '-' }}
                            </td>

                            <td class="p-3 border-b">
                                {{ $row->aset->no_serial ?? '-' }}
                            </td>

                            <td class="p-3 border-b">
                                {{ ucfirst($row->metode) }}
                            </td>

                            <td class="p-3 border-b">
                                <span
                                    class="px-2 py-1 rounded text-xs
                                {{ $row->status == 'disetujui' ? 'bg-green-50 text-green-700' : '' }}
                                {{ $row->status == 'menunggu' ? 'bg-yellow-50 text-yellow-700' : '' }}
                                {{ $row->status == 'ditolak' ? 'bg-red-50 text-red-700' : '' }}">
                                    {{ ucfirst($row->status) }}
                                </span>
                            </td>

                            <td class="p-3 border-b">
                                <div class="flex gap-2">
                                    <a href="{{ route('penghapusan-aset.show', $row->id) }}"
                                        class="px-2 py-1 rounded border text-xs hover:bg-gray-100">
                                        Detail
                                    </a>

                                    <a href="{{ route('penghapusan-aset.edit', $row->id) }}"
                                        class="px-2 py-1 rounded border text-xs hover:bg-blue-50 text-blue-700">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('penghapusan-aset.destroy', $row->id) }}"
                                        onsubmit="return confirm('Hapus data penghapusan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-2 py-1 rounded border text-xs hover:bg-red-50 text-red-700">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">
                                Belum ada data penghapusan aset
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div>
            {{ $data->links() }}
        </div>

    </div>
@endsection
