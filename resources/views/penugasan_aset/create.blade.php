@extends('layouts.app')

@section('content')
    <div class="space-y-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Buat Penugasan Aset</h1>
            <a href="{{ route('penugasan-aset.index') }}"
                class="px-3 py-2 rounded-lg border-gray-300 text-sm border text-sm hover:bg-gray-50">
                Kembali
            </a>
        </div>

        <!-- Error -->
        @if ($errors->any())
            <div class="p-3 rounded-lg border-gray-300 text-sm bg-red-50 text-red-700 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('penugasan-aset.store') }}"
            class="bg-white border rounded-lg border-gray-300 text-sm p-4 space-y-4">
            @csrf

            <!-- Aset -->
            <div>
                <label class="text-sm font-medium">Aset</label>
                <select name="aset_id" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2">
                    <option value="">Pilih aset</option>
                    @foreach ($aset as $a)
                        <option value="{{ $a->id }}" @selected(old('aset_id') == $a->id)>
                            {{ $a->barang?->nama }} — {{ $a->no_serial ?? '-' }}
                        </option>
                    @endforeach
                </select>


                {{-- <div>
                    <label class="text-sm font-medium">Instansi</label>
                    <select name="instansi_id" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2">
                        <option value="">Pilih instansi</option>
                        @foreach ($instansi as $a)
                            <option value="{{ $a->id }}" @selected(old('instansi_id') == $a->id)>
                                {{ $a->tag_aset }} — {{ $a->no_serial ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}

            </div>

            <!-- Tanggal & Nomor Dok -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Tanggal Tugas</label>
                    <input type="datetime-local" name="tanggal_tugas" value="{{ old('tanggal_tugas') }}"
                        class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2">
                </div>

                <div>
                    <label class="text-sm font-medium">Nomor Dokumen Serah Terima</label>
                    <input name="nomor_dok_serah_terima" value="{{ old('nomor_dok_serah_terima', $nomor) }}"
                        class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Orang ditugaskan</label>
                    <select name="ditugaskan_ke_pengguna_id"
                        class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2">
                        <option value="">Pilih Orang</option>
                        @foreach ($pengguna as $p)
                            <option value="{{ $p->id }}" @selected(old('ditugaskan_ke_pengguna_id') == $p->id)>
                                {{ $p->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Unit ditugaskan</label>
                    <select name="ditugaskan_ke_unit_id"
                        class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2">
                        <option value="">Pilih Unit</option>
                        @foreach ($unit as $u)
                            <option value="{{ $u->id }}" @selected(old('ditugaskan_ke_unit_id') == $u->id)>
                                {{ $u->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <label class="text-sm font-medium">Catatan</label>
                <textarea name="catatan" rows="3" class="mt-1 w-full border rounded-lg border-gray-300 text-sm px-3 py-2">{{ old('catatan') }}</textarea>
            </div>

            <!-- Action -->
            <div class="flex gap-2">
                <a href="{{ route('penugasan-aset.index') }}"
                    class="px-3 py-2 rounded-lg border-gray-300 text-sm border btn-outline-active hover:bg-gray-50">
                    Batal
                </a>

                <button class="px-3 py-2 rounded-lg border-gray-300 btn-active text-white text-sm ">
                    Simpan
                </button>
            </div>

        </form>
    </div>
@endsection
