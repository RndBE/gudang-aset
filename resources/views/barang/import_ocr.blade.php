@extends('layouts.app')
<style>
    #scanOverlay .scanner-frame {
        border: 2px solid rgba(255, 255, 255, .35);
        box-shadow:
            0 0 0 9999px rgba(0, 0, 0, .05) inset,
            0 10px 40px rgba(0, 0, 0, .25);
    }

    #scanOverlay .scanner-corners {
        --c: rgba(197, 141, 42, .95);
        position: absolute;
        inset: 1.5rem;
        border-radius: .75rem;
        pointer-events: none;
        filter: drop-shadow(0 0 10px rgba(197, 141, 42, .35));
    }

    #scanOverlay .scanner-corners .corner {
        position: absolute;
        width: 34px;
        height: 34px;
        border: 10px solid var(--c);
    }

    #scanOverlay .scanner-corners .corner.tl {
        left: 0;
        top: 0;
        border-right: 0;
        border-bottom: 0;
        border-top-left-radius: 14px;
    }

    #scanOverlay .scanner-corners .corner.tr {
        right: 0;
        top: 0;
        border-left: 0;
        border-bottom: 0;
        border-top-right-radius: 14px;
    }

    #scanOverlay .scanner-corners .corner.bl {
        left: 0;
        bottom: 0;
        border-right: 0;
        border-top: 0;
        border-bottom-left-radius: 14px;
    }

    #scanOverlay .scanner-corners .corner.br {
        right: 0;
        bottom: 0;
        border-left: 0;
        border-top: 0;
        border-bottom-right-radius: 14px;
    }

    @keyframes beamMove {
        0% {
            top: 1.5rem;
            opacity: .95;
        }

        70% {
            opacity: 1;
        }

        100% {
            top: calc(100% - 1.5rem - 64px);
            opacity: .95;
        }
    }

    #scanOverlay .scanner-beam {
        will-change: top;
    }


    #scanOverlay .scanner-beam {
        --g: rgba(197, 141, 42, .85);
        background: linear-gradient(to bottom,
                transparent,
                rgba(255, 255, 255, .08),
                var(--g),
                rgba(255, 255, 255, .10),
                transparent);
        box-shadow: 0 12px 30px rgba(197, 141, 42, .22);
        animation: beamMove 1.55s ease-in-out infinite alternate;
        mix-blend-mode: screen;
    }

    @keyframes shimmerMove {
        0% {
            transform: translateX(-60%) skewX(-12deg);
            opacity: 0;
        }

        25% {
            opacity: .22;
        }

        60% {
            opacity: .18;
        }

        100% {
            transform: translateX(60%) skewX(-12deg);
            opacity: 0;
        }
    }

    #scanOverlay .scanner-shimmer {
        position: absolute;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .22), transparent);
        animation: shimmerMove 1.9s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes dotPulse {

        0%,
        80%,
        100% {
            transform: translateY(0);
            opacity: .35;
        }

        40% {
            transform: translateY(-2px);
            opacity: 1;
        }
    }

    #scanOverlay .scanner-dots span {
        width: 6px;
        height: 6px;
        border-radius: 999px;
        background: rgba(17, 24, 39, .9);
        display: inline-block;
        animation: dotPulse 1s infinite;
    }

    #scanOverlay .scanner-dots span:nth-child(2) {
        animation-delay: .15s;
    }

    #scanOverlay .scanner-dots span:nth-child(3) {
        animation-delay: .3s;
    }
</style>

@section('content')
    <div class="flex items-center justify-between gap-4 mb-3">
        <div>
            <h1 class="text-xl font-semibold">Import Massal</h1>
        </div>
        <div class="flex">
            <button type="button" id="btnAddRow"
                class="px-5 cursor-pointer py-2 rounded-lg border text-sm btn-outline-active me-2">Tambah
                Baris</button>
            <a href="{{ route('barang.index') }}"
                class="btn-active px-4 py-2 lg:px-6 lg:py-3 rounded-lg text-sm  text-center">Kembali</a>
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

                <div class="relative rounded-lg overflow-hidden bg-white">
                    <img id="imgPreview" class="w-full max-h-90 object-contain" alt="">

                    <div id="scanOverlay"
                        class="absolute inset-0 hidden items-center justify-center overflow-hidden rounded-xl">
                        <div class="absolute inset-0 bg-black/35"></div>

                        <div class="scanner-frame absolute inset-6 rounded-xl"></div>
                        <div class="scanner-corners absolute inset-6 rounded-xl pointer-events-none">
                            <span class="corner tl"></span>
                            <span class="corner tr"></span>
                            <span class="corner bl"></span>
                            <span class="corner br"></span>
                        </div>

                        <div class="scanner-beam absolute inset-x-6 top-6 h-16 rounded-xl"></div>
                        <div class="scanner-shimmer absolute inset-6 rounded-xl"></div>

                        <div class="relative z-10 flex items-center gap-3 rounded-xl bg-white/85 px-4 py-2 backdrop-blur">
                            <div class="scanner-dots flex items-center gap-1">
                                <span></span><span></span><span></span>
                            </div>
                            <div class="text-sm font-medium text-gray-900">Memindai…</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-between">
                <div id="handwriteWrap" class="hidden">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 select-none cursor-pointer">
                        <input type="checkbox" id="chkHandwrite"
                            class="rounded border-gray-300 text-[#C58D2A] focus:ring-[#C58D2A]">
                        <span class="font-medium">Tulisan Tangan</span>
                    </label>
                </div>

                <div class="flex items-center ">
                    <button type="button" id="btnScan"
                        class="px-3 py-2 rounded-lg btn-outline-active cursor-pointer text-sm">
                        Scan Gambar
                    </button>
                    <div id="scanStatus" class="text-sm text-gray-600 ms-2"></div>
                </div>
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
        const scanOverlay = document.getElementById('scanOverlay');
        const rowsBody = document.getElementById('rowsBody');
        const rowCount = document.getElementById('rowCount');
        const btnAddRow = document.getElementById('btnAddRow');
        const chkHandwrite = document.getElementById('chkHandwrite');
        const handwriteWrap = document.getElementById('handwriteWrap');

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

            if (scanOverlay) {
                scanOverlay.classList.toggle('hidden', !isBusy);
                scanOverlay.classList.toggle('flex', isBusy);
            }
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
                if (handwriteWrap) handwriteWrap.classList.add('hidden');
                if (chkHandwrite) chkHandwrite.checked = false;
                resetResult();
                return;
            }

            const url = URL.createObjectURL(f);
            imgPreview.src = url;

            if (handwriteWrap) handwriteWrap.classList.remove('hidden');
            if (chkHandwrite) chkHandwrite.checked = false;

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
                const isHandwritten = chkHandwrite?.checked ? 1 : 0;
                fd.append('handwritten', isHandwritten);
                console.log(isHandwritten)
                const res = await fetch("{{ route('barang.import_ocr.scan') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json',
                    },
                    body: fd
                });


                const data = await res.json().catch(() => null);
                console.log(data);
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
