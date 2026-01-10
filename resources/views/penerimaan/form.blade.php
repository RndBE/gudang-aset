@extends('layouts.app')

@section('content')
    @php
        $isEdit = $mode === 'edit';
        $canManage = auth()->user()->punyaIzin('penerimaan.kelola');

        $instansiId = auth()->user()->instansi_id;
        $barangList = \App\Models\Barang::where('instansi_id', $instansiId)
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();
        $lokasiList = \App\Models\LokasiGudang::query()
            ->whereHas('gudang', function ($q) use ($instansiId) {
                $q->where('instansi_id', $instansiId);
            })
            ->orderByRaw('COALESCE(nama, kode) asc')
            ->get();
    @endphp

    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $isEdit ? 'Ubah Penerimaan' : 'Buat Penerimaan' }}</h1>
            <p class="text-sm text-gray-600 mt-1">Catat barang diterima. Setelah QC selesai, lakukan posting stok masuk.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('penerimaan.index') }}"
                class="px-4 py-2 rounded-lg bg-white border hover:bg-gray-50">Kembali</a>
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

    <form id="penerimaanForm" method="post"
        action="{{ $isEdit ? route('penerimaan.update', $row) : route('penerimaan.store') }}">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="bg-white border rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="font-semibold text-gray-900">Header Penerimaan</div>
                @if ($isEdit)
                    @php
                        $badge = 'bg-gray-100 text-gray-700';
                        if ($row->status === 'diterima') {
                            $badge = 'bg-blue-100 text-blue-700';
                        }
                        if ($row->status === 'qc_menunggu') {
                            $badge = 'bg-yellow-100 text-yellow-700';
                        }
                        if ($row->status === 'qc_selesai') {
                            $badge = 'bg-indigo-100 text-indigo-700';
                        }
                        if ($row->status === 'diposting') {
                            $badge = 'bg-green-100 text-green-700';
                        }
                        if ($row->status === 'dibatalkan') {
                            $badge = 'bg-red-100 text-red-700';
                        }
                    @endphp
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium {{ $badge }}">
                        Status: {{ $row->status }}
                    </span>
                @endif
            </div>

            @if ($po)
                <div class="mb-4 p-4 rounded-xl border bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-4">
                            <div class="text-xs text-gray-500">PO</div>
                            <div class="font-medium text-gray-900">{{ $po->nomor_po }}</div>
                        </div>
                        <div class="md:col-span-4">
                            <div class="text-xs text-gray-500">Pemasok</div>
                            <div class="font-medium text-gray-900">{{ $po->pemasok?->nama ?? '-' }}</div>
                        </div>
                        <div class="md:col-span-4">
                            <div class="text-xs text-gray-500">Status PO</div>
                            <div class="font-medium text-gray-900">{{ $po->status }}</div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="pesanan_pembelian_id" value="{{ $po->id }}">
            @else
                @if (old('pesanan_pembelian_id', $row->pesanan_pembelian_id))
                    <input type="hidden" name="pesanan_pembelian_id"
                        value="{{ old('pesanan_pembelian_id', $row->pesanan_pembelian_id) }}">
                @endif
            @endif

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-4">
                    <label class="text-sm text-gray-700 block mb-1">Gudang</label>
                    <select name="gudang_id" class="w-full border rounded-lg px-3 py-2" {{ $canManage ? '' : 'disabled' }}>
                        @foreach ($gudang as $g)
                            <option value="{{ $g->id }}" @selected(old('gudang_id', $row->gudang_id) == $g->id)>{{ $g->kode }} -
                                {{ $g->nama }}</option>
                        @endforeach
                    </select>
                    @if (!$canManage)
                        <input type="hidden" name="gudang_id" value="{{ old('gudang_id', $row->gudang_id) }}">
                    @endif
                </div>

                <div class="md:col-span-4">
                    <label class="text-sm text-gray-700 block mb-1">Nomor Penerimaan</label>
                    <input name="nomor_penerimaan" value="{{ old('nomor_penerimaan', $row->nomor_penerimaan) }}"
                        class="w-full border rounded-lg px-3 py-2" {{ $canManage ? '' : 'readonly' }}>
                </div>

                <div class="md:col-span-4">
                    <label class="text-sm text-gray-700 block mb-1">Tanggal Penerimaan</label>
                    <input type="date" name="tanggal_penerimaan"
                        value="{{ old('tanggal_penerimaan', $row->tanggal_penerimaan) }}"
                        class="w-full border rounded-lg px-3 py-2" {{ $canManage ? '' : 'readonly' }}>
                </div>

                <div class="md:col-span-12">
                    <label class="text-sm text-gray-700 block mb-1">Catatan</label>
                    <textarea name="catatan" rows="2" class="w-full border rounded-lg px-3 py-2" {{ $canManage ? '' : 'readonly' }}>{{ old('catatan', $row->catatan) }}</textarea>
                </div>
            </div>
        </div>

        @php
            $rows = old('barang_id')
                ? collect(old('barang_id'))
                    ->values()
                    ->map(function ($v, $i) {
                        return [
                            'barang_id' => $v,
                            'po_detail_id' => old('po_detail_id')[$i] ?? null,
                            'qty_diterima' => old('qty_diterima')[$i] ?? 0,
                            'no_lot' => old('no_lot')[$i] ?? null,
                            'tanggal_kedaluwarsa' => old('tanggal_kedaluwarsa')[$i] ?? null,
                            'biaya_satuan' => old('biaya_satuan')[$i] ?? 0,
                            'lokasi_id' => old('lokasi_id')[$i] ?? null,
                            'catatan_detail' => old('catatan_detail')[$i] ?? null,
                        ];
                    })
                : $detail->map(function ($d) {
                    return [
                        'barang_id' => $d->barang_id,
                        'po_detail_id' => $d->po_detail_id,
                        'qty_diterima' => $d->qty_diterima,
                        'no_lot' => $d->no_lot,
                        'tanggal_kedaluwarsa' => optional($d->tanggal_kedaluwarsa)->format('Y-m-d'),
                        'biaya_satuan' => $d->biaya_satuan,
                        'lokasi_id' => $d->lokasi_id,
                        'catatan_detail' => $d->catatan,
                    ];
                });

            if ($rows->count() === 0) {
                if ($poDetail && $poDetail->count() > 0) {
                    $rows = $poDetail->map(function ($pd) {
                        return [
                            'barang_id' => $pd->barang_id,
                            'po_detail_id' => $pd->id,
                            'qty_diterima' => $pd->qty,
                            'no_lot' => null,
                            'tanggal_kedaluwarsa' => null,
                            'biaya_satuan' => $pd->harga_satuan,
                            'lokasi_id' => null,
                            'catatan_detail' => null,
                        ];
                    });
                } else {
                    $rows = collect([
                        [
                            'barang_id' => $barangList->first()?->id,
                            'po_detail_id' => null,
                            'qty_diterima' => 1,
                            'no_lot' => null,
                            'tanggal_kedaluwarsa' => null,
                            'biaya_satuan' => 0,
                            'lokasi_id' => null,
                            'catatan_detail' => null,
                        ],
                    ]);
                }
            }
        @endphp

        <div class="bg-white border rounded-xl p-5 mt-5">
            <div class="flex items-center justify-between mb-4">
                <div class="font-semibold text-gray-900">Detail Penerimaan</div>
                <div class="flex items-center gap-2">
                    @if ($canManage && $row->status !== 'diposting')
                        <button type="button" id="btnAddRow"
                            class="px-4 py-2 rounded-lg bg-white border hover:bg-gray-50">Tambah Baris</button>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm" id="penerimaanTable">
                    <thead class="bg-gray-50">
                        <tr class="border-b">
                            <th class="text-left px-3 py-3 w-[320px]">Barang</th>
                            <th class="text-left px-3 py-3 w-[160px]">Qty Diterima</th>
                            <th class="text-left px-3 py-3 w-[160px]">Biaya Satuan</th>
                            <th class="text-left px-3 py-3 w-[160px]">Lot</th>
                            <th class="text-left px-3 py-3 w-[180px]">Kedaluwarsa</th>
                            <th class="text-left px-3 py-3 w-[240px]">Lokasi</th>
                            <th class="text-left px-3 py-3">Catatan</th>
                            <th class="text-right px-3 py-3 w-[90px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="penerimaanTbody">
                        @foreach ($rows as $d)
                            <tr class="border-b penerimaan-row">
                                <td class="px-3 py-2">
                                    <select name="barang_id[]" class="w-full border rounded-lg px-2 py-2"
                                        {{ $canManage ? '' : 'disabled' }}>
                                        @foreach ($barangList as $b)
                                            <option value="{{ $b->id }}" @selected((int) $d['barang_id'] === (int) $b->id)>
                                                {{ $b->sku }} - {{ $b->nama }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="po_detail_id[]" value="{{ $d['po_detail_id'] }}">
                                </td>
                                <td class="px-3 py-2">
                                    <input name="qty_diterima[]" value="{{ $d['qty_diterima'] }}"
                                        class="w-full border rounded-lg px-2 py-2" {{ $canManage ? '' : 'readonly' }}>
                                </td>
                                <td class="px-3 py-2">
                                    <input name="biaya_satuan[]" value="{{ $d['biaya_satuan'] }}"
                                        class="w-full border rounded-lg px-2 py-2" {{ $canManage ? '' : 'readonly' }}>
                                </td>
                                <td class="px-3 py-2">
                                    <input name="no_lot[]" value="{{ $d['no_lot'] }}"
                                        class="w-full border rounded-lg px-2 py-2" {{ $canManage ? '' : 'readonly' }}>
                                </td>
                                <td class="px-3 py-2">
                                    <input type="date" name="tanggal_kedaluwarsa[]"
                                        value="{{ $d['tanggal_kedaluwarsa'] }}"
                                        class="w-full border rounded-lg px-2 py-2" {{ $canManage ? '' : 'readonly' }}>
                                </td>
                                <td class="px-3 py-2">
                                    <select name="lokasi_id[]" class="w-full border rounded-lg px-2 py-2"
                                        {{ $canManage ? '' : 'disabled' }}>
                                        <option value="">-</option>
                                        @foreach ($lokasiList as $l)
                                            <option value="{{ $l->id }}" @selected((string) $d['lokasi_id'] === (string) $l->id)>
                                                {{ $l->nama }}</option>
                                        @endforeach
                                    </select>
                                    @if (!$canManage)
                                        <input type="hidden" name="lokasi_id[]" value="{{ $d['lokasi_id'] }}">
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <input name="catatan_detail[]" value="{{ $d['catatan_detail'] }}"
                                        class="w-full border rounded-lg px-2 py-2" {{ $canManage ? '' : 'readonly' }}>
                                </td>
                                <td class="px-3 py-2 text-right">
                                    @if ($canManage && $row->status !== 'diposting')
                                        <button type="button"
                                            class="btnRemove px-3 py-2 rounded-lg bg-white border hover:bg-gray-50">Hapus</button>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5 flex flex-wrap gap-2">
            @if ($canManage && $row->status !== 'diposting')
                <button class="px-5 py-2.5 rounded-lg bg-white border hover:bg-gray-50">Simpan</button>
            @endif

            @if ($isEdit && $canManage)
                @if (!in_array($row->status, ['diposting', 'dibatalkan'], true))
                    <form method="post" action="{{ route('penerimaan.qcMulai', $row) }}">
                        @csrf
                        <button class="px-5 py-2.5 rounded-lg bg-white border hover:bg-gray-50">Mulai QC</button>
                    </form>
                @endif

                @if (!in_array($row->status, ['diposting', 'dibatalkan'], true))
                    <a href="{{ route('inspeksi-qc.create', ['penerimaan_id' => $row->id]) }}"
                        class="px-5 py-2.5 rounded-lg bg-white border hover:bg-gray-50">
                        Buat / Ubah QC
                    </a>
                @endif

                @if (auth()->user()->punyaIzin('stok.posting') || auth()->user()->punyaIzin('pergerakan_stok.kelola'))
                    <form method="post" action="{{ route('penerimaan.postingStokMasuk', $row) }}">
                        @csrf
                        <button class="px-5 py-2.5 rounded-lg bg-white border hover:bg-gray-50">Posting Stok Masuk</button>
                    </form>
                @endif
            @endif
        </div>
    </form>

    <script>
        (function() {
            const canManage = @json($canManage);
            const tbody = document.getElementById('penerimaanTbody');
            const btnAdd = document.getElementById('btnAddRow');

            function addRow() {
                const tpl = tbody.querySelector('tr.penerimaan-row');
                if (!tpl) return;

                const clone = tpl.cloneNode(true);

                clone.querySelectorAll('input').forEach(i => {
                    if (i.name === 'qty_diterima[]') i.value = 1;
                    else if (i.name === 'biaya_satuan[]') i.value = 0;
                    else if (i.name === 'no_lot[]') i.value = '';
                    else if (i.name === 'tanggal_kedaluwarsa[]') i.value = '';
                    else if (i.name === 'catatan_detail[]') i.value = '';
                    else if (i.name === 'po_detail_id[]') i.value = '';
                });

                const selectBarang = clone.querySelector('select[name="barang_id[]"]');
                if (selectBarang) selectBarang.selectedIndex = 0;

                const selectLokasi = clone.querySelector('select[name="lokasi_id[]"]');
                if (selectLokasi) selectLokasi.selectedIndex = 0;

                tbody.appendChild(clone);
            }

            tbody.addEventListener('click', function(e) {
                if (!canManage) return;
                if (e.target.classList.contains('btnRemove')) {
                    const rows = tbody.querySelectorAll('tr.penerimaan-row');
                    if (rows.length <= 1) return;
                    e.target.closest('tr')?.remove();
                }
            });

            if (btnAdd) {
                btnAdd.addEventListener('click', function() {
                    if (!canManage) return;
                    addRow();
                });
            }
        })();
    </script>
@endsection
