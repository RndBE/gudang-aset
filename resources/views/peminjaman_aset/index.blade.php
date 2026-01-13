@extends('layouts.app')

@section('content')
    <div class="space-y-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Peminjaman Aset</h1>
            <a href="{{ route('peminjaman-aset.create') }}"
                class="px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
                Buat Peminjaman
            </a>
        </div>

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

        <!-- Filter -->
        <form class="grid grid-cols-1 md:grid-cols-5 gap-2">
            <input name="q" value="{{ $q }}" class="border rounded px-3 py-2 text-sm"
                placeholder="Cari tag, serial, IMEI, dokumen...">

            <select name="status" class="border rounded px-3 py-2 text-sm">
                <option value="">Semua status</option>
                @foreach (['aktif', 'terlambat', 'dikembalikan', 'dibatalkan'] as $s)
                    <option value="{{ $s }}" @selected($status == $s)>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>

            <button class="px-3 py-2 bg-gray-600 text-white rounded text-sm">
                Filter
            </button>

            {{-- <a href="{{ route('peminjaman-aset.index') }}"
                class="px-3 py-2 border rounded text-sm text-center hover:bg-gray-50">
                Reset
            </a> --}}
        </form>

        <!-- Table -->
        <div class="bg-white border rounded overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-3 text-left">Tanggal Peminjaman</th>
                        <th class="p-3 text-left">Aset</th>
                        <th class="p-3 text-left">Jatuh Tempo</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Dokumen</th>
                        <th class="text-right p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">

                    @forelse($data as $row)
                        @php
                            $isOverdue = $row->status === 'aktif' && $row->jatuh_tempo && $row->jatuh_tempo->isPast();
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="p-3">
                                <div class="font-medium">
                                    {{ $row->tanggal_mulai?->translatedFormat('d F Y, H:i') ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Dibuat: {{ $row->dibuat_pada?->translatedFormat('d F Y, H:i') ?? '-' }}
                                </div>
                            </td>

                            <td class="p-3">
                                <div class="font-medium">{{ $row->aset->tag_aset ?? '-' }}</div>
                                <div class="text-xs text-gray-500">
                                    SN: {{ $row->aset->no_serial ?? '-' }} | IMEI: {{ $row->aset->imei ?? '-' }}
                                </div>
                            </td>

                            <td class="p-3">
                                @if ($row->jatuh_tempo)
                                    <div class="font-medium">
                                        {{ $row->jatuh_tempo->translatedFormat('d F Y, H:i') }}
                                    </div>

                                    @if ($isOverdue)
                                        <span class="text-xs px-2 py-1 rounded bg-red-100 text-red-700">
                                            Terlambat
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>

                            <td class="p-3">
                                {{-- @php
                                    $statusTampil = $row->status;

                                    if ($row->status === 'aktif' && $row->jatuh_tempo && now()->gt($row->jatuh_tempo)) {
                                        $statusTampil = 'terlambat';
                                    }

                                    $color = 'bg-gray-100 text-gray-700';
                                    if ($statusTampil === 'aktif') {
                                        $color = 'bg-blue-100 text-blue-700';
                                    }
                                    if ($statusTampil === 'terlambat') {
                                        $color = 'bg-red-100 text-red-700';
                                    }
                                    if ($statusTampil === 'dikembalikan') {
                                        $color = 'bg-green-100 text-green-700';
                                    }
                                    if ($statusTampil === 'dibatalkan') {
                                        $color = 'bg-gray-200 text-gray-600';
                                    }
                                @endphp

                                <span class="px-2 py-1 text-xs rounded {{ $color }}">
                                    {{ ucfirst($statusTampil) }}
                                </span> --}}
                                @php
                                    $color = 'bg-gray-100 text-gray-700';
                                    if ($row->status == 'aktif') {
                                        $color = 'bg-blue-100 text-blue-700';
                                    }
                                    if ($row->status == 'terlambat') {
                                        $color = 'bg-red-100 text-red-700';
                                    }
                                    if ($row->status == 'dikembalikan') {
                                        $color = 'bg-green-100 text-green-700';
                                    }
                                    if ($row->status == 'dibatalkan') {
                                        $color = 'bg-gray-200 text-gray-600';
                                    }
                                @endphp
                                <span class="px-2 py-1 text-xs rounded {{ $color }}">
                                    {{ ucfirst($row->status) }}
                                </span>
                            </td>

                            <td class="p-3">
                                {{ $row->nomor_dok_serah_terima ?? '-' }}
                            </td>

                            <td class="p-3 text-right space-x-1">
                                <a href="{{ route('peminjaman-aset.show', $row->id) }}"
                                    class="px-2 py-1 border rounded text-xs hover:bg-gray-50">
                                    Detail
                                </a>

                                @if ($row->status === 'aktif' || $row->status === 'terlambat')
                                    <a href="{{ route('peminjaman-aset.edit', $row->id) }}"
                                        class="px-2 py-1 border rounded text-xs text-blue-700 hover:bg-blue-50">
                                        Edit
                                    </a>

                                    <a href="{{ route('peminjaman-aset.show', $row->id) }}#pengembalian"
                                        class="px-2 py-1 border rounded text-xs text-green-700 hover:bg-green-50">
                                        Pengembalian
                                    </a>

                                    <form method="POST" action="{{ route('peminjaman-aset.batalkan', $row->id) }}"
                                        class="inline">
                                        @csrf
                                        <button class="px-2 py-1 border rounded text-xs text-yellow-700 hover:bg-yellow-50"
                                            onclick="return confirm('Batalkan peminjaman ini?')">
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500">
                                Belum ada data peminjaman aset.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div>
            {{ $data->links() }}
        </div>

    </div>
@endsection
