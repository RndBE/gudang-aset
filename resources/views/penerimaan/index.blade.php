@extends('layouts.app')

@section('content')
    @php
        $canManage = auth()->user()->punyaIzin('penerimaan.kelola');
    @endphp

    <div class="flex items-center justify-between gap-4 mb-3">
        <div>
            <h1 class="text-xl font-semibold">Penerimaan</h1>
        </div>

        @if ($canManage)
            <a href="{{ route('penerimaan.create') }}"
                class="btn-active px-4 py-2 lg:px-6 lg:py-3 rounded-lg text-sm  text-center">
                Buat Penerimaan
            </a>
        @endif
    </div>

    @if (session('ok'))
        <div class="mb-4 p-4 rounded-lg border bg-white">
            <div class="font-medium text-gray-900">{{ session('ok') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 rounded-lg border bg-white">
            <div class="font-medium text-red-700">Terjadi kesalahan</div>
            <ul class="list-disc ml-5 mt-2 text-sm text-gray-700">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="bg-white border rounded-lg border-gray-300 p-4 mb-4" method="get"
        action="{{ route('penerimaan.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-3">
                <label class="text-sm text-gray-700 block mb-1">Status</label>
                <select name="status" class="w-full border rounded-lg border-gray-300 px-3 py-2 text-sm">
                    <option value="">Semua</option>
                    @foreach (['draft', 'diterima', 'qc_menunggu', 'qc_selesai', 'diposting', 'dibatalkan'] as $st)
                        <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-7">
                <label class="text-sm text-gray-700 block mb-1">Kata kunci</label>
                <input name="keyword" value="{{ request('keyword') }}"
                    class="w-full border border-gray-300  rounded-lg px-3 py-2 text-sm"
                    placeholder="Nomor penerimaan / catatan">
            </div>

            <div class="md:col-span-2 flex items-end gap-2">
                <button
                    class="w-full px-4 py-2 rounded-lg bg-white border border-gray-300 hover:bg-gray-50  text-sm">Filter</button>
                <a href="{{ route('penerimaan.index') }}"
                    class="w-full px-4 py-2 rounded-lg bg-white border  border-gray-300 hover:bg-gray-50 text-center  text-sm">Reset</a>
            </div>
        </div>
    </form>

    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="border-b border-gray-300">
                        <th class="text-left px-4 py-3">Nomor</th>
                        <th class="text-left px-4 py-3">Tanggal</th>
                        <th class="text-left px-4 py-3">Gudang</th>
                        <th class="text-left px-4 py-3">PO</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-right px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $r)
                        @php
                            $badge = 'bg-gray-100 text-gray-700';
                            if ($r->status === 'diterima') {
                                $badge = 'bg-blue-100 text-blue-700';
                            }
                            if ($r->status === 'qc_menunggu') {
                                $badge = 'bg-yellow-100 text-yellow-700';
                            }
                            if ($r->status === 'qc_selesai') {
                                $badge = 'bg-indigo-100 text-indigo-700';
                            }
                            if ($r->status === 'diposting') {
                                $badge = 'bg-green-100 text-green-700';
                            }
                            if ($r->status === 'dibatalkan') {
                                $badge = 'bg-red-100 text-red-700';
                            }
                        @endphp
                        <tr class="border-b border-gray-300 hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ $r->nomor_penerimaan }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ optional($r->tanggal_penerimaan)->format('Y-m-d') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $r->gudang?->nama ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if ($r->pesananPembelian)
                                    <div class="text-gray-900">{{ $r->pesananPembelian->nomor_po }}</div>
                                    <div class="text-xs text-gray-500">{{ $r->pesananPembelian->status }}</div>
                                @else
                                    <span class="text-xs text-gray-500">Tanpa PO</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $badge }}">
                                    {{ $r->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                @if ($canManage)
                                    <a href="{{ route('penerimaan.edit', $r) }}"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg border bg-white hover:bg-gray-50 btn-outline-active">Buka</a>
                                @else
                                    <span class="text-xs text-gray-500">Tidak ada akses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="6">Belum ada data.</td>
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
