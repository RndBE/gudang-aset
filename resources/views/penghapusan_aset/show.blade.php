@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="mb-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold">Detail Penghapusan Aset</h1>

            <a href="{{ route('penghapusan-aset.index') }}" class="px-3 py-2 rounded-lg border text-sm hover:bg-gray-50">
                Kembali
            </a>
        </div>
    </div>

        <!-- Alert -->
        @if (session('success'))
            <div class="p-3 rounded-lg bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 rounded-lg bg-red-50 text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Card -->
        <div class="bg-white border rounded-lg border-gray-300 overflow-hidden">
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <div class="text-xs text-gray-500">instansi</div>
                    <div class="font-medium text-sm">{{ $data->instansi->nama ?? '-' }}, {{ $data->instansi->alamat ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Nomor Penghapusan</div>
                    <div class="font-medium text-sm">{{ $data->nomor_penghapusan }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Tanggal Penghapusan</div>
                    <div class="font-medium text-sm">
                        {{ $data->tanggal_penghapusan ? $data->tanggal_penghapusan->locale('id')->translatedFormat('j F Y') : '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Disetujui Oleh</div>
                    <div class="font-medium text-sm">
                        {{ $data->disetujui?->username ?? '-'}}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Dibuat Oleh</div>
                    <div class="font-medium text-sm">
                        {{ $data->dibuat?->username ?? '-'}}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Tag Aset</div>
                    <div class="font-medium text-sm">{{ $data->aset->tag_aset ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Metode</div>
                    <div class="font-medium text-sm">{{ ucfirst($data->metode) }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Status</div>
                    <div>
                        <span
                            class="px-2 py-1 rounded-lg border-gray-300 text-xs
                        {{ $data->status === 'draft' ? 'bg-gray-100 text-gray-700' : '' }}
                        {{ $data->status === 'disetujui' ? 'bg-green-50 text-green-700' : '' }}
                        {{ $data->status === 'dieksekusi' ? 'bg-red-50 text-red-700' : '' }}
                        {{ $data->status === 'dibatalkan' ? 'bg-yellow-50 text-yellow-700' : '' }}">
                            {{ ucfirst($data->status) }}
                        </span>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="text-xs text-gray-500">Alasan</div>
                    <div class="mt-1 p-3 border rounded-lg border-gray-300 bg-gray-50 text-sm">
                        {{ $data->alasan ?? '-' }}
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="border-t border-gray-300 p-4 bg-gray-50 flex gap-2 justify-end">

                @if ($data->status === 'draft')
                    <form method="POST" action="{{ route('penghapusan-aset.setujui', $data->id) }}">
                        @csrf
                        <button class="px-3 py-2 rounded-lg border-gray-300 bg-green-600 text-white text-sm hover:bg-green-700"
                            onclick="return confirm('Setujui penghapusan ini?')">
                            Setujui
                        </button>
                    </form>
                @endif

                @if ($data->status === 'disetujui')
                    <form method="POST" action="{{ route('penghapusan-aset.eksekusi', $data->id) }}">
                        @csrf
                        <button class="px-3 py-2 rounded-lg btn-active cursor-pointer bg-gray-900 text-white text-sm "
                            onclick="return confirm('Eksekusi penghapusan? Aset akan menjadi dihapus')">
                            Eksekusi
                        </button>
                    </form>
                @endif

                @if (in_array($data->status, ['draft', 'disetujui']))
                    <form method="POST" action="{{ route('penghapusan-aset.batalkan', $data->id) }}">
                        @csrf
                        <button class="px-3 py-2 rounded-lg btn-outline-active cursor-pointer border-gray-300 text-sm  text-white text-sm hover:bg-gray-50"
                            onclick="return confirm('Batalkan penghapusan ini?')">
                            Batalkan
                        </button>
                    </form>
                @endif

            </div>
        </div>

    </div>
@endsection
