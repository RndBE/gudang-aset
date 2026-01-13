@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold">Detail Permintaan Persetujuan</h1>
                <p class="text-sm text-gray-500">
                    {{ $data->judul }} • {{ $data->tipe_entitas }} #{{ $data->entitas_id }}
                </p>
            </div>
            <a href="{{ route('permintaan-persetujuan.index') }}" class="px-3 py-2 border rounded text-sm hover:bg-gray-100">
                Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Info -->
        <div class="bg-white border rounded p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <div class="text-gray-500 text-sm">Alur</div>
                <div class="font-medium">{{ $data->alur->nama ?? '-' }}</div>
            </div>

            <div>
                <div class="text-gray-500 text-sm">Status</div>
                @php
                    $color = 'bg-gray-200 text-gray-700';
                    if ($data->status == 'berjalan') {
                        $color = 'bg-blue-100 text-blue-700';
                    }
                    if ($data->status == 'disetujui') {
                        $color = 'bg-green-100 text-green-700';
                    }
                    if ($data->status == 'ditolak') {
                        $color = 'bg-red-100 text-red-700';
                    }
                @endphp
                <span class="px-2 py-1 text-xs rounded {{ $color }}">
                    {{ ucfirst($data->status) }}
                </span>
            </div>

            <div>
                <div class="text-gray-500 text-sm">Langkah Aktif</div>
                <div class="font-medium">#{{ $data->langkah_saat_ini }}</div>
            </div>

            <div>
                <div class="text-gray-500 text-sm">Entitas</div>
                <div class="font-medium">{{ $data->tipe_entitas }} #{{ $data->id_entitas }}</div>
            </div>
        </div>

        <!-- Steps -->
        <div class="bg-white border rounded">
            <div class="border-b px-4 py-3 font-semibold">
                Langkah Persetujuan
            </div>

            <div class="divide-y">
                @foreach ($data->langkah as $l)
                    <div class="px-4 py-3 flex justify-between items-center">
                        <div>
                            <div class="font-medium">
                                {{ $l->no_langkah }}. {{ $l->nama_langkah }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $l->catatan_keputusan ?? '—' }}
                            </div>
                        </div>

                        @php
                            $c = 'bg-gray-200 text-gray-700';
                            if ($l->status == 'menunggu') {
                                $c = 'bg-yellow-100 text-yellow-700';
                            }
                            if ($l->status == 'disetujui') {
                                $c = 'bg-green-100 text-green-700';
                            }
                            if ($l->status == 'ditolak') {
                                $c = 'bg-red-100 text-red-700';
                            }
                        @endphp
                        <span class="px-2 py-1 text-xs rounded {{ $c }}">
                            {{ ucfirst($l->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        @php
            $step = $data->langkah->where('no_langkah', $data->langkah_saat_ini)->first();
        @endphp

        <!-- Action -->
        @if ($step && $step->status === 'menunggu')
            <div class="bg-white border rounded">
                <div class="border-b px-4 py-3 font-semibold">
                    Aksi Persetujuan – {{ $step->nama_langkah }}
                </div>

                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Approve -->
                    <form method="POST" action="{{ route('permintaan-persetujuan.setujui', $data->id) }}">
                        @csrf
                        <label class="text-sm font-medium">Catatan (opsional)</label>
                        <textarea name="catatan_keputusan" class="w-full border rounded px-3 py-2 text-sm mt-1 mb-3" rows="3"></textarea>

                        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm"
                            onclick="return confirm('Setujui langkah ini?')">
                            Setujui
                        </button>
                    </form>

                    <!-- Reject -->
                    <form method="POST" action="{{ route('permintaan-persetujuan.tolak', $data->id) }}">
                        @csrf
                        <label class="text-sm font-medium">Catatan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="catatan_keputusan" required class="w-full border rounded px-3 py-2 text-sm mt-1 mb-3" rows="3"></textarea>

                        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm"
                            onclick="return confirm('Tolak permintaan ini?')">
                            Tolak
                        </button>
                    </form>

                </div>
            </div>
        @endif

    </div>
@endsection
