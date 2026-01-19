@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold">Detail Pergerakan</h1>
            <div class="text-sm text-gray-600">{{ $pergerakan_stok->nomor_pergerakan }}</div>
        </div>
        <a class="px-4 py-2 rounded-lg border btn-active" href="{{ route('pergerakan-stok.index') }}">Kembali</a>
    </div>

    @if (session('ok'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-800">
            {{ session('ok') }}
        </div>
    @endif

    <div class="bg-white border rounded-lg border-gray-300 p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
            <div>
                <div class="text-gray-500">Tanggal</div>
                <div class="font-medium">{{ $pergerakan_stok->tanggal_pergerakan?->format('Y-m-d H:i') }}</div>
            </div>
            <div>
                <div class="text-gray-500">Jenis</div>
                <div class="font-medium">{{ $pergerakan_stok->jenis_pergerakan }}</div>
            </div>
            <div>
                <div class="text-gray-500">Status</div>
                <div class="font-medium">{{ $pergerakan_stok->status }}</div>
            </div>
            <div>
                <div class="text-gray-500">Gudang</div>
                <div class="font-medium">{{ $pergerakan_stok->gudang->nama ?? '-' }}</div>
            </div>
            <div>
                <div class="text-gray-500">Referensi</div>
                <div class="font-medium">
                    @if ($pergerakan_stok->tipe_referensi && $pergerakan_stok->id_referensi)
                        {{ $pergerakan_stok->tipe_referensi }} #{{ $pergerakan_stok->id_referensi }}
                    @else
                        -
                    @endif
                </div>
            </div>
            <div>
                <div class="text-gray-500">Catatan</div>
                <div class="font-medium">{{ $pergerakan_stok->catatan ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white border rounded-lg border-gray-300   overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-3 py-2">Barang</th>
                    <th class="text-left px-3 py-2">Dari</th>
                    <th class="text-left px-3 py-2">Ke</th>
                    <th class="text-left px-3 py-2">Lot</th>
                    <th class="text-left px-3 py-2">Kedaluwarsa</th>
                    <th class="text-right px-3 py-2">Qty</th>
                    <th class="text-right px-3 py-2">Biaya</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pergerakan_stok->detail as $d)
                    <tr class="border-t border-gray-300">
                        <td class="px-3 py-2">
                            {{ $d->barang->nama ?? '-' }}
                            <div class="text-xs text-gray-500">{{ $d->barang->sku ?? '' }}</div>
                        </td>
                        <td class="px-3 py-2">
                            @if ($d->dariGudang)
                                {{ $d->dariGudang->nama }}
                                @if ($d->dariLokasi)
                                    <div class="text-xs text-gray-500">
                                        {{ $d->dariLokasi->kode }}{{ $d->dariLokasi->nama ? ' - ' . $d->dariLokasi->nama : '' }}
                                    </div>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-3 py-2">
                            @if ($d->keGudang)
                                {{ $d->keGudang->nama }}
                                @if ($d->keLokasi)
                                    <div class="text-xs text-gray-500">
                                        {{ $d->keLokasi->kode }}{{ $d->keLokasi->nama ? ' - ' . $d->keLokasi->nama : '' }}
                                    </div>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-3 py-2">{{ $d->no_lot ?? '-' }}</td>
                        <td class="px-3 py-2">
                            {{ $d->tanggal_kedaluwarsa ? $d->tanggal_kedaluwarsa->format('Y-m-d') : '-' }}</td>
                        <td class="px-3 py-2 text-right">
                            {{ rtrim(rtrim(number_format((float) $d->qty, 4, '.', ''), '0'), '.') }}</td>
                        <td class="px-3 py-2 text-right">
                            {{ rtrim(rtrim(number_format((float) $d->biaya_satuan, 4, '.', ''), '0'), '.') }}</td>
                    </tr>
                @empty
                    <tr class="border-t">
                        <td colspan="7" class="px-3 py-6 text-center text-gray-500">Detail kosong.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
