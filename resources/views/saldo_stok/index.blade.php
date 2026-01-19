@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold">Saldo Stok</h1>
            <div class="text-sm text-gray-600">Ketersediaan barang per gudang/lokasi</div>
        </div>
    </div>

    @if (session('ok'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-800">
            {{ session('ok') }}
        </div>
    @endif

    <form method="get" class="bg-white border rounded-lg border-gray-300 p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="text-sm text-gray-700">Cari (nama/sku)</label>
                <input name="q" value="{{ $q }}"
                    class="mt-1 w-full border rounded-lg text-sm border-gray-300 px-3 py-2" />
            </div>
            <div>
                <label class="text-sm text-gray-700">Gudang</label>
                <select name="gudang_id" class="mt-1 w-full border rounded-lg text-sm border-gray-300 px-3 py-2">
                    <option value="">Semua</option>
                    @foreach ($gudangList as $g)
                        <option value="{{ $g->id }}" @selected((string) $gudangId === (string) $g->id)>{{ $g->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm text-gray-700">Barang</label>
                <select name="barang_id" class="mt-1 w-full border rounded-lg text-sm border-gray-300 px-3 py-2">
                    <option value="">Semua</option>
                    @foreach ($barangList as $b)
                        <option value="{{ $b->id }}" @selected((string) $barangId === (string) $b->id)>{{ $b->nama }}
                            ({{ $b->sku }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button
                    class="px-4 py-2 rounded-lg text-sm border-gray-300 btn-active text-white cursor-pointer">Filter</button>
                <a href="{{ route('saldo-stok.index') }}"
                    class="px-4 py-2 rounded-lg text-sm border-gray-300 border btn-outline-active cursor-pointer   ">Reset</a>
            </div>
        </div>
    </form>

    <div class="bg-white border rounded-lg border-gray-300 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-3 py-2">Gudang</th>
                    <th class="text-left px-3 py-2">Lokasi</th>
                    <th class="text-left px-3 py-2">Barang</th>
                    <th class="text-left px-3 py-2">Lot</th>
                    <th class="text-left px-3 py-2">Kedaluwarsa</th>
                    <th class="text-right px-3 py-2">Tersedia</th>
                    <th class="text-right px-3 py-2">Dipesan</th>
                    <th class="text-right px-3 py-2">Bisa Dipakai</th>
                    <th class="text-left px-3 py-2">Update</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $it)
                    <tr class="border-t border-gray-300">
                        <td class="px-3 py-2">{{ $it->gudang->nama ?? '-' }}</td>
                        <td class="px-3 py-2">
                            @if ($it->lokasi)
                                {{ $it->lokasi->kode }}{{ $it->lokasi->nama ? ' - ' . $it->lokasi->nama : '' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-3 py-2">
                            {{ $it->barang->nama ?? '-' }}
                            <div class="text-xs text-gray-500">{{ $it->barang->sku ?? '' }}</div>
                        </td>
                        <td class="px-3 py-2">{{ $it->no_lot ?? '-' }}</td>
                        <td class="px-3 py-2">
                            {{ $it->tanggal_kedaluwarsa ? $it->tanggal_kedaluwarsa->format('Y-m-d') : '-' }}</td>
                        <td class="px-3 py-2 text-right">
                            {{ rtrim(rtrim(number_format((float) $it->qty_tersedia, 4, '.', ''), '0'), '.') }}</td>
                        <td class="px-3 py-2 text-right">
                            {{ rtrim(rtrim(number_format((float) $it->qty_dipesan, 4, '.', ''), '0'), '.') }}</td>
                        <td class="px-3 py-2 text-right">
                            {{ rtrim(rtrim(number_format((float) $it->qty_bisa_dipakai, 4, '.', ''), '0'), '.') }}</td>
                        <td class="px-3 py-2 text-xs text-gray-600">
                            {{ $it->pergerakan_terakhir_pada ? $it->pergerakan_terakhir_pada->format('Y-m-d H:i') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr class="border-t border-gray-300">
                        <td colspan="9" class="px-3 py-6 text-center text-gray-500">Belum ada data saldo stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
@endsection
