@extends('layouts.app')

@section('content')

    <div class="flex items-center justify-between gap-4 mb-4">
        <div>
            <h1 class="text-xl font-semibold">Pengeluaran</h1>
            <div class="text-sm text-gray-500">{{ $pengeluaran->nomor_pengeluaran }} — status: {{ $pengeluaran->status }}
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('pengeluaran.index') }}"
                class="inline-flex items-center rounded-lg border px-4 py-2 btn-active cursor-pointer text-sm hover:bg-gray-50">
                Kembali
            </a>

            {{-- @if (auth()->user()->punyaIzin(['pengeluaran.kelola']) &&
    !in_array($pengeluaran->status, ['dikeluarkan', 'dibatalkan'], true)) --}}
            @if (auth()->user()->punyaIzin('pengeluaran.kelola') &&
                    !in_array($pengeluaran->status, ['dikeluarkan', 'dibatalkan'], true))
                <form method="post" action="{{ route('pengeluaran.posting', $pengeluaran->id) }}">
                    @csrf
                    <button
                        class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                        Posting
                    </button>
                </form>

                <form method="post" action="{{ route('pengeluaran.batalkan', $pengeluaran->id) }}">
                    @csrf
                    <button
                        class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        Batalkan
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if (session('ok'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('ok') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <div class="font-semibold mb-1">Terjadi error:</div>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('pengeluaran.update', $pengeluaran->id) }}" class="space-y-4">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-lg border-gray-300 border bg-white p-4">
                <div class="text-sm font-semibold mb-3">Detail Status</div>

                <label class="block text-sm mb-1">Gudang</label>
                <select name="gudang_id" id="gudang_id" class="w-full text-sm rounded-lg border py-2 px-3 border-gray-300"
                    @disabled(in_array($pengeluaran->status, ['dikeluarkan', 'dibatalkan'], true))>
                    <option value="">- pilih -</option>
                    @foreach ($gudang as $g)
                        <option value="{{ $g->id }}" @selected(old('gudang_id', $pengeluaran->gudang_id) == $g->id)>{{ $g->kode }} —
                            {{ $g->nama }}</option>
                    @endforeach
                </select>

                <div class="mt-3">
                    <label class="block text-sm mb-1">Unit Organisasi</label>
                    <select name="unit_organisasi_id" class="w-full text-sm rounded-lg  py-2 px-3 border border-gray-300"
                        @disabled(in_array($pengeluaran->status, ['dikeluarkan', 'dibatalkan'], true))>
                        <option value="">-</option>
                        @foreach ($unit as $u)
                            <option value="{{ $u->id }}" @selected(old('unit_organisasi_id', $pengeluaran->unit_organisasi_id) == $u->id)>{{ $u->kode }} —
                                {{ $u->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label class="block text-sm mb-1">Tanggal Pengeluaran</label>
                    <input type="datetime-local" name="tanggal_pengeluaran"
                        value="{{ old('tanggal_pengeluaran', optional($pengeluaran->tanggal_pengeluaran)->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}"
                        class="w-full text-sm rounded-lg border py-2 px-3 border-gray-300" @disabled(in_array($pengeluaran->status, ['dikeluarkan', 'dibatalkan'], true))>
                </div>

                <div class="mt-3">
                    <label class="block text-sm mb-1">Diserahkan ke Pengguna</label>
                    <select name="diserahkan_ke_pengguna_id"
                        class="w-full text-sm rounded-lg border py-2 px-3 border-gray-300" @disabled(in_array($pengeluaran->status, ['dikeluarkan', 'dibatalkan'], true))>
                        <option value="">-</option>
                        @foreach ($pengguna as $p)
                            <option value="{{ $p->id }}" @selected(old('diserahkan_ke_pengguna_id', $pengeluaran->diserahkan_ke_pengguna_id) == $p->id)>{{ $p->nama_lengkap }}
                                ({{ $p->username }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label class="block text-sm mb-1">Diserahkan ke Unit</label>
                    <select name="diserahkan_ke_unit_id" class="w-full text-sm rounded-lg border py-2 px-3 border-gray-300"
                        @disabled(in_array($pengeluaran->status, ['dikeluarkan', 'dibatalkan'], true))>
                        <option value="">-</option>
                        @foreach ($unit as $u)
                            <option value="{{ $u->id }}" @selected(old('diserahkan_ke_unit_id', $pengeluaran->diserahkan_ke_unit_id) == $u->id)>{{ $u->kode }} —
                                {{ $u->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label class="block text-sm mb-1">Catatan</label>
                    <textarea name="catatan" rows="3" class="w-full text-sm rounded-lg border py-2 px-3 border-gray-300"
                        @disabled(in_array($pengeluaran->status, ['dikeluarkan', 'dibatalkan'], true))>{{ old('catatan', $pengeluaran->catatan) }}</textarea>
                </div>
            </div>

            <div class="md:col-span-2 rounded-lg border border-gray-300 bg-white p-4 overflow-x-auto">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-sm font-semibold">Detail Barang</div>
                    @if (!in_array($pengeluaran->status, ['dikeluarkan', 'dibatalkan'], true))
                        <button type="button" id="btnAdd"
                            class="inline-flex items-center rounded-lg bg-gray-900 px-3 py-2 text-sm font-semibold text-white hover:bg-black">
                            Tambah Baris
                        </button>
                    @endif
                </div>
                <table class="min-w-full text-sm" id="detailTable">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-2 py-2 text-left">Barang</th>
                            <th class="px-2 py-2 text-left">Lokasi</th>
                            <th class="px-2 py-2 text-left">Lot</th>
                            <th class="px-2 py-2 text-left">Exp</th>
                            <th class="px-2 py-2 text-right">Qty</th>
                            <th class="px-2 py-2 text-right">Biaya</th>
                            <th class="px-2 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" id="detailBody"></tbody>
                </table>

                @if (!in_array($pengeluaran->status, ['dikeluarkan', 'dibatalkan'], true))
                    <div class="mt-4 flex justify-end">
                        <button
                            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </form>
    @php
        $readOnly = in_array($pengeluaran->status, ['dikeluarkan', 'dibatalkan'], true);

        $barangOptions = $barang
            ->map(
                fn($b) => [
                    'id' => $b->id,
                    'text' => $b->sku . ' — ' . $b->nama,
                ],
            )
            ->values();

        $existing = $pengeluaran->detail
            ->map(
                fn($d) => [
                    'barang_id' => $d->barang_id,
                    'lokasi_id' => $d->lokasi_id,
                    'no_lot' => $d->no_lot,
                    'tanggal_kedaluwarsa' => $d->tanggal_kedaluwarsa ? $d->tanggal_kedaluwarsa->format('Y-m-d') : null,
                    // 'qty' => (string) $d->qty,
                    'qty' => (int) $d->qty,
                    'biaya_satuan' => (string) ($d->biaya_satuan ?? 0),
                ],
            )
            ->values();
    @endphp
    <script>
        const readOnly = @json($readOnly);
        const barangOptions = @json($barangOptions);
        const lokasiByGudang = @json($lokasiByGudang);
        const existing = @json($existing);

        const tbody = document.getElementById('detailBody');
        const gudangSelect = document.getElementById('gudang_id');

        function makeSelect(name, options, value = '', disabled = false) {
            const s = document.createElement('select');
            s.name = name;
            s.className = 'w-full rounded-lg border-gray-300';
            if (disabled) s.disabled = true;
            const opt0 = document.createElement('option');
            opt0.value = '';
            opt0.textContent = '-';
            s.appendChild(opt0);
            for (const o of options) {
                const op = document.createElement('option');
                op.value = o.id;
                op.textContent = o.text;
                if (String(value) === String(o.id)) op.selected = true;
                s.appendChild(op);
            }
            return s;
        }

        function rowTemplate(idx, preset = null) {
            const tr = document.createElement('tr');

            const tdBarang = document.createElement('td');
            tdBarang.className = 'px-2 py-2 min-w-[260px]';
            tdBarang.appendChild(makeSelect(`barang_id[${idx}]`, barangOptions, preset?.barang_id ?? '', readOnly));
            tr.appendChild(tdBarang);

            const tdLok = document.createElement('td');
            tdLok.className = 'px-2 py-2 min-w-[260px]';
            const lokSelect = makeSelect(`lokasi_id[${idx}]`, [], preset?.lokasi_id ?? '', readOnly);
            lokSelect.dataset.role = 'lokasi';
            tdLok.appendChild(lokSelect);
            tr.appendChild(tdLok);

            const tdLot = document.createElement('td');
            tdLot.className = 'px-2 py-2 min-w-[140px]';
            tdLot.innerHTML =
                `<input name="no_lot[${idx}]" value="${preset?.no_lot ?? ''}" class="w-full rounded-lg border-gray-300" ${readOnly ? 'disabled' : ''} />`;
            tr.appendChild(tdLot);

            const tdExp = document.createElement('td');
            tdExp.className = 'px-2 py-2 min-w-[150px]';
            tdExp.innerHTML =
                `<input type="date" name="tanggal_kedaluwarsa[${idx}]" value="${preset?.tanggal_kedaluwarsa ?? ''}" class="w-full rounded-lg border-gray-300" ${readOnly ? 'disabled' : ''} />`;
            tr.appendChild(tdExp);

            const tdQty = document.createElement('td');
            tdQty.className = 'px-2 py-2 min-w-[120px] text-right';
            // tdQty.innerHTML =
            //     `<input type="number" step="0.0001" min="0.0001" name="qty[${idx}]" value="${preset?.qty ?? '1'}" class="w-full rounded-lg border-gray-300 text-right" ${readOnly ? 'disabled' : ''} />`;
            tdQty.innerHTML =
                `<input type="number" step="1" min="1" inputmode="numeric" name="qty[${idx}]" value="${preset?.qty ?? '1'}" class="w-full rounded-lg border-gray-300 text-right" ${readOnly ? 'disabled' : ''} />`
            tr.appendChild(tdQty);

            const tdBiaya = document.createElement('td');
            tdBiaya.className = 'px-2 py-2 min-w-[140px] text-right';
            tdBiaya.innerHTML =
                `<input type="number" step="0.0001" min="0" name="biaya_satuan[${idx}]" value="${preset?.biaya_satuan ?? '0'}" class="w-full rounded-lg border-gray-300 text-right" ${readOnly ? 'disabled' : ''} />`;
            tr.appendChild(tdBiaya);

            const tdAksi = document.createElement('td');
            tdAksi.className = 'px-2 py-2 text-right min-w-[90px]';
            if (!readOnly) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'inline-flex items-center rounded-lg border px-3 py-1.5 text-sm hover:bg-gray-50';
                btn.textContent = 'Hapus';
                btn.onclick = () => tr.remove();
                tdAksi.appendChild(btn);
            } else {
                tdAksi.innerHTML = `<span class="text-xs text-gray-400">-</span>`;
            }
            tr.appendChild(tdAksi);

            return tr;
        }

        function refreshLokasiOptions() {
            const gid = gudangSelect.value;
            const opts = (gid && lokasiByGudang[gid]) ? lokasiByGudang[gid] : [];
            const selects = document.querySelectorAll('select[data-role="lokasi"]');
            selects.forEach(s => {
                const name = s.name;
                const val = s.value;
                const parent = s.parentElement;
                const idx = name.match(/\[(\d+)\]/)?.[1] ?? '0';
                const newSel = makeSelect(`lokasi_id[${idx}]`, opts, val, readOnly);
                newSel.dataset.role = 'lokasi';
                parent.innerHTML = '';
                parent.appendChild(newSel);
            });
        }

        let rowIndex = 0;

        function addRow(preset = null) {
            const tr = rowTemplate(rowIndex++, preset);
            tbody.appendChild(tr);
            refreshLokasiOptions();
        }

        if (!readOnly) {
            document.getElementById('btnAdd')?.addEventListener('click', () => addRow());
            gudangSelect.addEventListener('change', refreshLokasiOptions);
        } else {
            gudangSelect.addEventListener('change', refreshLokasiOptions);
        }

        if (existing.length) {
            existing.forEach(x => addRow(x));
        } else {
            addRow();
        }
    </script>
@endsection
