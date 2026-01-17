@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-4 mb-4">
        <div>
            <h1 class="text-xl font-semibold">Pesanan Pembelian (PO)</h1>
        </div>

        @if (auth()->user()->punyaIzin('pesanan_pembelian.kelola'))
            <a href="{{ route('pesanan-pembelian.create') }}"
                class="inline-flex items-center px-6 text-sm py-3 rounded-lg btn-active hover:bg-gray-50">
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

    <form class="bg-white border border-gray-300 rounded-lg p-4 mb-4" method="get"
        action="{{ route('pesanan-pembelian.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-3">
                <label class="text-sm text-gray-700 block mb-1">Status</label>
                <select name="status" class="text-sm  w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Semua</option>
                    @foreach (['draft', 'diajukan', 'disetujui', 'diterima', 'diterima_sebagian', 'dibatalkan'] as $st)
                        <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-7">
                <label class="text-sm text-gray-700 block mb-1">Kata kunci</label>
                <input name="keyword" value="{{ request('keyword') }}"
                    class="text-sm w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Nomor PO / catatan">
            </div>

            <div class="md:col-span-2 flex items-end gap-2">
                <button
                    class="text-sm w-full px-4 py-2 rounded-lg btn-outline-active border hover:bg-gray-50">Filter</button>
                <a href="{{ route('pesanan-pembelian.index') }}"
                    class="text-sm w-full px-4 py-2 rounded-lg btn-active hover:bg-gray-50 text-center">Reset</a>
            </div>
        </div>
    </form>

    <div class="bg-white border rounded-lg border-gray-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="border-b border-gray-300">
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
                            $statusTampil = $r->status;
                            $pp = $r->permintaanPersetujuan; // relasi hasOne
                            $apprStatus = $pp?->status; // diajukan/disetujui/ditolak/null

                            // kalau ada permintaan persetujuan yg masih menunggu → override status tampilan
                            if ($apprStatus === 'menunggu') {
                                $statusTampil = 'diajukan';
                            }
                            // default
                            $color = 'bg-gray-100 text-gray-700';

                            if ($statusTampil === 'menunggu persetujuan') {
                                $color = 'bg-yellow-100 text-yellow-800';
                            } elseif ($statusTampil === 'diajukan') {
                                $color = 'bg-yellow-100 text-yellow-700';
                            } elseif ($statusTampil === 'disetujui') {
                                $color = 'bg-green-100 text-green-700';
                            } elseif ($statusTampil === 'diterima' || $statusTampil === 'diterima_sebagai') {
                                $color = 'bg-blue-100 text-blue-700';
                            } elseif ($statusTampil === 'dibatalkan') {
                                $color = 'bg-red-200 text-red-600';
                            }

                            $showLink = $pp && in_array($apprStatus, ['diajukan', 'menunggu'], true); // ✅ link hanya kalau masih menunggu

                            // $badge = 'bg-gray-100 text-gray-700';

                            // if ($r->status === 'draft') {
                            //     $badge = 'bg-gray-100 text-gray-700';
                            // }
                            // if ($r->status === 'diajukan') {
                            //     $badge = 'bg-yellow-100 text-yellow-700';
                            // }
                            // if ($r->status === 'disetujui') {
                            //     $badge = 'bg-green-100 text-green-700';
                            // }
                            // if ($r->status === 'diterima' || $r->status === 'diterima_sebagian') {
                            //     $badge = 'bg-blue-100 text-blue-700';
                            // }
                            // if ($r->status === 'dibatalkan') {
                            //     $badge = 'bg-red-100 text-red-700';
                            // }

                        @endphp
                        <tr class="border-b border-gray-300 hover:bg-gray-50">
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
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $color }}">
                                    {{-- {{ $r->status }} --}}
                                    {{ ucfirst($statusTampil) }}
                                </span>
                                @if ($showLink)
                                    <a href="{{ route('permintaan-persetujuan.show', $pp->id) }}"
                                        class="ml-2 text-xs text-blue-600 hover:underline">
                                        Lihat
                                    </a>
                                @endif
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
