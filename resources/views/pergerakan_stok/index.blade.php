@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold">Pergerakan Stok</h1>
            <div class="text-sm text-gray-600">Log transaksi stok (posting)</div>
        </div>
    </div>

    <form method="get" class="bg-white border rounded p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="text-sm text-gray-700">Cari (nomor/referensi)</label>
                <input name="q" value="{{ $q }}" class="mt-1 w-full border rounded px-3 py-2" />
            </div>
            <div>
                <label class="text-sm text-gray-700">Jenis</label>
                <select name="jenis_pergerakan" class="mt-1 w-full border rounded px-3 py-2">
                    <option value="">Semua</option>
                    @foreach (['penerimaan', 'pengeluaran', 'transfer', 'penyesuaian', 'reservasi', 'batal_reservasi', 'penyesuaian_opname'] as $j)
                        <option value="{{ $j }}" @selected($jenis === $j)>{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm text-gray-700">Status</label>
                <select name="status" class="mt-1 w-full border rounded px-3 py-2">
                    <option value="">Semua</option>
                    @foreach (['draft', 'diposting', 'dibatalkan'] as $s)
                        <option value="{{ $s }}" @selected($status === $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button class="px-4 py-2 rounded bg-black text-white">Filter</button>
                <a href="{{ route('pergerakan-stok.index') }}" class="px-4 py-2 rounded border">Reset</a>
            </div>
        </div>
    </form>

    <div class="bg-white border rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-3 py-2">Nomor</th>
                    <th class="text-left px-3 py-2">Tanggal</th>
                    <th class="text-left px-3 py-2">Jenis</th>
                    <th class="text-left px-3 py-2">Gudang</th>
                    <th class="text-left px-3 py-2">Referensi</th>
                    <th class="text-left px-3 py-2">Status</th>
                    <th class="text-right px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $it)
                    <tr class="border-t">
                        <td class="px-3 py-2 font-medium">{{ $it->nomor_pergerakan }}</td>
                        <td class="px-3 py-2">{{ $it->tanggal_pergerakan?->format('Y-m-d H:i') }}</td>
                        <td class="px-3 py-2">{{ $it->jenis_pergerakan }}</td>
                        <td class="px-3 py-2">{{ $it->gudang->nama ?? '-' }}</td>
                        <td class="px-3 py-2">
                            @if ($it->tipe_referensi && $it->id_referensi)
                                {{ $it->tipe_referensi }} #{{ $it->id_referensi }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-3 py-2">{{ $it->status }}</td>
                        <td class="px-3 py-2 text-right">
                            <a class="px-3 py-1 rounded border"
                                href="{{ route('pergerakan-stok.show', $it->id) }}">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr class="border-t">
                        <td colspan="7" class="px-3 py-6 text-center text-gray-500">Belum ada pergerakan stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
@endsection
