@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between">
        <div>
            <div class="text-xl font-semibold">Permintaan</div>
        </div>

        @if (auth()->user() && auth()->user()->punyaIzin('permintaan.kelola'))
            <a href="{{ route('permintaan.create') }}"
                class="btn-active px-4 py-2 lg:px-6 lg:py-3 rounded-lg text-sm  text-center">Buat
                Permintaan</a>
        @endif
    </div>

    @if (session('ok'))
        <div class="p-3 rounded bg-green-50 text-green-800 border border-green-200">{{ session('ok') }}</div>
    @endif

    <div class="bg-white border border-gray-300 rounded-lg p-4 my-3">
        <form class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="text-sm text-gray-600">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua</option>
                    @foreach ($statusList as $k => $v)
                        <option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="text-sm text-gray-600">Cari</label>
                <input name="keyword" value="{{ request('keyword') }}"
                    class="w-full border  border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Nomor / tujuan">
            </div>
            <div class="flex items-end gap-2">
                <button class="px-3 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 text-sm">Filter</button>
                <a href="{{ route('permintaan.index') }}"
                    class="px-3 py-2 rounded-lg text-sm border border-gray-300 hover:bg-gray-50">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white border border-gray-300 rounded-lg overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="px-4 py-3">Nomor</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Pemohon</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3">Tipe</th>
                    <th class="px-4 py-3">Prioritas</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $it)
                    <tr class="border-t  border-gray-300">
                        <td class="px-4 py-3 whitespace-nowrap font-medium">{{ $it->nomor_permintaan }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ optional($it->tanggal_permintaan)->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $it->pemohon?->nama_lengkap }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $it->unitOrganisasi?->nama }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $it->tipe_permintaan }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $it->prioritas }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span
                                class="px-2 py-1 rounded-lg border-gray-300 border text-sm">{{ $statusList[$it->status] ?? $it->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if (auth()->user() && auth()->user()->punyaIzin('permintaan.kelola'))
                                <a class="px-3 py-2 rounded-lg border border-gray-300 text-sm hover:bg-gray-50"
                                    href="{{ route('permintaan.edit', $it->id) }}">Edit</a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-gray-500" colspan="8">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $items->links() }}
    </div>
@endsection
