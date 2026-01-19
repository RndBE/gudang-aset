@extends('layouts.app')

@section('content')
    @php
        $isEdit = $mode === 'edit';
        $canManage = auth()->user()->punyaIzin('pesanan_pembelian.kelola');
    @endphp

    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-lg font-semibold">{{ $isEdit ? 'Ubah PO' : 'Buat PO' }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('pesanan-pembelian.index') }}"
                class="px-4 py-2 rounded-lg btn-active border hover:bg-gray-50 text-sm">Kembali</a>
        </div>
    </div>

    @if (session('ok'))
        <div class="mb-4 p-4 rounded-lg border-gray-300 border bg-white">
            <div class="font-medium text-gray-900">{{ session('ok') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 rounded-lg border-gray-300 border bg-white">
            <div class="font-medium text-red-700">Terjadi kesalahan</div>
            <ul class="list-disc ml-5 mt-2 text-sm text-gray-700">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="poForm" method="post"
        action="{{ $isEdit ? route('pesanan-pembelian.update', $row) : route('pesanan-pembelian.store') }}">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="bg-white border rounded-lg border-gray-300 p-4">
            <div class="flex items-center justify-between mb-4">
                <div class="font-semibold text-gray-900">Detail PO</div>
                @if ($isEdit)
                    @php
                        $badge = 'bg-gray-100 text-gray-700';
                        if ($row->status === 'diajukan') {
                            $badge = 'bg-yellow-100 text-yellow-700';
                        }
                        if ($row->status === 'disetujui') {
                            $badge = 'bg-green-100 text-green-700';
                        }
                        if ($row->status === 'dibatalkan') {
                            $badge = 'bg-red-100 text-red-700';
                        }
                        if ($row->status === 'diterima' || $row->status === 'diterima_sebagian') {
                            $badge = 'bg-blue-100 text-blue-700';
                        }
                    @endphp
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium {{ $badge }}">
                        Status: {{ $row->status }}
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-5">
                    <label class="text-sm text-gray-700 block mb-1">Pemasok</label>
                    <select name="pemasok_id" class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2"
                        {{ $canManage ? '' : 'disabled' }}>
                        @foreach ($pemasok as $p)
                            <option value="{{ $p->id }}" @selected(old('pemasok_id', $row->pemasok_id) == $p->id)>{{ $p->kode }} -
                                {{ $p->nama }}</option>
                        @endforeach
                    </select>
                    @if (!$canManage)
                        <input type="hidden" name="pemasok_id" value="{{ old('pemasok_id', $row->pemasok_id) }}">
                    @endif
                </div>

                <div class="md:col-span-3">
                    <label class="text-sm text-gray-700 block mb-1">Nomor PO</label>
                    <input name="nomor_po" value="{{ old('nomor_po', $row->nomor_po) }}"
                        class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2"
                        {{ $canManage ? '' : 'readonly' }}>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm text-gray-700 block mb-1">Tanggal PO</label>
                    <input type="date" name="tanggal_po"
                        value="{{ old('tanggal_po', $row->tanggal_po ? \Carbon\Carbon::parse($row->tanggal_po)->format('Y-m-d') : '') }}"
                        class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2"
                        {{ $canManage ? '' : 'readonly' }}>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm text-gray-700 block mb-1">Estimasi</label>
                    <input type="date" name="tanggal_estimasi"
                        value="{{ old('tanggal_estimasi', $row->tanggal_estimasi ? \Carbon\Carbon::parse($row->tanggal_estimasi)->format('Y-m-d') : '') }}"
                        class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2"
                        {{ $canManage ? '' : 'readonly' }}>
                </div>

                <div class="md:col-span-3">
                    <label class="text-sm text-gray-700 block mb-1">Mata Uang</label>
                    <input name="mata_uang" value="{{ old('mata_uang', $row->mata_uang) }}"
                        class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2"
                        {{ $canManage ? '' : 'readonly' }}>
                </div>

                <div class="md:col-span-9">
                    <label class="text-sm text-gray-700 block mb-1">Catatan</label>
                    <textarea name="catatan" rows="2" class="w-full border rounded-lg text-sm border-gray-300 px-3 py-2"
                        {{ $canManage ? '' : 'readonly' }}>{{ old('catatan', $row->catatan) }}</textarea>
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
                            'qty' => old('qty')[$i] ?? 0,
                            'harga_satuan' => old('harga_satuan')[$i] ?? 0,
                            'tarif_pajak' => old('tarif_pajak')[$i] ?? 0,
                            'deskripsi' => old('deskripsi')[$i] ?? null,
                        ];
                    })
                : $detail->map(function ($d) {
                    return [
                        'barang_id' => $d->barang_id,
                        'qty' => $d->qty,
                        'harga_satuan' => $d->harga_satuan,
                        'tarif_pajak' => $d->tarif_pajak,
                        'deskripsi' => $d->deskripsi,
                    ];
                });

            if ($rows->count() === 0) {
                $firstBarangId = $barang->first()?->id;
                $rows = collect([
                    [
                        'barang_id' => $firstBarangId,
                        'qty' => 1,
                        'harga_satuan' => 0,
                        'tarif_pajak' => 0,
                        'deskripsi' => null,
                    ],
                ]);
            }
        @endphp

        <div class="bg-white border rounded-lg border-gray-300 p-4 mt-5">
            <div class="flex items-center justify-between mb-4">
                <div class="font-semibold text-gray-900">Detail Barang</div>
                <div class="flex items-center gap-2">
                    @if ($canManage)
                        <button type="button" id="btnAddRow"
                            class="px-4 py-2 rounded-lg border btn-outline-active  cursor-pointer   hover:bg-gray-50 text-sm">Tambah
                            Baris</button>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm" id="poTable">

                    <thead class="bg-gray-50">
                        <tr class="border-b border-gray-300">
                            <th class="text-left px-3 py-3 w-[320px]">Barang</th>
                            <th class="text-left px-3 py-3 w-[140px]">Qty</th>
                            <th class="text-left px-3 py-3 w-[160px]">Harga</th>
                            <th class="text-left px-3 py-3 w-[140px]">Pajak (%)</th>
                            <th class="text-left px-3 py-3">Deskripsi</th>
                            <th class="text-right px-3 py-3 w-[140px]">Total Harga</th>
                            <th class="text-right px-3 py-3 w-[90px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="poTbody">
                        @foreach ($rows as $i => $d)
                            <tr class="border-b po-row border-gray-300">
                                <td class="px-3 py-2">
                                    <select name="barang_id[]"
                                        class="w-full border rounded-lg border-gray-300 px-2 py-2 barang-id"
                                        {{ $canManage ? '' : 'disabled' }}>
                                        @foreach ($barang as $b)
                                            <option value="{{ $b->id }}" @selected((int) $d['barang_id'] === (int) $b->id)>
                                                {{ $b->sku }} - {{ $b->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if (!$canManage)
                                        <input type="hidden" name="barang_id[]" value="{{ $d['barang_id'] }}">
                                    @endif
                                </td>
                                <td class="px-3 py-2">

                                    <input name="qty[]"
                                        value="{{ old('qty.' . $loop->index, rtrim(rtrim(number_format((float) $d['qty'], 4, '.', ''), '0'), '.')) }}"
                                        class="w-full border rounded-lg border-gray-300 px-2 py-2 qty"
                                        {{ $canManage ? '' : 'readonly' }}>
                                </td>
                                <td class="px-3 py-2">

                                    <input name="harga_satuan[]"
                                        value="{{ old('harga_satuan.' . $loop->index, rtrim(rtrim(number_format((float) $d['harga_satuan'], 4, '.', ''), '0'), '.')) }}"
                                        class="w-full border rounded-lg border-gray-300 px-2 py-2 harga"
                                        {{ $canManage ? '' : 'readonly' }}>
                                </td>
                                <td class="px-3 py-2">

                                    <input name="tarif_pajak[]"
                                        value="{{ old('tarif_pajak.' . $loop->index, rtrim(rtrim(number_format((float) $d['tarif_pajak'], 4, '.', ''), '0'), '.')) }}"
                                        class="w-full border rounded-lg border-gray-300 px-2 py-2 pajak"
                                        {{ $canManage ? '' : 'readonly' }}>
                                </td>
                                <td class="px-3 py-2">
                                    <input name="deskripsi[]" value="{{ $d['deskripsi'] }}"
                                        class="w-full border rounded-lg border-gray-300 px-2 py-2"
                                        {{ $canManage ? '' : 'readonly' }}>
                                </td>
                                <td class="px-3 py-2 text-right font-medium total-baris">0</td>
                                <td class="ps-4 py-2 text-right">
                                    @if ($canManage)
                                        <button type="button"
                                            class="btnRemove px-3 py-2 rounded-lg border-gray-300 bg-white border hover:bg-red-200 hover:text-red-600 cursor-pointer hover:border-red-200">Hapus</button>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="pt-4">
                                <div class="flex justify-end">
                                    <div class="w-full max-w-md bg-gray-50 border rounded-lg border-gray-300 p-4">
                                        <div class="flex items-center justify-between text-sm">
                                            <div class="text-gray-600">Subtotal</div>
                                            <div class="font-medium" id="sumSubtotal">0</div>
                                        </div>
                                        <div class="flex items-center justify-between text-sm mt-2">
                                            <div class="text-gray-600">Pajak</div>
                                            <div class="font-medium" id="sumPajak">0</div>
                                        </div>
                                        <div class="border-t border-gray-300 my-3"></div>
                                        <div class="flex items-center justify-between">
                                            <div class="text-gray-900 font-semibold">Total</div>
                                            <div class="text-gray-900 font-semibold" id="sumTotal">0</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        </div>
    </form>
    <div class="px-6 flex flex-wrap gap-2">


        @if ($isEdit && $canManage)
            @if ($row->status === 'draft')
                <form method="post" action="{{ route('pesanan-pembelian.ajukan', $row) }}">
                    @csrf
                    <button class="px-5 py-2.5 rounded-lg text-sm bg-white border hover:bg-gray-50">Ajukan</button>
                </form>
            @endif



            @if (!in_array($row->status, ['diterima', 'dibatalkan'], true))
                <form method="post" action="{{ route('pesanan-pembelian.batalkan', $row) }}">
                    @csrf
                    <button
                        class="px-5 py-2.5 rounded-lg text-sm bg-white border border-[#C70000] text-[#C70000] hover:bg-red-100 cursor-pointer">Batalkan</button>
                </form>
            @endif

            @if ($row->status === 'diajukan')
                <form method="post" action="{{ route('pesanan-pembelian.setujui', $row) }}">
                    @csrf
                    <button
                        class="px-5 py-2.5 rounded-lg text-sm bg-[#8FD066] text-white hover:bg-green-600 border-[#8FD066] cursor-pointer">Setujui</button>
                </form>
            @endif
        @endif
        @if ($canManage)
            <button form="poForm"
                class="px-5 py-2.5 rounded-lg text-sm bg-white border btn-active cursor-pointer ">Simpan</button>
        @endif
    </div>

    <script>
        (function() {
            const canManage = @json($canManage);
            const tbody = document.getElementById('poTbody');
            const btnAdd = document.getElementById('btnAddRow');

            function toNum(v) {
                if (v === null || v === undefined) return 0;
                const s = String(v).replace(/,/g, '.').replace(/[^0-9.\-]/g, '');
                const n = parseFloat(s);
                return isNaN(n) ? 0 : n;
            }

            function money(n) {
                const x = isNaN(n) ? 0 : n;
                return x.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function calc() {
                let subtotal = 0;
                let pajak = 0;

                tbody.querySelectorAll('tr.po-row').forEach(tr => {
                    const qty = toNum(tr.querySelector('.qty')?.value);
                    const harga = toNum(tr.querySelector('.harga')?.value);
                    const tarif = toNum(tr.querySelector('.pajak')?.value);

                    const nilai = qty * harga;
                    const nilaiPajak = nilai * (tarif / 100);
                    const totalBaris = nilai + nilaiPajak;

                    subtotal += nilai;
                    pajak += nilaiPajak;

                    const cell = tr.querySelector('.total-baris');
                    if (cell) cell.textContent = money(totalBaris);
                });

                document.getElementById('sumSubtotal').textContent = money(subtotal);
                document.getElementById('sumPajak').textContent = money(pajak);
                document.getElementById('sumTotal').textContent = money(subtotal + pajak);
            }

            function addRow() {
                const tpl = tbody.querySelector('tr.po-row');
                if (!tpl) return;

                const clone = tpl.cloneNode(true);

                clone.querySelectorAll('input').forEach(i => {
                    if (i.name === 'qty[]') i.value = 1;
                    else if (i.name === 'harga_satuan[]') i.value = 0;
                    else if (i.name === 'tarif_pajak[]') i.value = 0;
                    else if (i.name === 'deskripsi[]') i.value = '';
                });

                const select = clone.querySelector('select[name="barang_id[]"]');
                if (select) select.selectedIndex = 0;

                const totalCell = clone.querySelector('.total-baris');
                if (totalCell) totalCell.textContent = '0';

                tbody.appendChild(clone);
                calc();
            }

            tbody.addEventListener('input', function(e) {
                if (!canManage) return;
                if (e.target.matches('.qty, .harga, .pajak')) calc();
            });

            tbody.addEventListener('click', function(e) {
                if (!canManage) return;
                if (e.target.classList.contains('btnRemove')) {
                    const rows = tbody.querySelectorAll('tr.po-row');
                    if (rows.length <= 1) return;
                    e.target.closest('tr')?.remove();
                    calc();
                }
            });

            if (btnAdd) {
                btnAdd.addEventListener('click', function() {
                    if (!canManage) return;
                    addRow();
                });
            }

            calc();
        })();
    </script>
@endsection
