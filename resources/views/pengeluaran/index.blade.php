@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-4 mb-4">
        <div>
            <h1 class="text-xl font-semibold">Pengeluaran</h1>
        </div>

        @if (auth()->user() && auth()->user()->punyaIzin('pengeluaran.kelola'))
            <a href="{{ route('pengeluaran.create') }}"
                class="btn-active px-4 py-2 lg:px-6 lg:py-3 rounded-lg text-sm  text-center ">
                Buat Pengeluaran
            </a>
        @endif
    </div>

    @if (session('ok'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('ok') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <div class="font-semibold mb-1">Terjadi error:</div>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-lg border-gray-300 border bg-white overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Nomor</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Gudang</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($items as $it)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ $it->nomor_pengeluaran }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ optional($it->tanggal_pengeluaran)->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $it->gudang?->nama }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span
                                class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold
                                @if ($it->status === 'dikeluarkan') bg-green-100 text-green-700
                                @elseif($it->status === 'dibatalkan') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700 @endif
                            ">
                                {{ $it->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            <a href="{{ route('pengeluaran.edit', $it->id) }}"
                                class="inline-flex items-center rounded-lg border px-3 py-1.5 text-sm btn-outline-active hover:bg-gray-50">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-gray-500" colspan="5">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
@endsection
