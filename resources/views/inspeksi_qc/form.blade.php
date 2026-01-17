@extends('layouts.app')

@section('content')
    @php
        $isEdit = $mode === 'edit';

        $canManage = $isEdit
            ? auth()->user()->punyaIzin('qc.kelola')
            : true;

        $bolehPosting = in_array($penerimaan->status, ['qc_lulus', 'qc_sebagian'], true);
    @endphp

    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $isEdit ? 'Ubah QC' : 'Buat QC' }}</h1>
            <p class="text-sm text-gray-600 mt-1">QC berdasarkan item penerimaan. Item yang gagal tidak akan diposting.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('inspeksi-qc.index') }}"
                class="px-4 py-2 rounded-lg bg-white border hover:bg-gray-50">Kembali</a>
            <a href="{{ route('penerimaan.edit', $penerimaan) }}"
                class="px-4 py-2 rounded-lg bg-white border hover:bg-gray-50">Buka Penerimaan</a>
        </div>
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

    <form id="qcMulai" method="post" action="{{ $isEdit ? route('inspeksi-qc.update', $row) : route('inspeksi-qc.store') }}">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <input type="hidden" name="penerimaan_id" value="{{ old('penerimaan_id', $row->penerimaan_id) }}">

        <div class="bg-white border rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="font-semibold text-gray-900">Header QC</div>

                @if ($isEdit)
                    @php
                        $badge = 'bg-gray-100 text-gray-700';

                        if ($row->status === 'menunggu') {
                            $badge = 'bg-yellow-100 text-yellow-700';
                        } elseif ($row->status === 'lulus') {
                            $badge = 'bg-green-100 text-green-700';
                        } elseif ($row->status === 'sebagian') {
                            $badge = 'bg-indigo-100 text-indigo-700';
                        } elseif ($row->status === 'gagal') {
                            $badge = 'bg-red-100 text-red-700';
                        }
                    @endphp

                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium {{ $badge }}">
                        Status: {{ $row->status }}
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-4">
                    <label class="text-sm text-gray-700 block mb-1">Nomor QC</label>
                    <input name="nomor_qc" value="{{ old('nomor_qc', $row->nomor_qc) }}"
                        class="w-full border rounded-lg px-3 py-2" {{ $canManage ? '' : 'readonly' }}>
                </div>

                <div class="md:col-span-4">
                    <label class="text-sm text-gray-700 block mb-1">Tanggal QC</label>
                    <input type="date" name="tanggal_qc" value="{{ old('tanggal_qc', $row->tanggal_qc) }}"
                        class="w-full border rounded-lg px-3 py-2" {{ $canManage ? '' : 'readonly' }}>
                </div>

                <div class="md:col-span-4">
                    <label class="text-sm text-gray-700 block mb-1">Status QC</label>
                    <select name="status" class="w-full border rounded-lg px-3 py-2" {{ $canManage ? '' : 'disabled' }}>
                        @foreach (['menunggu', 'lulus', 'gagal', 'sebagian'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $row->status) === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                    @if (!$canManage)
                        <input type="hidden" name="status" value="{{ old('status', $row->status) }}">
                    @endif
                </div>

                <div class="md:col-span-12">
                    <label class="text-sm text-gray-700 block mb-1">Ringkasan</label>
                    <textarea name="ringkasan" rows="2" class="w-full border rounded-lg px-3 py-2"
                        {{ $canManage ? '' : 'readonly' }}>{{ old('ringkasan', $row->ringkasan) }}</textarea>
                </div>
            </div>
        </div>

        @php
            $oldPd = old('penerimaan_detail_id');
            $oldHasil = old('hasil');
            $oldTerima = old('qty_diterima');
            $oldTolak = old('qty_ditolak');
            $oldCat = old('catatan_cacat');
        @endphp

        <div class="bg-white border rounded-xl p-5 mt-5">
            <div class="font-semibold text-gray-900 mb-4">Detail QC</div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="border-b">
                            <th class="text-left px-3 py-3">Barang</th>
                            <th class="text-left px-3 py-3 w-[160px]">Qty Diterima (GR)</th>
                            <th class="text-left px-3 py-3 w-[180px]">Hasil</th>
                            <th class="text-left px-3 py-3 w-[160px]">Qty Lulus</th>
                            <th class="text-left px-3 py-3 w-[160px]">Qty Ditolak</th>
                            <th class="text-left px-3 py-3">Catatan Cacat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (($detail ?? collect()) as $i => $d)
                            @php
                                $existing = $qcDetail instanceof \Illuminate\Support\Collection ? $qcDetail->get($d->id) : null;

                                $hasilVal = $oldPd ? ($oldHasil[$i] ?? 'menunggu') : ($existing?->hasil ?? 'menunggu');
                                $qtyLulusVal = $oldPd ? ($oldTerima[$i] ?? $d->qty_diterima) : ($existing?->qty_diterima ?? $d->qty_diterima);
                                $qtyTolakVal = $oldPd ? ($oldTolak[$i] ?? 0) : ($existing?->qty_ditolak ?? 0);
                                $catVal = $oldPd ? ($oldCat[$i] ?? '') : ($existing?->catatan_cacat ?? '');
                            @endphp

                            <tr class="border-b">
                                <td class="px-3 py-2">
                                    <div class="font-medium text-gray-900">{{ $d->barang?->nama ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $d->barang?->sku ?? '' }}</div>
                                    <input type="hidden" name="penerimaan_detail_id[]" value="{{ $d->id }}">
                                </td>
                                <td class="px-3 py-2">
                                    <div class="font-medium text-gray-900">{{ $d->qty_diterima }}</div>
                                    <div class="text-xs text-gray-500">{{ $d->no_lot ? 'Lot: ' . $d->no_lot : '' }}</div>
                                </td>
                                <td class="px-3 py-2">
                                    <select name="hasil[]" class="w-full border rounded-lg px-2 py-2" {{ $canManage ? '' : 'disabled' }}>
                                        @foreach (['menunggu', 'lulus', 'gagal'] as $h)
                                            <option value="{{ $h }}" @selected($hasilVal === $h)>{{ $h }}</option>
                                        @endforeach
                                    </select>
                                    @if (!$canManage)
                                        <input type="hidden" name="hasil[]" value="{{ $hasilVal }}">
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <input name="qty_diterima[]" value="{{ $qtyLulusVal }}"
                                        class="w-full border rounded-lg px-2 py-2" {{ $canManage ? '' : 'readonly' }}>
                                </td>
                                <td class="px-3 py-2">
                                    <input name="qty_ditolak[]" value="{{ $qtyTolakVal }}"
                                        class="w-full border rounded-lg px-2 py-2" {{ $canManage ? '' : 'readonly' }}>
                                </td>
                                <td class="px-3 py-2">
                                    <input name="catatan_cacat[]" value="{{ $catVal }}"
                                        class="w-full border rounded-lg px-2 py-2" {{ $canManage ? '' : 'readonly' }}>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-xs text-gray-500 mt-3">
                Saran: jika hasil <span class="font-medium">gagal</span>, isi qty ditolak. Item gagal tidak akan diposting ke saldo stok.
            </div>
        </div>
    </form>

    <div class="mt-5 flex flex-wrap items-center gap-2">
        @if ($canManage)
            <button type="submit" form="qcMulai"
                class="px-5 py-2.5 rounded-lg bg-white border hover:bg-gray-50">
                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan QC' }}
            </button>
        @endif

        @if (auth()->user()->punyaIzin('penerimaan.kelola'))
            <form method="post" action="{{ route('penerimaan.postingStokMasuk', $penerimaan) }}">
                @csrf
                <button class="px-5 py-2.5 rounded-lg bg-white border hover:bg-gray-50">Posting Stok Masuk</button>
            </form>
        @endif
    </div>
@endsection
