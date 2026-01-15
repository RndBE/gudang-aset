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

            <script>
                setTimeout(() => {
                    const el = document.getElementById('alert-success');
                    if (el) el.remove();
                }, 3000);
            </script>
        @endif

        @if (session('error'))
            <div class="p-3 rounded bg-red-50 text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white border rounded overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Tag Aset</th>
                        <th class="p-3 text-left">No Dokumen</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="text-right p-3">Aksi</th>
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
                                @php
                                    $color = 'bg-gray-100 text-gray-700'; // default

                                    if ($row->status === 'sedang ditugaskan') {
                                        $color = 'bg-blue-100 text-blue-700';
                                    } elseif ($row->status === 'selesai ditugaskan') {
                                        $color = 'bg-green-200 text-green-800';
                                    } elseif ($row->status === 'dibatalkan') {
                                        $color = 'bg-red-100 text-red-700';
                                    }
                                @endphp

                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $color }}">
                                    {{ ucfirst($row->status) }}
                                </span>
                                {{-- <span
                                    class="px-2 py-1 rounded text-xs
                                {{ $row->status == 'aktif' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($row->status) }}
                                </span> --}}
                            </td>

                            <td class="p-3 border-b">
                                <div class="flex items-center justify-end gap-2 whitespace-nowrap">

                                    <a href="{{ route('penugasan-aset.show', $row->id) }}"
                                        class="px-3 py-1 border rounded text-xs hover:bg-gray-100">
                                        Detail
                                    </a>
                                    {{-- button muncul jika status sedang ditugaskan dan punya izin kelola --}}
                                    @if ($row->status === 'sedang ditugaskan' && auth()->user()->punyaIzin('penugasan_aset.kelola'))
                                        <a href="{{ route('penugasan-aset.edit', $row->id) }}"
                                            class="px-3 py-1 border rounded text-xs text-blue-700 hover:bg-blue-50">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('penugasan-aset.destroy', $row->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus data penugasan ini?')"
                                                class="px-3 py-1 border rounded text-xs text-red-700 hover:bg-red-50">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
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
