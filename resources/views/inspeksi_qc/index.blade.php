@extends('layouts.app')

@section('content')
    @php
        $canManage = auth()->user()->punyaIzin('qc.kelola');
    @endphp

    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Inspeksi QC</h1>
            <p class="text-sm text-gray-600 mt-1">Tentukan lulus/gagal per item penerimaan.</p>
        </div>

        @if ($canManage)
            <a href="{{ route('penerimaan.index') }}"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-white border hover:bg-gray-50">
                Pilih dari Penerimaan
            </a>
        @endif
    </div>

    @if (session('ok'))
        <div class="mb-4 p-4 rounded-lg border bg-white">
            <div class="font-medium text-gray-900">{{ session('ok') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 rounded-lg border bg-white">
            <div class="font-medium text-red-700">Terjadi kesalahan</div>
            <ul class="list-disc ml-5 mt-2 text-sm text-gray-700">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="border-b">
                        <th class="text-left px-4 py-3">Nomor QC</th>
                        <th class="text-left px-4 py-3">Tanggal</th>
                        <th class="text-left px-4 py-3">Penerimaan</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-right px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $r)
                        @php
                            $badge = 'bg-gray-100 text-gray-700';
                            if ($r->status === 'menunggu') {
                                $badge = 'bg-yellow-100 text-yellow-700';
                            }
                            if ($r->status === 'lulus') {
                                $badge = 'bg-green-100 text-green-700';
                            }
                            if ($r->status === 'sebagian') {
                                $badge = 'bg-indigo-100 text-indigo-700';
                            }
                            if ($r->status === 'gagal') {
                                $badge = 'bg-red-100 text-red-700';
                            }
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $r->nomor_qc }}</td>
                            <td class="px-4 py-3">{{ optional($r->tanggal_qc)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">
                                <div class="text-gray-900">{{ $r->penerimaan?->nomor_penerimaan ?? '-' }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ optional($r->penerimaan?->tanggal_penerimaan)->format('Y-m-d') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $badge }}">
                                    {{ $r->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if ($canManage)
                                    <a href="{{ route('inspeksi-qc.edit', $r) }}"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg border bg-white hover:bg-gray-50">Buka</a>
                                @else
                                    <span class="text-xs text-gray-500">Tidak ada akses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="5">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $data->links() }}
    </div>
@endsection
