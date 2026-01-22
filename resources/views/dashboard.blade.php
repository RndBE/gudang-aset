@extends('layouts.app')

@section('content')
    <div class="bg-white">
        <div class="text-xl font-semibold mb-3">Dashboard</div>

        @php
            $topCards = [
                [
                    'label' => 'Total Instansi',
                    'value' => $totalInstansi ?? '-',
                    'bg' => 'bg-[#B27F26]',
                    'icon' => 'instansi',
                    'iconColor' => 'text-[#B27F26]',
                ],
                [
                    'label' => 'Total Gudang',
                    'value' => $totalGudang ?? '-',
                    'bg' => 'bg-[#52B226]',
                    'icon' => 'gudang',
                    'iconColor' => 'text-[#52B226]',
                ],
                [
                    'label' => 'Total Barang',
                    'value' => $totalBarang ?? '-',
                    'bg' => 'bg-[#2673B2]',
                    'icon' => 'barang',
                    'iconColor' => 'text-[#2673B2]',
                ],
                [
                    'label' => 'Total Pemasok',
                    'value' => $totalPemasok ?? '-',
                    'bg' => 'bg-[#269DB2]',
                    'icon' => 'pemasok',
                    'iconColor' => 'text-[#269DB2]',
                ],
            ];

            $bottomCards = [
                [
                    'label' => 'Total Aset',
                    'value' => $totalAset ?? '-',
                    'pill' => 'bg-green-100 text-green-700',
                    'icon' => 'daftar_aset',
                ],
                [
                    'label' => 'Aset Ditugaskan',
                    'value' => $asetDitugaskan ?? '-',
                    'pill' => 'bg-amber-100 text-amber-700',
                    'icon' => 'penugasan_aset',
                ],
                [
                    'label' => 'Aset Dipinjamkan',
                    'value' => $asetDipinjamkan ?? '-',
                    'pill' => 'bg-purple-100 text-purple-700',
                    'icon' => 'peminjaman_aset',
                ],
                [
                    'label' => 'Aset Dihapus',
                    'value' => $asetDihapus ?? '-',
                    'pill' => 'bg-red-100 text-red-700',
                    'icon' => 'penghapusan_aset',
                ],
            ];
        @endphp

        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                @foreach ($topCards as $c)
                    <div class="{{ $c['bg'] }} text-white rounded-xl px-4 py-3 shadow-sm">
                        <div class="flex items-center justify-start gap-4">
                            <div class="shrink-0 rounded-lg bg-white ring-1 ring-white/20 p-3">
                                {{-- {!! svg($c['icon'], 'h-6 w-6 ' . $c['iconColor']) !!} --}}
                                {!! file_get_contents(resource_path('icon/' . $c['icon'] . '.svg')) !!}
                            </div>

                            <div class="min-w-0">
                                <div class="text-sm/5 font-medium text-white/90 truncate">{{ $c['label'] }}</div>
                                <div class="text-2xl font-semibold tracking-tight">{{ $c['value'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                @foreach ($bottomCards as $c)
                    <div class="rounded-2xl bg-white border border-gray-200 px-5 py-4 shadow-sm">
                        <div class="flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <div class="text-2xl font-semibold tracking-tight text-gray-900">{{ $c['value'] }}</div>
                                <div class="mt-1 text-sm text-gray-600 truncate">{{ $c['label'] }}</div>
                            </div>

                            <div class="shrink-0 rounded-full {{ $c['pill'] }} p-3 ring-1 ring-black/5">
                                {{-- {!! svg($c['icon'], 'h-6 w-6') !!} --}}
                                <div class="h-6 w-6 [&_svg]:h-6 [&_svg]:w-6">
                                    {!! file_get_contents(resource_path('icon/' . $c['icon'] . '.svg')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="mt-6 rounded-2xl bg-white border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <div class="text-base font-semibold text-gray-900">Barang Masuk vs Keluar</div>
                    <div class="text-sm text-gray-500">Per hari ({{ count($labels ?? []) }} hari terakhir)</div>
                </div>
                <form method="get" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <select name="year" class="btn-outline-active border rounded-lg px-3 py-2 text-sm w-full sm:w-28">
                        @foreach ($yearOptions as $y)
                            <option value="{{ $y }}" @selected((int) $year === (int) $y)>{{ $y }}
                            </option>
                        @endforeach
                    </select>

                    <select name="mode" class="btn-outline-active border rounded-lg px-3 py-2 text-sm w-full sm:w-32">
                        <option value="weekly" @selected($mode === 'weekly')>Days</option>
                        <option value="monthly" @selected($mode === 'monthly')>Monthly</option>
                    </select>

                    <button
                        class=" btn-active px-4 py-2 rounded-lg text-sm bg-[#C58D2A] text-white w-full sm:w-auto">Terapkan</button>
                </form>
            </div>

            <div style="height: 320px;">
                <canvas id="stokBar" data-labels='@json($labels ?? [])' data-in='@json($in ?? [])'
                    data-out='@json($out ?? [])' style="height:280px;width:100%"></canvas>
            </div>
        </div>
        <div class="mt-6 grid grid-cols-1 xl:grid-cols-2 gap-4">
            <div class="rounded-2xl bg-white border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <div class="text-base font-semibold text-gray-900">Komposisi Kategori Barang</div>
                    </div>
                </div>

                <div style="height: 320px;">
                    <canvas id="stokPie" data-labels='@json($pieLabels ?? [])'
                        data-values='@json($pieValues ?? [])' style="height:280px;width:100%"></canvas>
                </div>
            </div>
            <div class="rounded-2xl bg-white border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <div class="text-base font-semibold text-gray-900">Pergerakan Stok</div>
                        <div class="text-sm text-gray-500">Penerimaan vs Pengeluaran</div>
                    </div>
                </div>

                <div style="height: 320px;">
                    <canvas id="pergerakanDonut" data-labels='@json($donutLabels ?? [])'
                        data-values='@json($donutValues ?? [])' style="width:100%"></canvas>
                </div>
            </div>
        </div>

    </div>
@endsection
