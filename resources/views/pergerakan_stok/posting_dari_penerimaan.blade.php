@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold">Posting Stok Masuk</h1>
            <div class="text-sm text-gray-600">Dari Penerimaan: {{ $penerimaan->nomor_penerimaan }}</div>
        </div>
        <a class="px-4 py-2 rounded border" href="{{ route('penerimaan.edit', $penerimaan->id) }}">Kembali</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-800">
            <div class="font-semibold mb-1">Terjadi kesalahan</div>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border rounded p-4 mb-4 text-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <div class="text-gray-500">Gudang</div>
                <div class="font-medium">{{ $penerimaan->gudang->nama ?? '-' }}</div>
            </div>
            <div>
                <div class="text-gray-500">Tanggal Penerimaan</div>
                <div class="font-medium">{{ $penerimaan->tanggal_penerimaan?->format('Y-m-d') }}</div>
            </div>
            <div>
                <div class="text-gray-500">Status</div>
                <div class="font-medium">{{ $penerimaan->status }}</div>
            </div>
        </div>
    </div>

    <form method="post" action="{{ route('penerimaan.posting.store', $penerimaan->id) }}" class="bg-white border rounded">
        @csrf

        <div class="p-4 border-b">
            <label class="text-sm text-gray-700">Tanggal Pergerakan</label>
            <input type="datetime-local" name="tanggal_pergerakan"
                value="{{ old('tanggal_pergerakan', now()->format('Y-m-d\TH:i')) }}"
                class="mt-1 w-full md:w-72 border rounded px-3 py-2" required />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-3 py-2">Barang</th>
                        <th class="text-left px-3 py-2">Qty Diterima</th>
                        <th class="text-left px-3 py-2">Lot</th>
                        <th class="text-left px-3 py-2">Kedaluwarsa</th>
                        <th class="text-left px-3 py-2">Lokasi Masuk</th>
                        <th class="text-right px-3 py-2">Biaya Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penerimaan->details as $d)
                        <tr class="border-t">
                            <td class="px-3 py-2">
                                {{ $d->barang->nama ?? '-' }}
                                <div class="text-xs text-gray-500">{{ $d->barang->sku ?? '' }}</div>
                            </td>
                            <td class="px-3 py-2">
                                {{ rtrim(rtrim(number_format((float) $d->qty_diterima, 4, '.', ''), '0'), '.') }}</td>
                            <td class="px-3 py-2">{{ $d->no_lot ?? '-' }}</td>
                            <td class="px-3 py-2">
                                {{ $d->tanggal_kedaluwarsa ? $d->tanggal_kedaluwarsa->format('Y-m-d') : '-' }}</td>
                            <td class="px-3 py-2">
                                <select name="lokasi_id[{{ $d->id }}]" class="border rounded px-2 py-1 w-full">
                                    <option value="">(Ikuti lokasi di penerimaan)</option>
                                    @foreach ($lokasiList as $l)
                                        <option value="{{ $l->id }}" @selected((string) old('lokasi_id.' . $d->id, $d->lokasi_id) === (string) $l->id)>
                                            {{ $l->kode }}{{ $l->nama ? ' - ' . $l->nama : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-3 py-2 text-right">
                                {{ rtrim(rtrim(number_format((float) $d->biaya_satuan, 4, '.', ''), '0'), '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t flex items-center justify-end gap-2">
            <button class="px-4 py-2 rounded bg-black text-white">Posting</button>
        </div>
    </form>
@endsection
