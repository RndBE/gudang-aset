@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold">Detail Permintaan Persetujuan</h1>
                <p class="text-sm text-gray-500">
                    {{ $data->judul }} • {{ $data->tipe_entitas }} #{{ $data->entitas_id }}
                </p>
            </div>
            <a href="{{ route('permintaan-persetujuan.index') }}"
                class="px-3 py-2 border rounded-lg  text-sm hover:bg-gray-100 btn-active">
                Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded-lg ">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded-lg ">
                {{ session('error') }}
            </div>
        @endif

        <!-- Info -->
        <div class="bg-white border rounded-lg border-gray-300 p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <div class="text-gray-500 text-sm">Alur</div>
                <div class="font-medium">{{ $data->alur->nama ?? '-' }}</div>
            </div>

            <div>
                <div class="text-gray-500 text-sm">Status</div>
                @php
                    $color = 'bg-gray-200 text-gray-700';
                    if ($data->status == 'berjalan') {
                        $color = 'bg-blue-100 text-blue-700';
                    }
                    if ($data->status == 'disetujui') {
                        $color = 'bg-green-100 text-green-700';
                    }
                    if ($data->status == 'ditolak') {
                        $color = 'bg-red-100 text-red-700';
                    }
                @endphp
                <span class="px-2 py-1 text-xs rounded-lg border-gray-300 {{ $color }}">
                    {{ ucfirst($data->status) }}
                </span>
            </div>

            <div>
                <div class="text-gray-500 text-sm">Langkah Aktif</div>
                <div class="font-medium">#{{ $data->langkah_saat_ini }}</div>
            </div>

            <div>
                <div class="text-gray-500 text-sm">Entitas</div>
                <div class="font-medium">{{ $data->tipe_entitas }} #{{ $data->id_entitas }}</div>
            </div>
        </div>


        <div class="bg-white border rounded-lg border-gray-300 p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- aset tugas yang diajukan --}}
            @if ($data->tipe_entitas === 'penugasan_aset')
                <div class="bg-white border rounded-lg border-gray-300">
                    <div class="border-b px-4 py-3 font-semibold border-gray-300">Detail Penugasan Aset</div>

                    <div class="p-4 text-sm">
                        @if ($entitas)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <div class="text-gray-500">Tag Aset</div>
                                    <div class="font-medium">{{ $entitas->aset?->tag_aset ?? '-' }}</div>
                                </div>

                                <div>
                                    <div class="text-gray-500">Barang</div>
                                    <div class="font-medium">
                                        {{ $entitas->aset?->barang?->sku ?? '-' }} —
                                        {{ $entitas->aset?->barang?->nama ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-gray-500">Ditugaskan ke Pengguna</div>
                                    <div class="font-medium">{{ $entitas->ditugaskanKePengguna?->nama_lengkap ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-gray-500">Ditugaskan ke Unit</div>
                                    <div class="font-medium">
                                        {{ $entitas->ditugaskanKeUnit?->nama_unit ?? ($entitas->ditugaskanKeUnit?->nama ?? '-') }}
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <div class="text-gray-500">Catatan</div>
                                    <div class="font-medium" style="white-space: pre-line">{{ $entitas->catatan ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-gray-500">Data penugasan tidak ditemukan.</div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- aset peminjaman yang diajukan --}}
            @if ($data->tipe_entitas === 'peminjaman_aset')
                <div class="bg-white border rounded-lg border-gray-300">
                    <div class="border-b px-4 py-3 font-semibold">Detail Peminjaman Aset</div>

                    <div class="p-4 text-sm">
                        @if ($entitas)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <div class="text-gray-500">Tag Aset</div>
                                    <div class="font-medium">{{ $entitas->aset?->tag_aset ?? '-' }}</div>
                                </div>

                                <div>
                                    <div class="text-gray-500">Barang</div>
                                    <div class="font-medium">
                                        {{ $entitas->aset?->barang?->sku ?? '-' }} —
                                        {{ $entitas->aset?->barang?->nama ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-gray-500">Dipinjamkan ke Pengguna</div>
                                    <div class="font-medium">{{ $entitas->dipinjamkanKePengguna?->nama_lengkap ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-gray-500">Dipinjamkan ke Unit</div>
                                    <div class="font-medium">
                                        {{ $entitas->dipinjamkanKeUnit?->nama_unit ?? ($entitas->dipinjamkanKeUnit?->nama ?? '-') }}
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <div class="text-gray-500">Catatan</div>
                                    <div class="font-medium" style="white-space: pre-line">{{ $entitas->catatan ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-gray-500">Data peminjaman tidak ditemukan.</div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- aset penugasan yang diajukan --}}
            @if ($data->tipe_entitas === 'penghapusan_aset')
                <div class="bg-white border rounded-lg border-gray-300">
                    <div class="border-b px-4 py-3 font-semibold">Detail Penghapusan Aset</div>

                    <div class="p-4 text-sm">
                        @if ($entitas)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <div class="text-gray-500">Tag Aset</div>
                                    <div class="font-medium">{{ $entitas->aset?->tag_aset ?? '-' }}</div>
                                </div>

                                <div>
                                    <div class="text-gray-500">Barang</div>
                                    <div class="font-medium">
                                        {{ $entitas->aset?->barang?->sku ?? '-' }} —
                                        {{ $entitas->aset?->barang?->nama ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-gray-500">Status Kondisi</div>
                                    <div class="font-medium">{{ $entitas->aset?->status_kondisi ?? '-' }}</div>
                                </div>

                                <div>
                                    <div class="text-gray-500">Alasan Penghapusan</div>
                                    <div class="font-medium">{{ $entitas->alasan ?? '-' }}</div>
                                </div>

                                <div class="md:col-span-2">
                                    <div class="text-gray-500">Catatan</div>
                                    <div class="font-medium" style="white-space: pre-line">{{ $entitas->catatan ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-gray-500">Data penghapusan tidak ditemukan.</div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- pesanan pembelian yang diajukan --}}
            @if ($data->tipe_entitas === 'pesanan_pembelian')
                <div class="bg-white border rounded-lg border-gray-300 md:col-span-2">
                    <div class="border-b px-4 py-3 font-semibold">Detail Pesanan Pembelian</div>

                    <div class="p-4 text-sm">
                        @if ($entitas)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                <div>
                                    <div class="text-gray-500">Nomor PO</div>
                                    <div class="font-medium">{{ $entitas->nomor_po ?? '-' }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Tanggal</div>
                                    <div class="font-medium">{{ optional($entitas->tanggal_po)->format('d/m/Y') ?? '-' }}
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="text-gray-500">Catatan</div>
                                    <div class="font-medium" style="white-space: pre-line">{{ $entitas->catatan ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded-lg border-gray-300 overflow-hidden">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="text-left px-3 py-2">Barang</th>
                                            <th class="text-right px-3 py-2 w-28">Qty</th>
                                            <th class="text-right px-3 py-2 w-40">Harga</th>
                                            <th class="text-right px-3 py-2 w-44">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $grand = 0; @endphp
                                        @forelse ($entitas->detail ?? [] as $d)
                                            @php
                                                $qty = (float) ($d->qty ?? 0);
                                                $harga = (float) ($d->biaya_satuan ?? ($d->harga_satuan ?? 0));
                                                $sub = $qty * $harga;
                                                $grand += $sub;
                                            @endphp
                                            <tr class="border-t border-gray-300">
                                                <td class="px-3 py-2">
                                                    <div class="font-medium">{{ $d->barang?->sku ?? '-' }} —
                                                        {{ $d->barang?->nama ?? '-' }}</div>
                                                    @if (!empty($d->catatan))
                                                        <div class="text-xs text-gray-500">{{ $d->catatan }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2 text-right">
                                                    {{ rtrim(rtrim(number_format($qty, 4, '.', ''), '0'), '.') }}</td>
                                                <td class="px-3 py-2 text-right">{{ number_format($harga, 0, ',', '.') }}
                                                </td>
                                                <td class="px-3 py-2 text-right">{{ number_format($sub, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="border-t">
                                                <td colspan="4" class="px-3 py-3 text-gray-500">Detail barang tidak
                                                    ditemukan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-gray-50 border-t">
                                        <tr>
                                            <td colspan="3" class="px-3 py-2 text-right font-semibold">Total</td>
                                            <td class="px-3 py-2 text-right font-semibold">
                                                {{ number_format($grand, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="text-gray-500">Data pesanan pembelian tidak ditemukan.</div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- permintaan yang diajukan --}}
            @if ($data->tipe_entitas === 'permintaan')
                <div class="bg-white border rounded-lg border-gray-300 md:col-span-2">
                    <div class="border-b px-4 py-3 font-semibold border-gray-300">Detail Permintaan Barang</div>

                    <div class="p-4 text-sm">
                        @if ($entitas)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                <div>
                                    <div class="text-gray-500">Nomor Permintaan</div>
                                    <div class="font-medium">{{ $entitas->nomor_permintaan ?? '-' }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Tanggal Permintaan</div>
                                    <div class="font-medium">
                                        {{ optional($entitas->tanggal_permintaan)->format('d/m/Y H:i') ?? '-' }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Tipe</div>
                                    <div class="font-medium">{{ $entitas->tipe_permintaan ?? '-' }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Prioritas</div>
                                    <div class="font-medium">{{ $entitas->prioritas ?? '-' }}</div>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="text-gray-500">Tujuan</div>
                                    <div class="font-medium" style="white-space: pre-line">{{ $entitas->tujuan ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded-lg border-gray-300-lg overflow-hidden">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="text-left px-3 py-2">Barang</th>
                                            <th class="text-center px-3 py-2 w-28">Diminta</th>
                                            <th class="text-center px-3 py-2 w-28">Catatan</th>
                                            {{-- <th class="text-right px-3 py-2 w-28">Disetujui</th>
                                            <th class="text-right px-3 py-2 w-28">Dipenuhi</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($entitas->detail ?? [] as $d)
                                            <tr class="border-t">
                                                <td class="px-3 py-2">
                                                    <div class="font-medium">{{ $d->barang?->sku ?? '-' }} —
                                                        {{ $d->barang?->nama ?? '-' }}</div>
                                                    @if (!empty($d->catatan))
                                                        <div class="text-xs text-gray-500">{{ $d->catatan }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2 text-center">
                                                    {{ rtrim(rtrim(number_format((float) ($d->qty_diminta ?? 0), 4, '.', ''), '0'), '.') }}
                                                </td>
                                                <td class="px-3 py-2 text-center">
                                                    {{ $d->catatan ?? '-' }}
                                                </td>
                                                {{-- <td class="px-3 py-2 text-right">
                                                    {{ rtrim(rtrim(number_format((float) ($d->qty_disetujui ?? 0), 4, '.', ''), '0'), '.') }}
                                                </td>
                                                <td class="px-3 py-2 text-right">
                                                    {{ rtrim(rtrim(number_format((float) ($d->qty_dipenuhi ?? 0), 4, '.', ''), '0'), '.') }}
                                                </td> --}}
                                            </tr>
                                        @empty
                                            <tr class="border-t">
                                                <td colspan="4" class="px-3 py-3 text-gray-500">Detail barang tidak
                                                    ditemukan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-gray-500">Data permintaan tidak ditemukan.</div>
                        @endif
                    </div>
                </div>
            @endif


        </div>

        <!-- Steps -->
        <div class="bg-white border rounded-lg border-gray-300">
            <div class="border-b px-4 py-3 font-semibold border-gray-300">
                Langkah Persetujuan
            </div>

            <div class="divide-y divide-gray-300">
                @foreach ($data->langkah as $l)
                    <div class="px-4 py-3 flex justify-between items-center">
                        <div>
                            <div class="font-medium">
                                {{ $l->no_langkah }}. {{ $l->nama_langkah }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $l->catatan_keputusan ?? '—' }}
                            </div>
                        </div>

                        @php
                            $c = 'bg-gray-200 text-gray-700';
                            if ($l->status == 'menunggu') {
                                $c = 'bg-yellow-100 text-yellow-700';
                            }
                            if ($l->status == 'disetujui') {
                                $c = 'bg-green-100 text-green-700';
                            }
                            if ($l->status == 'ditolak') {
                                $c = 'bg-red-100 text-red-700';
                            }
                        @endphp
                        <span class="px-2 py-1 text-xs rounded-lg border-gray-300 {{ $c }}">
                            {{ ucfirst($l->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        @php
            $step = $data->langkah->where('no_langkah', $data->langkah_saat_ini)->first();
        @endphp

        <!-- Action -->
        @if ($step && $step->status === 'menunggu' && $bolehApproveStep)
            {{-- @if ($step && $step->status === 'menunggu') --}}
            <div class="bg-white border border-gray-300 rounded-lg">
                <div class="border-b px-4 py-3 font-semibold border-gray-300">
                    Aksi Persetujuan – {{ $step->nama_langkah }}
                </div>

                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Approve -->
                    <form method="POST" action="{{ route('permintaan-persetujuan.setujui', $data->id) }}">
                        @csrf
                        <label class="text-sm font-medium">Catatan (opsional)</label>
                        <textarea name="catatan_keputusan" class="w-full border rounded-lg border-gray-300 px-3 py-2 text-sm mt-1 mb-3"
                            rows="3"></textarea>

                        <button
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg border-gray-300 text-sm"
                            onclick="return confirm('Setujui langkah ini?')">
                            Setujui
                        </button>
                    </form>

                    <!-- Reject -->
                    <form method="POST" action="{{ route('permintaan-persetujuan.tolak', $data->id) }}">
                        @csrf
                        <label class="text-sm font-medium">Catatan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="catatan_keputusan" required
                            class="w-full border rounded-lg border-gray-300 px-3 py-2 text-sm mt-1 mb-3" rows="3"></textarea>

                        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg border-gray-300 text-sm"
                            onclick="return confirm('Tolak permintaan ini?')">
                            Tolak
                        </button>
                    </form>

                </div>
            </div>
        @endif

    </div>
@endsection
