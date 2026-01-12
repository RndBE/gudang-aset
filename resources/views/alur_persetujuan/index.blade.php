@extends('layouts.app')

@section('content')
    <div class="space-y-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Alur Persetujuan</h1>
            <a href="{{ route('alur-persetujuan.create') }}"
                class="px-3 py-2 rounded bg-gray-900 text-white text-sm hover:bg-gray-800">
                Tambah
            </a>
        </div>

        <!-- Alert -->
        @if (session('success'))
            <div class="p-3 rounded bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search -->
        <form class="flex gap-2">
            <input name="q" value="{{ $q }}" class="w-full md:w-1/3 border rounded px-3 py-2 text-sm"
                placeholder="Cari nama atau kode alur...">

            <button class="px-4 py-2 rounded bg-gray-800 text-white text-sm hover:bg-gray-900">
                Cari
            </button>
        </form>

        <!-- Table -->
        <div class="bg-white border rounded overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Kode</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-right .w-[200px]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3 font-medium">
                                {{ $row->nama }}
                            </td>

                            <td class="p-3 text-gray-700">
                                {{ $row->kode }}
                            </td>

                            <td class="p-3">
                                @if ($row->status)
                                    <span class="px-2 py-1 rounded text-xs bg-green-50 text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="p-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('alur-persetujuan.show', $row->id) }}"
                                        class="px-3 py-1 rounded border text-sm hover:bg-gray-100">
                                        Detail
                                    </a>

                                    <a href="{{ route('alur-persetujuan.edit', $row->id) }}"
                                        class="px-3 py-1 rounded border text-sm text-blue-600 hover:bg-blue-50">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500">
                                Belum ada data alur persetujuan.
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
