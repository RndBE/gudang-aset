@extends('layouts.app')

@section('content')
    <div class="space-y-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Detail Penugasan Aset</h1>
            <a href="{{ route('penugasan-aset.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
                Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="p-3 rounded bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 rounded bg-red-50 text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Panel Penugasan -->
            <div class="md:col-span-1 bg-white border rounded p-4 space-y-3">

                <div>
                    <div class="text-xs text-gray-500">Status</div>
                    <span
                        class="px-2 py-1 rounded text-xs
                    {{ $data->status === 'aktif' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst($data->status) }}
                    </span>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Tanggal Tugas</div>
                    <div class="font-medium">
                        {{ $data->tanggal_tugas?->translatedFormat('d F Y, H:i') ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Tanggal Kembali</div>
                    <div class="font-medium">
                        {{ $data->tanggal_kembali?->translatedFormat('d F Y, H:i') ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Nomor Dokumen</div>
                    <div class="font-medium">{{ $data->nomor_dok_serah_terima ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Catatan</div>
                    <div class="text-sm whitespace-pre-line">{{ $data->catatan ?? '-' }}</div>
                </div>

                {{-- <div class="pt-2 border-t flex gap-2">
                    @if ($data->status === 'aktif')
                        <form method="POST" action="{{ route('penugasan-aset.kembalikan', $data->id) }}">
                            @csrf
                            <button class="px-3 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700"
                                onclick="return confirm('Kembalikan aset ini?')">
                                Kembalikan
                            </button>
                        </form>

                        <form method="POST" action="{{ route('penugasan-aset.batalkan', $data->id) }}">
                            @csrf
                            <button class="px-3 py-2 rounded bg-yellow-500 text-white text-sm hover:bg-yellow-600"
                                onclick="return confirm('Batalkan penugasan ini?')">
                                Batalkan
                            </button>
                        </form>
                    @else
                        <span class="px-3 py-2 rounded bg-gray-100 text-gray-500 text-sm">
                            Aksi tidak tersedia
                        </span>
                    @endif
                </div> --}}

            </div>

            <!-- Panel Aset -->
            <div class="md:col-span-2 bg-white border rounded p-4">

                <h2 class="text-sm font-semibold mb-3">Informasi Aset</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <div class="text-xs text-gray-500">Tag Aset</div>
                        <div class="font-medium">{{ $data->aset->tag_aset ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">No Serial</div>
                        <div>{{ $data->aset->no_serial ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">IMEI</div>
                        <div>{{ $data->aset->imei ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">No Polisi</div>
                        <div>{{ $data->aset->no_polisi ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">Status Siklus</div>
                        <span class="px-2 py-1 rounded text-xs bg-blue-50 text-blue-700">
                            {{ $data->aset->status_siklus ?? '-' }}
                        </span>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">Status Kondisi</div>
                        <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">
                            {{ $data->aset->status_kondisi ?? '-' }}
                        </span>
                    </div>

                </div>

                <div class="pt-4 border-t mt-4 flex gap-2">
                    <a href="{{ route('aset.show', $data->aset_id) }}"
                        class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
                        Lihat Detail Aset
                    </a>

                    @if ($data->status === 'aktif')
                        <a href="{{ route('penugasan-aset.edit', $data->id) }}"
                            class="px-3 py-2 rounded border text-sm text-blue-700 hover:bg-blue-50">
                            Edit Penugasan
                        </a>
                    @endif
                </div>

            </div>

        </div>
    </div>
@endsection
