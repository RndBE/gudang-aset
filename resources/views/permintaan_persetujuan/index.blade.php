@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Permintaan Persetujuan</h1>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter -->
        <form class="bg-white border rounded p-4 grid grid-cols-1 md:grid-cols-6 gap-3">
            <div class="md:col-span-3">
                <input name="q" value="{{ $q }}" class="w-full border rounded px-3 py-2 text-sm"
                    placeholder="Cari judul atau tipe entitas...">
            </div>

            <div class="md:col-span-2">
                <select name="status" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Semua Status</option>
                    @foreach (['berjalan', 'disetujui', 'ditolak'] as $s)
                        <option value="{{ $s }}" @selected($status == $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-1">
                <button class="w-full bg-gray-800 text-white px-3 py-2 rounded hover:bg-gray-900 text-sm">
                    Filter
                </button>
            </div>
        </form>

        <!-- Table -->
        <div class="bg-white border rounded overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-2 text-left">Nomor Persetujuan</th>
                            <th class="px-4 py-2 text-left">Tipe</th>
                            <th class="px-4 py-2 text-left">Entitas</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-2 font-medium">{{ $row->nomor_persetujuan }}</td>
                                <td class="px-4 py-2">{{ $row->tipe_entitas }}</td>
                                <td class="px-4 py-2">{{ $row->id_entitas }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $color = 'bg-gray-200 text-gray-700';
                                        if ($row->status == 'berjalan') {
                                            $color = 'bg-blue-100 text-blue-700';
                                        }
                                        if ($row->status == 'disetujui') {
                                            $color = 'bg-green-100 text-green-700';
                                        }
                                        if ($row->status == 'ditolak') {
                                            $color = 'bg-red-100 text-red-700';
                                        }
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded {{ $color }}">
                                        {{ ucfirst($row->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('permintaan-persetujuan.show', $row->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 text-xs border rounded hover:bg-gray-100">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-gray-500">
                                    Belum ada permintaan persetujuan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t bg-gray-50">
                {{ $data->links() }}
            </div>
        </div>

    </div>
@endsection
