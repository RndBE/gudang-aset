@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-3">
        <div>
            <div class="text-xl font-semibold">Edit Permintaan</div>
            <div class="text-sm text-gray-500">Nomor: {{ $permintaan->nomor_permintaan }}</div>
        </div>
        <a href="{{ route('permintaan.index') }}"
            class="px-3 py-2 rounded-lg btn-active cursor-pointer hover:bg-gray-50">Kembali</a>
    </div>

    @if (session('ok'))
        <div class="p-3 rounded-lg bg-green-50 text-green-800 border border-green-200">{{ session('ok') }}</div>
    @endif

    @if ($errors->any())
        <div class="p-3 rounded-lg bg-red-50 text-red-800 border border-red-200">
            <div class="font-semibold mb-2">Validasi gagal</div>
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('permintaan.update', $permintaan->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="bg-white border rounded-lg border-gray-300 p-4 grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="text-sm text-gray-600">Tanggal Permintaan</label>
                <input type="datetime-local" name="tanggal_permintaan"
                    value="{{ old('tanggal_permintaan', optional($permintaan->tanggal_permintaan)->format('Y-m-d\TH:i')) }}"
                    class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2">
            </div>

            <div>
                <label class="text-sm text-gray-600">Tipe Permintaan</label>
                <select name="tipe_permintaan" class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2">
                    @foreach ($tipeList as $k => $v)
                        <option value="{{ $k }}" @selected(old('tipe_permintaan', $permintaan->tipe_permintaan) === $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-gray-600">Prioritas</label>
                <select name="prioritas" class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2">
                    @foreach ($prioritasList as $k => $v)
                        <option value="{{ $k }}" @selected(old('prioritas', $permintaan->prioritas) === $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-gray-600">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2">
                    @foreach ($statusList as $k => $v)
                        <option value="{{ $k }}" @selected(old('status', $permintaan->status) === $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-gray-600">Dibutuhkan Pada</label>
                <input type="date" name="dibutuhkan_pada"
                    value="{{ old('dibutuhkan_pada', optional($permintaan->dibutuhkan_pada)->format('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2">
            </div>

            <div class="md:col-span-5">
                <label class="text-sm text-gray-600">Tujuan</label>
                <textarea name="tujuan" rows="2" class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2">{{ old('tujuan', $permintaan->tujuan) }}</textarea>
            </div>
        </div>

        <div class="bg-white border border-gray-300 rounded-lg">
            <div class="p-4 flex items-center justify-between border-b border-gray-300">
                <div class="font-semibold">Detail Barang</div>
                <button type="button" id="btnTambah"
                    class="px-3 py-2 rounded-lg  btn-outline-active text-white cursor-pointer text-sm">Tambah
                    Baris</button>
            </div>

            <div class="p-4 overflow-x-auto">
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <table class="w-full text-sm  ">
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
                                $oldBarang = old('barang_id');
                                $oldQty = old('qty_diminta');
                                $oldCat = old('catatan_item');

                                $seed = [];
                                if (is_array($oldBarang)) {
                                    foreach ($oldBarang as $i => $bid) {
                                        $seed[] = [
                                            'barang_id' => $bid,
                                            'qty' => $oldQty[$i] ?? 1,
                                            'catatan' => $oldCat[$i] ?? '',
                                        ];
                                    }
                                } else {
                                    foreach ($permintaan->detail as $d) {
                                        $seed[] = [
                                            'barang_id' => $d->barang_id,
                                            'qty' => $d->qty_diminta,
                                            'catatan' => $d->catatan,
                                        ];
                                    }
                                    if (count($seed) === 0) {
                                        $seed[] = ['barang_id' => null, 'qty' => 1, 'catatan' => ''];
                                    }
                                }
                            @endphp

                            @foreach ($seed as $row)
                                <tr class="border-t border-gray-300">
                                    <td class="px-3 py-2">
                                        <select name="barang_id[]"
                                            class="w-full border rounded-lg border-gray-300 px-3 py-2 text-sm">
                                            <option value="">Pilih barang...</option>
                                            @foreach ($barang as $b)
                                                <option value="{{ $b->id }}" @selected((string) $row['barang_id'] === (string) $b->id)>
                                                    {{ $b->sku }} — {{ $b->nama }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="number" step="0.0001" min="0.0001" name="qty_diminta[]"
                                            value="{{ (int) round((float) ($row['qty'] ?? 1)) }}"
                                            class="w-full border rounded-lg border-gray-300 text-sm  px-3 py-2">
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="text" name="catatan_item[]" value="{{ $row['catatan'] ?? '' }}"
                                            class="w-full border rounded-lg border-gray-300 text-sm  px-3 py-2">
                                    </td>
                                    <td class="px-3 py-2 text-right">
                                        <button type="button"
                                            class="btnHapus px-3 py-2 rounded-lg border border-gray-300 text-sm  hover:bg-red-100 hover:border-red-100 hover:text-red-900 cursor-pointer">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('permintaan.index') }}"
                class="px-3 py-2 rounded-lg btn-outline-active text-sm border hover:bg-gray-50 ">Kembali</a>
            <button class="px-4 py-2 rounded-lg btn-active text-sm bg-blue-600 text-white hover:bg-blue-700">Simpan
                Perubahan</button>
            @if ($permintaan->status === 'draft')
                <button type="button" onclick="document.getElementById('ajukanForm').submit()"
                    class="px-5 py-2.5 rounded-lg text-sm bg-white border hover:bg-gray-50">
                    Ajukan
                </button>
            @endif
        </div>
    </form>
    @if ($permintaan->status === 'draft')
        <form id="ajukanForm" method="post" action="{{ route('permintaan.ajukan', $permintaan->id) }}" class="hidden">
            @csrf
        </form>
    @endif

    <template id="rowTpl">
        <tr class="border-t border-gray-300 text-sm ">
            <td class="px-3 py-2">
                <select name="barang_id[]" class="w-full border rounded-lg border-gray-300 text-sm   px-3 py-2">
                    <option value="">Pilih barang...</option>
                    @foreach ($barang as $b)
                        <option value="{{ $b->id }}">{{ $b->sku }} — {{ $b->nama }}</option>
                    @endforeach
                </select>
            </td>
            <td class="px-3 py-2">
                <input type="number" step="0.0001" min="0.0001" name="qty_diminta[]"
                    class="w-full border rounded-lg border-gray-300 text-sm  px-3 py-2" value="1">
            </td>
            <td class="px-3 py-2">
                <input type="text" name="catatan_item[]"
                    class="w-full border rounded-lg border-gray-300 text-sm  px-3 py-2" placeholder="Opsional">
            </td>
            <td class="px-3 py-2 text-right">
                <button type="button"
                    class="btnHapus px-3 py-2 rounded-lg border-gray-300 text-sm     border hover:bg-red-100 hover:border-red-100 hover:text-red-900 cursor-pointer">Hapus</button>
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
