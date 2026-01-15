@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Tambah Alur Persetujuan</h1>
            <a href="{{ route('alur-persetujuan.index') }}" class="px-3 py-2 rounded border text-sm hover:bg-gray-100">
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white border rounded p-6">
            <form method="POST" action="{{ route('alur-persetujuan.store') }}" class="space-y-6">
                @csrf

                <!-- Info Alur -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Nama Alur</label>
                        <input name="nama" value="{{ old('nama') }}" class="w-full border rounded px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Kode</label>
                        <input name="kode" value="{{ old('kode') }}" class="w-full border rounded px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Berlaku Untuk</label>
                        <input name="berlaku_untuk" value="{{ old('berlaku_untuk') }}"
                            class="mt-1 w-full border rounded px-3 py-2 text-sm"
                            placeholder="Contoh: penugasan_aset / peminjaman_aset" required>
                        @error('berlaku_untuk')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                        {{-- <select name="berlaku_untuk" class="mt-1 w-full border rounded px-3 py-2 text-sm" required>
                            <option value="">-- pilih --</option>
                            @foreach ($berlakuUntukOptions as $k => $label)
                                <option value="{{ $k }}" @selected(old('berlaku_untuk') === $k)>{{ $label }}
                                </option>
                            @endforeach
                        </select> --}}
                    </div>

                    {{-- <div class="md:col-span-2">
                        <label class="text-sm text-gray-600">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="w-full border rounded px-3 py-2 text-sm">{{ old('keterangan') }}</textarea>
                    </div> --}}

                    <div class="md:col-span-2 flex items-center gap-2">
                        <input type="checkbox" name="aktif" id="aktif" class="rounded border" checked>
                        <label for="aktif" class="text-sm">Aktif</label>
                    </div>
                </div>

                <!-- Langkah Persetujuan -->
                <div>
                    <div class="font-semibold mb-2">Langkah Persetujuan</div>

                    <div id="stepsWrap" class="space-y-3">
                        @php
                            $oldLangkah = old('langkah');
                            $initialCount = is_array($oldLangkah) ? max(1, count($oldLangkah)) : 1;
                        @endphp

                        @for ($i = 1; $i <= $initialCount; $i++)
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
                                        <label class="text-xs text-gray-500">Urutan</label>
                                        <input name="langkah[{{ $i }}][urutan]"
                                            value="{{ old("langkah.$i.urutan", $i) }}"
                                            class="w-full border rounded px-2 py-1 text-sm" readonly>
                                    </div>

                                    <div>
                                        <label class="text-xs text-gray-500">Nama Langkah</label>
                                        <input name="langkah[{{ $i }}][nama_langkah]"
                                            value="{{ old("langkah.$i.nama_langkah", "Persetujuan $i") }}"
                                            class="w-full border rounded px-2 py-1 text-sm">
                                    </div>

                                    <div>
                                        <label class="text-xs text-gray-500">Peran</label>
                                        <select name="langkah[{{ $i }}][peran_id]"
                                            class="w-full border rounded px-2 py-1 text-sm" required>
                                            <option value="">Pilih Peran</option>
                                            @foreach ($peran as $p)
                                                <option value="{{ $p->id }}" @selected(old("langkah.$i.peran_id") == $p->id)>
                                                    {{ $p->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="text-xs text-gray-500">Izin Khusus</label>
                                        <select name="langkah[{{ $i }}][izin_id]"
                                            class="w-full border rounded px-2 py-1 text-sm" required>
                                            <option value="">Pilih izin</option>
                                            @foreach ($izin as $iz)
                                                <option value="{{ $iz->id }}" @selected(old("langkah.$i.izin_id") == $iz->id)>
                                                    {{ $iz->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <button type="button" id="btnAddStep"
                        class="px-3 py-2 rounded bg-gray-900 text-white text-sm hover:bg-gray-800">
                        + Tambah Langkah
                    </button>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <button class="px-4 py-2 rounded bg-gray-900 text-white text-sm hover:bg-gray-800">
                        Simpan
                    </button>
                    <a href="{{ route('alur-persetujuan.index') }}"
                        class="px-4 py-2 rounded border text-sm hover:bg-gray-100">
                        Batal
                    </a>
                </div>

            </form>
            <script>
                const stepsWrap = document.getElementById('stepsWrap');
                const btnAddStep = document.getElementById('btnAddStep');

                const PERAN_OPTIONS = @json($peran->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama])->values());

                const IZIN_OPTIONS = @json($izin->map(fn($iz) => ['id' => $iz->id, 'nama' => $iz->nama])->values());

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
                    <label class="text-xs text-gray-500">Urutan</label>
                    <input name="langkah[${stepNumber}][urutan]"
                        value="${stepNumber}"
                        class="w-full border rounded px-2 py-1 text-sm"
                        readonly>
                </div>

                <div>
                    <label class="text-xs text-gray-500">Nama Langkah</label>
                    <input name="langkah[${stepNumber}][nama_langkah]"
                        value="Persetujuan ${stepNumber}"
                        class="w-full border rounded px-2 py-1 text-sm">
                </div>

                <div>
                    <label class="text-xs text-gray-500">Peran</label>
                    <select name="langkah[${stepNumber}][peran_id]"
                        class="w-full border rounded px-2 py-1 text-sm" required>
                        ${optionsHtml(PERAN_OPTIONS, 'Pilih Peran')}
                    </select>
                </div>

                <div>
                    <label class="text-xs text-gray-500">Izin Khusus</label>
                    <select name="langkah[${stepNumber}][izin_id]"
                        class="w-full border rounded px-2 py-1 text-sm" required>
                        ${optionsHtml(IZIN_OPTIONS, 'Pilih izin')}
                    </select>
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

                        const urutanInput = step.querySelector('input[name*="[urutan]"]');
                        if (urutanInput) {
                            urutanInput.value = number;
                            urutanInput.name = `langkah[${number}][urutan]`;
                        }

                        const namaInput = step.querySelector('input[name*="[nama_langkah]"]');
                        if (namaInput) namaInput.name = `langkah[${number}][nama_langkah]`;

                        const peranSelect = step.querySelector('select[name*="[peran_id]"]');
                        if (peranSelect) peranSelect.name = `langkah[${number}][peran_id]`;

                        const izinSelect = step.querySelector('select[name*="[izin_id]"]');
                        if (izinSelect) izinSelect.name = `langkah[${number}][izin_id]`;
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
        </div>

    </div>
@endsection
