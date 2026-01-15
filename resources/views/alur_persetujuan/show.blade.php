@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Detail Alur Persetujuan</h1>
            <div class="flex gap-2">
                <a href="{{ route('alur-persetujuan.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-100">
                    Kembali
                </a>

                <a href="{{ route('alur-persetujuan.edit', $data->id) }}"
                    class="px-3 py-2 rounded border text-sm text-blue-600 hover:bg-blue-50">
                    Edit
                </a>

                <form method="POST" action="{{ route('alur-persetujuan.destroy', $data->id) }}">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Hapus alur persetujuan ini?')"
                        class="px-3 py-2 rounded border text-sm text-red-600 hover:bg-red-50">
                        Hapus
                    </button>
                </form>
            </div>
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Info Alur -->
            <div class="bg-white border rounded p-5 space-y-3">
                <div>
                    <div class="text-xs text-gray-500">Nama Alur</div>
                    <div class="font-medium">{{ $data->nama }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Kode</div>
                    <div>{{ $data->kode }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Berlaku Untuk</div>
                    <div>{{ $data->berlaku_untuk }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Status</div>
                    @if ($data->status)
                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Aktif</span>
                    @else
                        <span class="px-2 py-1 text-xs rounded bg-gray-200 text-gray-600">Nonaktif</span>
                    @endif
                </div>

                {{-- <div>
                    <div class="text-xs text-gray-500">Keterangan</div>
                    <div class="whitespace-pre-line">{{ $data->keterangan ?? '-' }}</div>
                </div> --}}

                <hr>

                <div>
                    <div class="text-xs text-gray-500">Dibuat Pada</div>
                    <div>
                        {{ optional($data->dibuat_pada)->locale('id')->translatedFormat('j F Y, H:i') ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Diubah Pada</div>
                    <div>
                        {{ optional($data->diubah_pada)->locale('id')->translatedFormat('j F Y, H:i') ?? '-' }}
                    </div>
                </div>
            </div>

            <!-- Langkah Persetujuan -->
            <div class="md:col-span-2 bg-white border rounded overflow-hidden">
                <div class="px-5 py-3 border-b flex items-center justify-between">
                    <div class="font-semibold">Langkah Persetujuan</div>
                    <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">
                        {{ $data->langkah->count() }} Langkah
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 text-left">Urutan</th>
                                <th class="px-4 py-2 text-left">Nama Langkah</th>
                                <th class="px-4 py-2 text-left">Peran</th>
                                <th class="px-4 py-2 text-left">Izin</th>
                                <th class="px-4 py-2 text-left">Aturan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data->langkah as $l)
                                <tr class="border-t">
                                    <td class="px-4 py-2 font-medium">{{ $l->no_langkah }}</td>
                                    <td class="px-4 py-2">
                                        <div class="font-medium">{{ $l->nama_langkah }}</div>
                                        <div class="text-xs text-gray-500">
                                            Batas waktu:
                                            {{ $l->batas_waktu_hari !== null ? $l->batas_waktu_hari . ' hari' : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">{{ $l->peran->nama ?? '-' }}</td>
                                    {{-- <td class="px-4 py-2">{{ $l->izin_id ?? '-' }}</td> --}}
                                    <td class="px-4 py-2">
                                        @php
                                            $izinId = data_get($l->kondisi, 'izin_id');
                                            $izinObj = $izinId ? $izin->firstWhere('id', (int) $izinId) : null;
                                        @endphp

                                        @if ($izinObj)
                                            <span
                                                class="px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $izinObj->nama }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 text-sm">-</span>
                                        @endif
                                    </td>


                                    <td class="px-4 py-2">
                                        @if ($l->harus_semua)
                                            <span class="badge bg-warning text-dark">Harus Semua</span>
                                        @else
                                            <span class="badge bg-secondary">Salah Satu Cukup</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada langkah persetujuan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($data->langkah->count() > 0)
                    <div class="px-5 py-2 text-xs text-gray-500 border-t bg-gray-50">
                        Catatan: urutan langkah harus 1,2,3… agar proses approval berjalan normal.
                    </div>
                @endif
            </div>

        </div>

    </div>
@endsection
