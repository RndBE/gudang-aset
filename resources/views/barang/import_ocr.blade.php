@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-4 mb-3">
        <div>
            <h1 class="text-xl font-semibold">Import Massal</h1>
        </div>
        <a href="{{ route('barang.index') }}" class="px-6 py-3  btn-active rounded-lg border text-sm">Kembali</a>
    </div>

    @if ($errors->any())
        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-red-800 space-y-2">
            <div class="font-semibold">Validasi gagal:</div>
            <ul class="list-disc ms-5 text-sm">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <div class="rounded-lg border border-gray-300 bg-white p-5 space-y-4">
            <div class="font-semibold">Upload Gambar</div>

            <div class="space-y-2">
                <label for="imgInput"
                    class="group block cursor-pointer rounded-2xl border border-gray-200 bg-white p-4 transition
               hover:border-gray-400 hover:bg-gray-50 active:scale-[0.99]">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-gray-900">
                                Pilih file gambar
                            </div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                JPG / PNG, max 10MB
                            </div>
                        </div>

                        <div class="shrink-0">
                            <span
                                class="inline-flex items-center rounded-lg btn-active px-3 py-2 text-xs font-semibold text-white transition
                           group-hover:opacity-90">
                                Browse
                            </span>
                        </div>
                    </div>

                    <div id="fileMeta"
                        class="mt-3 hidden rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs text-gray-700">
                    </div>

                    <div class="mt-3 flex items-center gap-2 text-xs text-gray-500">
                        <span class="inline-block h-2 w-2 rounded-full bg-gray-300 group-hover:bg-black transition"></span>
                        Klik area ini untuk memilih file
                    </div>
                </label>
                <input id="imgInput" type="file" accept="image/*" class="hidden">

            </div>

            <div class="rounded-lg border-gray-300 border bg-gray-50 p-3">
                <div class="text-xs text-gray-600 mb-2">Preview</div>
                <img id="imgPreview" class="w-full max-h-[360px] object-contain rounded-lg bg-white" alt="">

            </div>
            <div class="flex items-center gap-3">
                <button type="button" id="btnScan" hidden
                    class="px-3 py-2 rounded-lg btn-outline-active cursor-pointer text-sm">
                    Scan Gambar
                </button>
                <div id="scanStatus" class="text-sm text-gray-600"></div>
            </div>
        </div>

        <div class="rounded-lg border border-gray-300 bg-white px-5 pt-5 pb-4 space-y-3">
            <div class="font-semibold">Hasil OCR</div>
            <textarea id="ocrJson" readonly
                class="w-full h-[420px] border rounded-lg border-gray-300 p-3 font-mono text-xs bg-gray-50 focus:outline-none"></textarea>
        </div>
    </div>

    <form method="POST" action="{{ route('barang.import_ocr.store') }}" class="space-y-4">
        @csrf

        <div class="rounded-lg border border-gray-300 bg-white overflow-hidden">
            <div class="p-5 flex items-center justify-between gap-3">
                <div>
                    <div class="font-semibold">Koreksi Data</div>
                </div>
                <div class="text-sm text-gray-600">
                    Total: <span id="rowCount">0</span>
                </div>
            </div>

            <div class="overflow-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-y border-gray-300">
                        <tr class="text-left">
                            <th class="p-3 w-12">#</th>
                            <th class="p-3 min-w-[320px]">Nama</th>
                            <th class="p-3 min-w-[260px]">Kategori</th>
                            <th class="p-3 min-w-[260px]">Satuan</th>
                            <th class="p-3 w-16"></th>
                        </tr>
                    </thead>
                    <tbody id="rowsBody"></tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-between gap-3">
            <button type="button" id="btnAddRow"
                class="px-5 cursor-pointer py-2 rounded-lg border text-sm btn-outline-active ">Tambah
                Baris</button>

            <button type="submit" class="px-5 cursor-pointer py-2 rounded-lg btn-active text-sm">
                Simpan Massal
            </button>
        </div>
    </form>

    <script>
        const kategoriOptions = @json($kategori->map(fn($k) => ['id' => $k->id, 'nama' => $k->nama])->values());
        const satuanOptions = @json($satuan->map(fn($s) => ['id' => $s->id, 'nama' => $s->nama])->values());

        const imgInput = document.getElementById('imgInput');
        const imgPreview = document.getElementById('imgPreview');
        const btnScan = document.getElementById('btnScan');
        const scanStatus = document.getElementById('scanStatus');
        const ocrJson = document.getElementById('ocrJson');
        const fileMeta = document.getElementById('fileMeta');

        const rowsBody = document.getElementById('rowsBody');
        const rowCount = document.getElementById('rowCount');
        const btnAddRow = document.getElementById('btnAddRow');

        function escapeHtml(str) {
            return String(str ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function buildSelect(name, items, selectedId, placeholder) {
            const opts = ['<option value="">' + escapeHtml(placeholder) + '</option>']
                .concat(items.map(x => {
                    const sel = String(x.id) === String(selectedId) ? 'selected' : '';
                    return `<option value="${escapeHtml(x.id)}" ${sel}>${escapeHtml(x.nama)}</option>`;
                }))
                .join('');
            return `<select name="${name}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">${opts}</select>`;
        }

        function renumber() {
            const trs = rowsBody.querySelectorAll('tr');
            trs.forEach((tr, idx) => {
                const n = tr.querySelector('[data-no]');
                if (n) n.textContent = String(idx + 1);
            });
            rowCount.textContent = String(trs.length);
        }

        function reindex() {
            const trs = Array.from(rowsBody.querySelectorAll('tr'));
            trs.forEach((tr, newIdx) => {
                tr.querySelectorAll('input, select').forEach(el => {
                    const name = el.getAttribute('name');
                    if (!name) return;
                    el.setAttribute('name', name.replace(/items\[\d+\]/g, `items[${newIdx}]`));
                });
            });
            renumber();
        }

        function clearRows() {
            rowsBody.innerHTML = '';
            renumber();
        }

        function addRow(data = {}) {
            const idx = rowsBody.querySelectorAll('tr').length;

            const nama = data.nama ?? '';
            const kategoriId = data.kategori_id ?? '';
            const satuanId = data.satuan_id ?? '';

            const tr = document.createElement('tr');
            tr.className = 'border-t border-gray-300';

            tr.innerHTML = `
            <td class="p-3 text-gray-600" data-no>${idx + 1}</td>
            <td class="p-3">
                <input name="items[${idx}][nama]" value="${escapeHtml(nama)}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-black" required>
            </td>
            <td class="p-3">
                ${buildSelect(`items[${idx}][kategori_id]`, kategoriOptions, kategoriId, 'Pilih kategori')}
            </td>
            <td class="p-3">
                ${buildSelect(`items[${idx}][satuan_id]`, satuanOptions, satuanId, 'Pilih satuan')}
            </td>
            <td class="p-3 text-right">
                <button type="button" class="px-3 py-2 rounded-lg border border-gray-300 cursor-pointer text-sm" data-remove>Hapus</button>
            </td>
        `;

            rowsBody.appendChild(tr);

            tr.querySelector('[data-remove]').addEventListener('click', () => {
                tr.remove();
                reindex();
            });

            renumber();
        }

        function normalizeOcrItems(json) {
            const items = Array.isArray(json) ? json : (json?.items ?? json?.data ?? []);
            if (!Array.isArray(items)) return [];

            return items
                .map(x => ({
                    nama: x?.nama ?? x?.name ?? '',
                    kategori_id: x?.kategori_id ?? x?.kategoriId ?? '',
                    satuan_id: x?.satuan_id ?? x?.satuanId ?? '',
                }))
                .filter(x => String(x.nama).trim() !== '');
        }

        function setBusy(isBusy) {
            btnScan.disabled = isBusy;
            btnAddRow.disabled = isBusy;
            imgInput.disabled = isBusy;

            if (isBusy) {
                btnScan.setAttribute('aria-busy', 'true');
            } else {
                btnScan.removeAttribute('aria-busy');
            }
        }

        function resetOcrResult() {
            scanStatus.textContent = '';
            ocrJson.value = '';
            clearRows();
            addRow({});
            toggleScanButton();
        }


        function toggleScanButton() {
            const hasFile = !!imgInput.files?.[0];
            btnScan.hidden = !hasFile;
        }


        imgInput.addEventListener('change', () => {
            const f = imgInput.files?.[0];

            if (!f) {
                if (fileMeta) {
                    fileMeta.classList.add('hidden');
                    fileMeta.textContent = '';
                }
                imgPreview.removeAttribute('src');
                resetOcrResult();
                return;
            }

            const url = URL.createObjectURL(f);
            imgPreview.src = url;

            if (fileMeta) {
                const sizeMb = (f.size / (1024 * 1024)).toFixed(2);
                fileMeta.textContent = `${f.name} • ${sizeMb} MB`;
                fileMeta.classList.remove('hidden');
            }

            resetOcrResult();
        });


        btnScan.addEventListener('click', async () => {
            const f = imgInput.files?.[0];
            if (!f) {
                scanStatus.textContent = 'Pilih gambar dulu.';
                return;
            }

            scanStatus.textContent = 'Scanning...';
            setBusy(true);

            try {
                const fd = new FormData();
                fd.append('image', f);

                const res = await fetch("{{ route('barang.import_ocr.scan') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json',
                    },
                    body: fd
                });

                const data = await res.json().catch(() => null);

                if (!res.ok) {
                    const msg = data?.message ?? 'OCR gagal.';
                    scanStatus.textContent = msg;
                    ocrJson.value = data ? JSON.stringify(data, null, 2) : '';
                    return;
                }

                ocrJson.value = JSON.stringify(data, null, 2);

                const items = normalizeOcrItems(data);
                clearRows();

                if (items.length) {
                    items.forEach(it => addRow(it));
                    scanStatus.textContent = `Selesai. ${items.length} baris dimuat.`;
                } else {
                    addRow({});
                    scanStatus.textContent = 'Selesai, tapi tidak ada item terdeteksi.';
                }
            } catch (e) {
                scanStatus.textContent = 'Error network/endpoint.';
            } finally {
                setBusy(false);
            }
        });

        btnAddRow.addEventListener('click', () => addRow({}));

        addRow({});
        toggleScanButton();
    </script>

@endsection
