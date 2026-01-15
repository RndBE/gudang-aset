@extends('layouts.app')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold">Edit Alur Persetujuan</h1>

        <a href="{{ route('alur-persetujuan.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
            Kembali
        </a>
    </div>

    @if (session('error'))
        <div class="mb-4 p-3 rounded bg-red-50 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-50 text-red-700 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('alur-persetujuan.update', $data->id) }}"
        class="bg-white border rounded p-4 space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Kode</label>
                <input name="kode" value="{{ old('kode', $data->kode) }}"
                    class="mt-1 w-full border rounded px-3 py-2 text-sm" required>
                @error('kode')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="text-sm font-medium">Status</label>
                <select name="status" class="mt-1 w-full border rounded px-3 py-2 text-sm" required>
                    <option value="aktif" @selected(old('status', $data->status) === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status', $data->status) === 'nonaktif')>Nonaktif</option>
                </select>
                @error('status')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Nama</label>
            <input name="nama" value="{{ old('nama', $data->nama) }}"
                class="mt-1 w-full border rounded px-3 py-2 text-sm" required>
            @error('nama')
                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="text-sm font-medium">Berlaku Untuk</label>
            <input name="berlaku_untuk" value="{{ old('berlaku_untuk', $data->berlaku_untuk) }}"
                class="mt-1 w-full border rounded px-3 py-2 text-sm" placeholder="contoh: penugasan_aset" required>
            @error('berlaku_untuk')
                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <hr class="my-2">

        <div class="flex items-center justify-between mb-2">
            <div>
                <h3 class="text-sm font-semibold">Langkah Persetujuan</h3>
                <p class="text-xs text-gray-500">Tambah/hapus langkah sesuai kebutuhan.</p>
            </div>

            <button type="button" id="btnAddStep"
                class="px-3 py-2 rounded bg-gray-900 text-white text-sm hover:bg-gray-800">
                + Tambah Langkah
            </button>
        </div>

        <div id="stepsWrap" class="space-y-3">
            @php
                $oldLangkah = old('langkah');

                if (is_array($oldLangkah)) {
                    $steps = $oldLangkah;
                    $stepCount = count($oldLangkah);
                } else {
                    $steps = $langkah ?? [];
                    $stepCount = is_iterable($steps) ? count($steps) : 0;
                }

                $stepCount = max(1, $stepCount);
            @endphp

            @for ($i = 1; $i <= $stepCount; $i++)
                @php
                    $stepData = null;

                    if (is_array(old('langkah'))) {
                        $stepData = old("langkah.$i");
                    } else {
                        $stepData = isset($steps[$i - 1]) ? $steps[$i - 1] : null;
                    }

                    $noLangkah = $stepData['no_langkah'] ?? ($stepData->no_langkah ?? $i);
                    $namaLangkah = $stepData['nama_langkah'] ?? ($stepData->nama_langkah ?? "Persetujuan $i");
                    $peranId = $stepData['peran_id'] ?? ($stepData->peran_id ?? null);
                    $izinId = $stepData['izin_id'] ?? ($stepData->izin_id ?? null);
                    $wajibCatatan = $stepData['wajib_catatan'] ?? ($stepData->wajib_catatan ?? 0);
                    // $batasWaktuHari = $stepData['batas_waktu_hari'] ?? ($stepData->batas_waktu_hari ?? null);
                @endphp

                <div class="step-item border rounded p-4 bg-gray-50" data-step="{{ $i }}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-sm font-semibold">
                            Langkah <span class="step-number">{{ $i }}</span>
                        </div>

                        <button type="button"
                            class="btnRemoveStep px-3 py-1 border rounded text-xs text-red-700 hover:bg-red-50">
                            Hapus
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="text-xs text-gray-500">No Langkah</label>
                            <input name="langkah[{{ $i }}][no_langkah]" value="{{ $noLangkah }}"
                                class="w-full border rounded px-2 py-1 text-sm" readonly>
                            @error("langkah.$i.no_langkah")
                                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs text-gray-500">Nama Langkah</label>
                            <input name="langkah[{{ $i }}][nama_langkah]" value="{{ $namaLangkah }}"
                                class="w-full border rounded px-2 py-1 text-sm" required>
                            @error("langkah.$i.nama_langkah")
                                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs text-gray-500">Peran</label>
                            <select name="langkah[{{ $i }}][peran_id]"
                                class="w-full border rounded px-2 py-1 text-sm">
                                <option value="">Pilih Peran</option>
                                @foreach ($peran as $p)
                                    <option value="{{ $p->id }}" @selected((string) $peranId === (string) $p->id)>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error("langkah.$i.peran_id")
                                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs text-gray-500">Izin Khusus</label>
                            <select name="langkah[{{ $i }}][izin_id]"
                                class="w-full border rounded px-2 py-1 text-sm">
                                <option value="">Pilih izin</option>
                                @foreach ($izin as $iz)
                                    <option value="{{ $iz->id }}" @selected((string) $izinId === (string) $iz->id)>
                                        {{ $iz->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error("langkah.$i.izin_id")
                                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div class="md:col-span-2 flex items-center gap-2">
                            <input type="checkbox" name="langkah[{{ $i }}][wajib_catatan]" value="1"
                                class="h-4 w-4" @checked((int) $wajibCatatan === 1)>
                            <label class="text-sm">Wajib Catatan</label>
                        </div>

                        {{-- <div class="md:col-span-2">
                            <label class="text-xs text-gray-500">Batas waktu (hari)</label>
                            <input type="number" min="0" name="langkah[{{ $i }}][batas_waktu_hari]"
                                value="{{ $batasWaktuHari }}" class="w-full border rounded px-2 py-1 text-sm"
                                placeholder="opsional">
                        </div> --}}
                    </div>
                </div>
            @endfor
        </div>

        <div class="flex gap-2 pt-2">
            <a href="{{ route('alur-persetujuan.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
                Batal
            </a>

            <button class="px-3 py-2 rounded bg-gray-900 text-white text-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>

    <script>
        const stepsWrap = document.getElementById('stepsWrap');
        const btnAddStep = document.getElementById('btnAddStep');

        const PERAN_OPTIONS = @json($peran->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama])->values());
        const IZIN_OPTIONS = @json($izin->map(fn($i) => ['id' => $i->id, 'nama' => $i->nama])->values());

        function optionsHtml(options, placeholder) {
            let html = `<option value="">${placeholder}</option>`;
            for (const opt of options) {
                html += `<option value="${opt.id}">${opt.nama}</option>`;
            }
            return html;
        }

        function renderStep(stepNumber) {
            const el = document.createElement('div');
            el.className = 'step-item border rounded p-4 bg-gray-50';
            el.dataset.step = stepNumber;

            el.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <div class="text-sm font-semibold">
                        Langkah <span class="step-number">${stepNumber}</span>
                    </div>

                    <button type="button"
                        class="btnRemoveStep px-3 py-1 border rounded text-xs text-red-700 hover:bg-red-50">
                        Hapus
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div>
                        <label class="text-xs text-gray-500">No Langkah</label>
                        <input name="langkah[${stepNumber}][no_langkah]" value="${stepNumber}"
                            class="w-full border rounded px-2 py-1 text-sm" readonly>
                    </div>

                    <div>
                        <label class="text-xs text-gray-500">Nama Langkah</label>
                        <input name="langkah[${stepNumber}][nama_langkah]" value="Persetujuan ${stepNumber}"
                            class="w-full border rounded px-2 py-1 text-sm" required>
                    </div>

                    <div>
                        <label class="text-xs text-gray-500">Peran</label>
                        <select name="langkah[${stepNumber}][peran_id]"
                            class="w-full border rounded px-2 py-1 text-sm">
                            ${optionsHtml(PERAN_OPTIONS, 'Pilih Peran')}
                        </select>
                    </div>

                    <div>
                        <label class="text-xs text-gray-500">Izin Khusus</label>
                        <select name="langkah[${stepNumber}][izin_id]"
                            class="w-full border rounded px-2 py-1 text-sm">
                            ${optionsHtml(IZIN_OPTIONS, 'Pilih izin')}
                        </select>
                    </div>
                </div>

                <div class="mt-3 grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2 flex items-center gap-2">
                        <input type="checkbox" name="langkah[${stepNumber}][wajib_catatan]" value="1" class="h-4 w-4">
                        <label class="text-sm">Wajib Catatan</label>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs text-gray-500">Batas waktu (hari)</label>
                        <input type="number" min="0" name="langkah[${stepNumber}][batas_waktu_hari]"
                            class="w-full border rounded px-2 py-1 text-sm" placeholder="opsional">
                    </div>
                </div>
            `;
            return el;
        }

        function normalizeSteps() {
            const steps = [...stepsWrap.querySelectorAll('.step-item')];

            steps.forEach((step, idx) => {
                const number = idx + 1;
                step.dataset.step = number;

                const stepNumberEl = step.querySelector('.step-number');
                if (stepNumberEl) stepNumberEl.textContent = number;

                const noLangkahInput = step.querySelector('input[name*="[no_langkah]"]');
                if (noLangkahInput) {
                    noLangkahInput.value = number;
                    noLangkahInput.name = `langkah[${number}][no_langkah]`;
                }

                const namaInput = step.querySelector('input[name*="[nama_langkah]"]');
                if (namaInput) namaInput.name = `langkah[${number}][nama_langkah]`;

                const peranSelect = step.querySelector('select[name*="[peran_id]"]');
                if (peranSelect) peranSelect.name = `langkah[${number}][peran_id]`;

                const izinSelect = step.querySelector('select[name*="[izin_id]"]');
                if (izinSelect) izinSelect.name = `langkah[${number}][izin_id]`;

                const wajib = step.querySelector('input[type="checkbox"][name*="[wajib_catatan]"]');
                if (wajib) wajib.name = `langkah[${number}][wajib_catatan]`;

                const batas = step.querySelector('input[name*="[batas_waktu_hari]"]');
                if (batas) batas.name = `langkah[${number}][batas_waktu_hari]`;
            });

            const removeButtons = [...stepsWrap.querySelectorAll('.btnRemoveStep')];
            removeButtons.forEach((btn, idx) => {
                if (idx === 0) {
                    btn.disabled = true;
                    btn.classList.add('opacity-40', 'cursor-not-allowed');
                } else {
                    btn.disabled = false;
                    btn.classList.remove('opacity-40', 'cursor-not-allowed');
                }
            });
        }

        btnAddStep.addEventListener('click', () => {
            const nextNumber = stepsWrap.querySelectorAll('.step-item').length + 1;
            stepsWrap.appendChild(renderStep(nextNumber));
            normalizeSteps();
        });

        stepsWrap.addEventListener('click', (e) => {
            const btn = e.target.closest('.btnRemoveStep');
            if (!btn) return;

            const stepEl = btn.closest('.step-item');
            if (!stepEl) return;

            const steps = stepsWrap.querySelectorAll('.step-item');
            if (steps.length <= 1) return;

            stepEl.remove();
            normalizeSteps();
        });

        normalizeSteps();
    </script>
@endsection
