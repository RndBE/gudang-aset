@extends('layouts.app')

@section('content')
    <div class="space-y-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Penugasan Aset</h1>
            <a href="{{ route('penugasan-aset.create') }}"
                class="px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
                Buat Penugasan
            </a>
        </div>

        <!-- Alert -->
        @if (session('success'))
            <div class="p-3 rounded bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 rounded bg-red-50 text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white border rounded overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="p-3 border-b">Tanggal</th>
                        <th class="p-3 border-b">Tag Aset</th>
                        <th class="p-3 border-b">No Dokumen</th>
                        <th class="p-3 border-b">Status</th>
                        <th class="p-3 border-b w-[160px]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b">
                                {{ $row->tanggal_tugas?->translatedFormat('d F Y') }}
                            </td>

                            <td class="p-3 border-b">
                                {{ $row->aset->tag_aset ?? '-' }}
                            </td>

                            <td class="p-3 border-b">
                                {{ $row->nomor_dok_serah_terima }}
                            </td>

                            <td class="p-3 border-b">
                                <span
                                    class="px-2 py-1 rounded text-xs
                                {{ $row->status == 'aktif' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($row->status) }}
                                </span>
                            </td>

                            <td class="p-3 border-b">
                                <a href="{{ route('penugasan-aset.show', $row->id) }}"
                                    class="px-2 py-1 rounded border text-xs hover:bg-gray-100">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">
                                Belum ada data penugasan
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
