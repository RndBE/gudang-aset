@extends('layouts.app')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold">Detail Aset</h1>
        <a href="{{ route('aset.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
            Kembali
        </a>
    </div>

    <div class="bg-white border rounded p-4 space-y-4">
        <div class="grid grid-cols-3 md:grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-500">Nama Barang</div>
                <div class="font-medium">{{ $aset->barang?->nama ?? '-' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500">Penerima</div>
                <div class="font-medium">{{ $aset->penerimaan?->diterima_oleh ?? '-' }}</div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-500">Tag Aset</div>
                <div class="font-medium">{{ $aset->tag_aset }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Status</div>
                <div class="font-medium">
                    <span
                        class="px-2 py-1 rounded text-xs
                {{ $aset->status_siklus === 'tersedia' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ $aset->status_siklus }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-500">Nomor Serial</div>
                <div>{{ $aset->no_serial ?? '-' }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500">IMEI</div>
                <div>{{ $aset->imei ?? '-' }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-500">Nomor Polisi</div>
                <div>{{ $aset->no_polisi ?? '-' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500">Biaya</div>
                <div class="font-medium">
                    @if ($aset->biaya_perolehan)
                        {{ $aset->mata_uang === 'USD' ? '$' : 'Rp' }}
                        {{ number_format($aset->biaya_perolehan, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-500">Keterangan</div>
                <div class="font-medium">{{ $aset->status_kondisi ?? '-' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500">Tempat disimpan</div>
                <div class="font-medium">{{ $aset->gudang?->nama ?? '-' }}, {{ $aset->gudang?->alamat }}</div>
            </div>
        </div>

        {{-- <div class="flex gap-2 pt-4 border-t">
            <a href="{{ route('aset.edit', $aset->id) }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
                Edit
            </a>

            <form method="POST" action="{{ route('aset.destroy', $aset->id) }}"
                onsubmit="return confirm('Hapus aset ini?')">
                @csrf
                @method('DELETE')
                <button class="px-3 py-2 rounded bg-red-600 text-white text-sm">
                    Hapus
                </button>
            </form>
        </div> --}}

    </div>
@endsection
