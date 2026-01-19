@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-4 mb-3">
        <div>
            <h1 class="text-xl font-semibold">Import Massal</h1>
        </div>
        <div class="flex">
            <button type="button" id="btnAddRow"
                class="px-5 cursor-pointer py-2 rounded-lg border text-sm btn-outline-active me-2">Tambah
                Baris</button>
            <a href="{{ route('barang.index') }}" class="px-6 py-3  btn-active rounded-lg border text-sm">Kembali</a>
        </div>

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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mb-4">
        <div class="lg:col-span-4 rounded-lg  bg-white space-y-2">
            <div class="font-semibold">Upload Gambar</div>

            <div class="space-y-2">
                <div class="relative rounded-lg bg-white p-6">
                    <svg class="pointer-events-none absolute inset-0 h-full w-full text-gray-300">
                        <rect x="8" y="8" width="calc(100% - 16px)" height="calc(100% - 16px)" rx="8" ry="8"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="24 12" />
                    </svg>

                    <div class="relative flex flex-col items-center justify-center gap-2 text-center">
                        <div class=" text-center h-full ">
                            {!! file_get_contents(resource_path('icon/image_upload.svg')) !!}
                        </div>
                        <div class="text-sm font-medium text-gray-800">Unggah <span class="text-[#C58D2A]">gambar</span>
                            disini</div>
                        <small class="text-xs text-gray-500">Ukuran maksimum 10 MB</small>
                        <input id="imgInput" type="file" accept="image/*"
                            class="absolute inset-0 cursor-pointer opacity-0" />
                    </div>
                </div>
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

        <div class="lg:col-span-8 rounded-lg bg-white space-y-3">
            <div class="font-semibold">Hasil OCR</div>

            <form method="POST" action="{{ route('barang.import_ocr.store') }}" class="space-y-2">
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

                <div class="flex items-center justify-end mt-4 gap-3">


                    <button type="submit" class="px-5 cursor-pointer py-3 rounded-lg btn-active text-sm">
                        Simpan Massal
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        const kategoriOptions = @json($kategori->map(fn($k) => ['id' => $k->id, 'nama' => $k->nama])->values());
        const satuanOptions = @json($satuan->map(fn($s) => ['id' => $s->id, 'nama' => $s->nama])->values());

        const imgInput = document.getElementById('imgInput');
        const imgPreview = document.getElementById('imgPreview');
        const btnScan = document.getElementById('btnScan');
        const scanStatus = document.getElementById('scanStatus');

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

        function toggleScanButton() {
            btnScan.hidden = !imgInput.files?.[0];
        }

        function setBusy(isBusy) {
            btnScan.disabled = isBusy;
            btnAddRow.disabled = isBusy;
            imgInput.disabled = isBusy;
        }

        function resetResult() {
            scanStatus.textContent = '';
            clearRows();
            addRow({});
            toggleScanButton();
        }

        imgInput.addEventListener('change', () => {
            const f = imgInput.files?.[0];

            if (!f) {
                imgPreview.removeAttribute('src');
                resetResult();
                return;
            }

            const url = URL.createObjectURL(f);
            imgPreview.src = url;

            resetResult();
        });

        btnScan.addEventListener('click', async () => {
            const f = imgInput.files?.[0];
            if (!f) {
                scanStatus.textContent = 'Pilih gambar dulu.';
                toggleScanButton();
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
                    scanStatus.textContent = data?.message ?? 'OCR gagal.';
                    return;
                }

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
                toggleScanButton();
            }
        });

        btnAddRow.addEventListener('click', () => addRow({}));

        addRow({});
        toggleScanButton();
    </script>
@endsection
