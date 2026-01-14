@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-3">
        <div>
            <div class="text-xl font-semibold">Buat Permintaan</div>
            <div class="text-sm text-gray-500">Nomor: {{ $nomorPreview }}</div>
        </div>
        <a href="{{ route('permintaan.index') }}" class="px-3 py-2 rounded border hover:bg-gray-50">Kembali</a>
    </div>

    @if ($errors->any())
        <div class="p-3 rounded bg-red-50 text-red-800 border border-red-200">
            <div class="font-semibold mb-2">Validasi gagal</div>
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('permintaan.store') }}" class="space-y-4">
        @csrf

        <div class="bg-white border rounded p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="text-sm text-gray-600">Tanggal Permintaan</label>
                <input type="datetime-local" name="tanggal_permintaan"
                    value="{{ old('tanggal_permintaan', now()->format('Y-m-d\TH:i')) }}"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="text-sm text-gray-600">Tipe Permintaan</label>
                <select name="tipe_permintaan" class="w-full border rounded px-3 py-2">
                    @foreach ($tipeList as $k => $v)
                        <option value="{{ $k }}" @selected(old('tipe_permintaan', 'habis_pakai') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-gray-600">Prioritas</label>
                <select name="prioritas" class="w-full border rounded px-3 py-2">
                    @foreach ($prioritasList as $k => $v)
                        <option value="{{ $k }}" @selected(old('prioritas', 'normal') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-gray-600">Dibutuhkan Pada</label>
                <input type="date" name="dibutuhkan_pada" value="{{ old('dibutuhkan_pada') }}"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="md:col-span-4">
                <label class="text-sm text-gray-600">Tujuan</label>
                <textarea name="tujuan" rows="2" class="w-full border rounded px-3 py-2">{{ old('tujuan') }}</textarea>
            </div>
        </div>

        <div class="bg-white border rounded">
            <div class="p-4 flex items-center justify-between border-b">
                <div class="font-semibold">Detail Barang</div>
                <button type="button" id="btnTambah" class="px-3 py-2 rounded bg-gray-900 text-white hover:bg-black"
                    @disabled($barang->isEmpty())>Tambah Baris</button>
            </div>

            @if ($barang->isEmpty())
                <div class="p-4">
                    <div class="p-3 rounded bg-yellow-50 text-yellow-800 border border-yellow-200">
                        Master barang masih kosong/atau tidak ada yang aktif untuk instansi kamu. Buat data di menu
                        <b>Barang</b> dulu.
                    </div>
                </div>
            @endif

            <div class="p-4 overflow-x-auto">
                <table class="w-full text-sm" id="tblDetail">
                    <thead class="bg-gray-50 text-left">
                        <tr>
                            <th class="px-3 py-2 w-2/5">Barang</th>
                            <th class="px-3 py-2 w-1/6">Qty Diminta</th>
                            <th class="px-3 py-2">Catatan</th>
                            <th class="px-3 py-2 w-16"></th>
                        </tr>
                    </thead>
                    <tbody id="detailBody">
                        @php
                            $oldBarang = old('barang_id', [null]);
                            $oldQty = old('qty_diminta', [1]);
                            $oldCat = old('catatan_item', ['']);
                        @endphp

                        @foreach ($oldBarang as $i => $bid)
                            <tr class="border-t">
                                <td class="px-3 py-2">
                                    <select name="barang_id[]" class="w-full border rounded px-3 py-2"
                                        @disabled($barang->isEmpty())>
                                        <option value="">Pilih barang...</option>
                                        @foreach ($barang as $b)
                                            <option value="{{ $b->id }}" @selected((string) $bid === (string) $b->id)>
                                                {{ $b->sku }} — {{ $b->nama }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" step="0.0001" min="0.0001" name="qty_diminta[]"
                                        value="{{ $oldQty[$i] ?? 1 }}" class="w-full border rounded px-3 py-2"
                                        @disabled($barang->isEmpty())>
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" name="catatan_item[]" value="{{ $oldCat[$i] ?? '' }}"
                                        class="w-full border rounded px-3 py-2" @disabled($barang->isEmpty())>
                                </td>
                                <td class="px-3 py-2 text-right">
                                    <button type="button" class="btnHapus px-3 py-2 rounded border hover:bg-gray-50"
                                        @disabled($barang->isEmpty())>Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('permintaan.index') }}" class="px-3 py-2 rounded border hover:bg-gray-50">Batal</a>
            <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700"
                @disabled($barang->isEmpty())>Simpan</button>
        </div>
    </form>

    <template id="rowTpl">
        <tr class="border-t">
            <td class="px-3 py-2">
                <select name="barang_id[]" class="w-full border rounded px-3 py-2">
                    <option value="">Pilih barang...</option>
                    @foreach ($barang as $b)
                        <option value="{{ $b->id }}">{{ $b->sku }} — {{ $b->nama }}</option>
                    @endforeach
                </select>
            </td>
            <td class="px-3 py-2">
                <input type="number" step="0.0001" min="0.0001" name="qty_diminta[]"
                    class="w-full border rounded px-3 py-2" value="1">
            </td>
            <td class="px-3 py-2">
                <input type="text" name="catatan_item[]" class="w-full border rounded px-3 py-2" placeholder="Opsional">
            </td>
            <td class="px-3 py-2 text-right">
                <button type="button" class="btnHapus px-3 py-2 rounded border hover:bg-gray-50">Hapus</button>
            </td>
        </tr>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.getElementById('detailBody')
            const tpl = document.getElementById('rowTpl')
            const btn = document.getElementById('btnTambah')

            if (btn) {
                btn.addEventListener('click', () => {
                    const node = tpl.content.cloneNode(true)
                    body.appendChild(node)
                })
            }

            if (body) {
                body.addEventListener('click', (e) => {
                    if (e.target.classList.contains('btnHapus')) {
                        const tr = e.target.closest('tr')
                        if (tr) tr.remove()
                    }
                })
            }
        })
    </script>
@endsection
