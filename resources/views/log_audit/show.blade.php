@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Detail Log Audit</h2>
            <a href="{{ route('log-audit.index') }}" class="px-4 py-2 text-sm border rounded-lg bg-white hover:bg-gray-50">
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

            {{-- Informasi --}}
            <div class="md:col-span-5">
                <div class="bg-white rounded-xl shadow-sm border p-4 space-y-4">

                    <div>
                        <div class="text-xs text-gray-500">Waktu</div>
                        <div class="font-semibold text-gray-800">
                            {{ optional($data->dibuat_pada)->translatedFormat('d F Y, H:i:s') ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">Pengguna</div>
                        <div class="font-medium">
                            {{ $data->pengguna->username ?? '-' }}
                            <span class="text-xs text-gray-500">(ID: {{ $data->pengguna_id }})</span>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">Aksi</div>
                        @php
                            $color = 'bg-gray-200 text-gray-700';
                            if (in_array($data->aksi, ['tambah', 'create'])) {
                                $color = 'bg-green-100 text-green-700';
                            }
                            if (in_array($data->aksi, ['ubah', 'update'])) {
                                $color = 'bg-blue-100 text-blue-700';
                            }
                            if (in_array($data->aksi, ['hapus', 'delete'])) {
                                $color = 'bg-red-100 text-red-700';
                            }
                            if (in_array($data->aksi, ['setujui', 'posting'])) {
                                $color = 'bg-green-100 text-green-700';
                            }
                            if (in_array($data->aksi, ['tolak', 'batal'])) {
                                $color = 'bg-red-100 text-red-700';
                            }
                            if (in_array($data->aksi, ['login', 'logout'])) {
                                $color = 'bg-gray-300 text-gray-800';
                            }
                        @endphp
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                            {{ ucfirst($data->aksi) }}
                        </span>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">Nama Tabel</div>
                        <div class="font-medium">{{ $data->nama_tabel }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">ID Rekaman</div>
                        <div class="font-medium">#{{ $data->id_rekaman ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">Referensi</div>
                        <div class="font-medium">
                            {{ $data->tipe_referensi ?? '-' }}
                            <span class="text-xs text-gray-500">#{{ $data->id_referensi ?? '-' }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">IP Address</div>
                        <div class="font-mono text-sm">{{ $data->ip_address ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">User Agent</div>
                        <div class="text-xs text-gray-600 break-all">
                            {{ $data->user_agent ?? '-' }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- Data Lama & Baru --}}
            <div class="md:col-span-7 space-y-4">

                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="px-4 py-2 border-b font-semibold text-gray-700">
                        Data Lama
                    </div>
                    <div class="p-4 overflow-auto text-xs font-mono bg-gray-50">
                        <pre>{{ json_encode($data->data_lama, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="px-4 py-2 border-b font-semibold text-gray-700">
                        Data Baru
                    </div>
                    <div class="p-4 overflow-auto text-xs font-mono bg-gray-50">
                        <pre>{{ json_encode($data->data_baru, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
