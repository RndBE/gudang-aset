@extends('layouts.app')

@section('content')
    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Pesanan Pembelian (PO)</h1>
            <p class="text-sm text-gray-600 mt-1">Kelola PO dari draft sampai disetujui dan siap diterima.</p>
        </div>

        @if (auth()->user()->punyaIzin('pesanan_pembelian.kelola'))
            <a href="{{ route('pesanan-pembelian.create') }}"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-white border hover:bg-gray-50">
                Buat PO
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

    <form class="bg-white border rounded-xl p-4 mb-4" method="get" action="{{ route('pesanan-pembelian.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-3">
                <label class="text-sm text-gray-700 block mb-1">Status</label>
                <select name="status" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Semua</option>
                    @foreach (['draft', 'diajukan', 'disetujui', 'diterima', 'diterima_sebagian', 'dibatalkan'] as $st)
                        <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-7">
                <label class="text-sm text-gray-700 block mb-1">Kata kunci</label>
                <input name="keyword" value="{{ request('keyword') }}" class="w-full border rounded-lg px-3 py-2"
                    placeholder="Nomor PO / catatan">
            </div>

            <div class="md:col-span-2 flex items-end gap-2">
                <button class="w-full px-4 py-2 rounded-lg bg-white border hover:bg-gray-50">Filter</button>
                <a href="{{ route('pesanan-pembelian.index') }}"
                    class="w-full px-4 py-2 rounded-lg bg-white border hover:bg-gray-50 text-center">Reset</a>
            </div>
        </div>
    </form>

    <div class="bg-white border rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="border-b">
                        <th class="text-left px-4 py-3">Nomor PO</th>
                        <th class="text-left px-4 py-3">Tanggal</th>
                        <th class="text-left px-4 py-3">Pemasok</th>
                        <th class="text-left px-4 py-3">Total</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-right px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $r)
                        @php
                            $badge = 'bg-gray-100 text-gray-700';
                            if ($r->status === 'draft') {
                                $badge = 'bg-gray-100 text-gray-700';
                            }
                            if ($r->status === 'diajukan') {
                                $badge = 'bg-yellow-100 text-yellow-700';
                            }
                            if ($r->status === 'disetujui') {
                                $badge = 'bg-green-100 text-green-700';
                            }
                            if ($r->status === 'diterima' || $r->status === 'diterima_sebagian') {
                                $badge = 'bg-blue-100 text-blue-700';
                            }
                            if ($r->status === 'dibatalkan') {
                                $badge = 'bg-red-100 text-red-700';
                            }
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $r->nomor_po }}
                            </td>
                            <td class="px-4 py-3">
                                {{ optional($r->tanggal_po)->format('Y-m-d') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-gray-900">{{ $r->pemasok?->nama ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $r->pemasok?->kode ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ number_format((float) $r->total, 2, ',', '.') }}
                                </div>
                                <div class="text-xs text-gray-500">Subtotal
                                    {{ number_format((float) $r->subtotal, 2, ',', '.') }} · Pajak
                                    {{ number_format((float) $r->pajak, 2, ',', '.') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $badge }}">
                                    {{ $r->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if (auth()->user()->punyaIzin('pesanan_pembelian.kelola'))
                                    <a href="{{ route('pesanan-pembelian.edit', $r) }}"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg border bg-white hover:bg-gray-50">
                                        Buka
                                    </a>
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
