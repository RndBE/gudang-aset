@extends('layouts.app')

@section('content')
    <div class="space-y-4" id="top">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Detail Peminjaman Aset</h1>
            <div class="flex gap-2">
                <a href="{{ route('peminjaman-aset.index') }}" class="px-3 py-2 border rounded text-sm hover:bg-gray-50">
                    Kembali
                </a>
                @if ($data->status === 'aktif' || $data->status === 'terlambat')
                    <a href="{{ route('peminjaman-aset.edit', $data->id) }}"
                        class="px-3 py-2 rounded border text-sm text-blue-600 hover:bg-blue-50">
                        Edit
                    </a>
                @endif
            </div>
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

        @php
            $isOverdue = $data->status === 'aktif' && $data->jatuh_tempo && $data->jatuh_tempo->isPast();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Informasi Peminjaman -->
            <div class="bg-white border rounded p-4 space-y-3">
                <h2 class="font-semibold text-sm">Informasi Peminjaman</h2>

                <div>
                    <div class="text-xs text-gray-500">Status</div>
                    @php
                        $badge = 'bg-gray-100 text-gray-700';
                        if ($data->status === 'aktif') {
                            $badge = 'bg-blue-100 text-blue-700';
                        }
                        if ($data->status === 'terlambat') {
                            $badge = 'bg-red-100 text-red-700';
                        }
                        if ($data->status === 'dikembalikan') {
                            $badge = 'bg-green-100 text-green-700';
                        }
                        if ($data->status === 'dibatalkan') {
                            $badge = 'bg-gray-200 text-gray-700';
                        }
                    @endphp
                    <span class="px-2 py-1 rounded text-xs {{ $badge }}">
                        {{ ucfirst($data->status) }}
                    </span>

                    @if ($isOverdue)
                        <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700 ml-1">
                            Terlambat
                        </span>
                    @endif
                </div>

                <div>
                    <div class="text-xs text-gray-500">Tanggal Mulai</div>
                    <div>{{ optional($data->tanggal_mulai)->locale('id')->translatedFormat('j F Y, H:i') ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Jatuh Tempo</div>
                    <div>{{ optional($data->jatuh_tempo)->locale('id')->translatedFormat('j F Y, H:i') ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Tanggal Kembali</div>
                    <div>{{ optional($data->tanggal_kembali)->locale('id')->translatedFormat('j F Y, H:i') ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Nomor Dokumen</div>
                    <div class="font-medium">{{ $data->nomor_dok_serah_terima ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Peminjam Pengguna </div>
                    <div>{{ $data->peminjam_pengguna->username ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Peminjam Unit </div>
                    <div>{{ $data->peminjam_unit->nama ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Tujuan</div>
                    <div class="whitespace-pre-line">{{ $data->tujuan ?? '-' }}</div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <div class="text-xs text-gray-500">Kondisi Keluar</div>
                        <span class="px-2 py-1 rounded text-xs bg-gray-100">{{ $data->kondisi_keluar ?? '-' }}</span>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Kondisi Masuk</div>
                        <span class="px-2 py-1 rounded text-xs bg-gray-100">{{ $data->kondisi_masuk ?? '-' }}</span>
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Catatan</div>
                    <div class="whitespace-pre-line">{{ $data->catatan ?? '-' }}</div>
                </div>

                @if ($data->status === 'aktif' || $data->status === 'terlambat')
                    <div class="pt-4 border-t flex gap-2">
                        <a href="#pengembalian" class="px-3 py-2 border rounded text-sm text-green-600 hover:bg-green-50">
                            Pengembalian
                        </a>

                        <form method="POST" action="{{ route('peminjaman-aset.batalkan', $data->id) }}">
                            @csrf
                            <button class="px-3 py-2 border rounded text-sm text-yellow-600 hover:bg-yellow-50"
                                onclick="return confirm('Batalkan peminjaman ini?')">
                                Batalkan
                            </button>
                        </form>
                    </div>
                @endif

            </div>

            <!-- Informasi Aset -->
            <div class="bg-white border rounded p-4 space-y-3">
                <h2 class="font-semibold text-sm">Informasi Aset</h2>

                <div class="grid grid-cols-2 gap-3 text-sm">
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
                        <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-700">
                            {{ $data->aset->status_siklus ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Pemegang (ID)</div>
                        <div>{{ $data->aset->pemegang_pengguna_id ?? '-' }}</div>
                    </div>
                </div>

                <div class="pt-4 border-t">
                    <a href="{{ route('aset.show', $data->aset_id) }}"
                        class="px-3 py-2 border rounded text-sm hover:bg-gray-50">
                        Lihat Detail Aset
                    </a>
                </div>

            </div>

        </div>

        @if ($data->status === 'aktif' || $data->status === 'terlambat')
            <div class="bg-white border rounded p-4" id="pengembalian">
                <h2 class="font-semibold text-sm mb-3">Form Pengembalian</h2>

                <form method="POST" action="{{ route('peminjaman-aset.kembalikan', $data->id) }}" class="space-y-3">
                    @csrf

                    <div>
                        <label class="block text-sm mb-1">Kondisi Masuk</label>
                        <select name="kondisi_masuk" class="w-full border rounded px-3 py-2 text-sm">
                            <option value="">-- pilih --</option>
                            @foreach (['baik', 'rusak_ringan', 'rusak_berat'] as $k)
                                <option value="{{ $k }}">{{ ucfirst(str_replace('_', ' ', $k)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700"
                        onclick="return confirm('Proses pengembalian aset ini?')">
                        Proses Pengembalian
                    </button>
                </form>
            </div>
        @endif

    </div>
@endsection
