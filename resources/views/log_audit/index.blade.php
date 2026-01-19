@extends('layouts.app')

@section('content')
    {{-- Header --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Log Audit</h2>
        <a href="{{ route('log-audit.index') }}"
            class="px-4 py-2 text-sm border btn-outline-active rounded-lg bg-white hover:bg-gray-50">
            Reset Filter
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-lg border-gray-300 border p-4 mb-4">
        <form class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-3">
                <input name="q" value="{{ $q }}"
                    class="w-full border rounded-lg border-gray-300 px-3 py-2 text-sm"
                    placeholder="Cari tabel / referensi / ID">
            </div>

            <div class="md:col-span-2">
                <select name="aksi" class="w-full border rounded-lg border-gray-300 px-3 py-2 text-sm">
                    <option value="">Semua aksi</option>
                    @foreach ($listAksi as $a)
                        <option value="{{ $a }}" @selected($aksi == $a)>
                            {{ ucfirst($a) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <select name="nama_tabel" class="w-full border rounded-lg border-gray-300 px-3 py-2 text-sm">
                    <option value="">Semua tabel</option>
                    @foreach ($listTabel as $t)
                        <option value="{{ $t }}" @selected($nama_tabel == $t)>
                            {{ $t }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <input type="date" name="tanggal_dari" value="{{ $tanggal_dari }}"
                    class="w-full border rounded-lg border-gray-300 px-3 py-2 text-sm">
            </div>

            <div class="md:col-span-2">
                <input type="date" name="tanggal_sampai" value="{{ $tanggal_sampai }}"
                    class="w-full border rounded-lg border-gray-300 px-3 py-2 text-sm">
            </div>

            <div class="md:col-span-1">
                <button class="w-full btn-active text-white rounded-lg border-gray-300 py-2 text-sm hover:bg-gray-900">
                    Go
                </button>
            </div>
        </form>
    </div>

    {{-- Data --}}
    <div class="bg-white rounded-lg border-gray-300 shadow-sm border overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-300 font-semibold text-gray-700">
            Daftar Aktivitas
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Waktu</th>
                        <th class="px-4 py-3 text-left">Pengguna</th>
                        <th class="px-4 py-3 text-left">Tabel</th>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                        <th class="px-4 py-3 text-left">Referensi</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($data as $row)
                        <tr class="hover:bg-gray-50 border-gray-300">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-800">
                                    {{ optional($row->dibuat_pada)->translatedFormat('d F Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ optional($row->dibuat_pada)->format('H:i:s') }}
                                </div>
                            </td>

                            {{-- User --}}
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-800">
                                    {{ $row->pengguna->username ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    ID: {{ $row->pengguna_id }}
                                </div>
                            </td>

                            <td class="px-4 py-3">{{ $row->nama_tabel }}</td>
                            <td class="px-4 py-3">#{{ $row->id_rekaman }}</td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3">
                                @php
                                    $color = 'bg-gray-200 text-gray-700';
                                    if (in_array($row->aksi, ['tambah', 'create'])) {
                                        $color = 'bg-green-100 text-green-700';
                                    }
                                    if (in_array($row->aksi, ['ubah', 'update'])) {
                                        $color = 'bg-blue-100 text-blue-700';
                                    }
                                    if (in_array($row->aksi, ['hapus', 'delete'])) {
                                        $color = 'bg-red-100 text-red-700';
                                    }
                                    if (in_array($row->aksi, ['setujui', 'posting'])) {
                                        $color = 'bg-green-100 text-green-700';
                                    }
                                    if (in_array($row->aksi, ['tolak', 'batal'])) {
                                        $color = 'bg-red-100 text-red-700';
                                    }
                                    if (in_array($row->aksi, ['login', 'logout'])) {
                                        $color = 'bg-gray-300 text-gray-800';
                                    }
                                @endphp

                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                    {{ ucfirst($row->aksi) }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $row->tipe_referensi }}</div>
                                <div class="text-xs text-gray-500">#{{ $row->id_referensi }}</div>
                            </td>

                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('log-audit.show', $row->id) }}"
                                    class="text-blue-600 hover:underline text-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                Tidak ada data log.
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
