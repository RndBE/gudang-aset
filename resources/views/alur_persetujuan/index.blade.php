@extends('layouts.app')

@section('content')
    <div class="space-y-4">


        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Alur Persetujuan</h1>
            <a href="{{ route('alur-persetujuan.create') }}"
                class="btn-active px-4 py-2 lg:px-6 lg:py-3 rounded-lg text-sm  text-center">
                Tambah
            </a>
        </div>


        @if (session('success'))
            <div class="p-3 rounded-lg  bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif


        <form class="flex gap-2">
            <input name="q" value="{{ $q }}"
                class="w-full md:w-1/3 border rounded-lg border-gray-300 px-3 py-2 text-sm"
                placeholder="Cari nama atau kode alur...">

            <button class="px-4 py-2 rounded-lg btn-outline-active cursor-pointer text-sm">
                Cari
            </button>
        </form>


        <div class="bg-white border rounded-lg border-gray-300 overflow-hidden">
            <div class="w-full overflow-x-auto">
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
                            <tr class="border-t border-gray-300 ">
                                <td class="p-3 whitespace-nowrap font-medium">
                                    {{ $row->nama }}
                                </td>

                                <td class="p-3 whitespace-nowrap text-gray-700">
                                    {{ $row->kode }}
                                </td>

                                <td class="p-3 whitespace-nowrap">
                                    @if ($row->status === 'aktif')
                                        <span class="px-2 py-1 rounded text-xs bg-green-50 text-green-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="p-3 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('alur-persetujuan.show', $row->id) }}"
                                            class="px-3 py-1 rounded-lg border btn-outline-active text-sm hover:bg-gray-100">
                                            Detail
                                        </a>

                                        <a href="{{ route('alur-persetujuan.edit', $row->id) }}"
                                            class="px-3 py-1 rounded-lg  border text-sm border-[#78BD4E] text-[#78BD4E] hover:bg-blue-50">
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
        </div>


        <div>
            {{ $data->links() }}
        </div>

    </div>
@endsection
