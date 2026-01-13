@extends('layouts.app')

@section('content')
    <div class="container">
        <h4 class="mb-3">Penghapusan Aset</h4>

        <div class="card mb-3">
            <div class="card-body">
                <div><strong>Tag:</strong> {{ $aset->tag_aset }}</div>
                <div><strong>Serial:</strong> {{ $aset->no_serial ?? '-' }}</div>
                <div><strong>Status sekarang:</strong> {{ $aset->status_siklus }}</div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('aset.penghapusan.store', $aset->id) }}">
                    @csrf

                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}">
                            @error('tanggal')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Metode</label>
                            <select name="metode" class="form-select">
                                @foreach (['hibah', 'lelang', 'rusak', 'hilang', 'lainnya'] as $m)
                                    <option value="{{ $m }}" @selected(old('metode') == $m)>{{ $m }}
                                    </option>
                                @endforeach
                            </select>
                            @error('metode')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alasan</label>
                        <textarea name="alasan" class="form-control" rows="3">{{ old('alasan') }}</textarea>
                        @error('alasan')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                    </div>

                    <button class="btn btn-danger" onclick="return confirm('Yakin hapuskan aset ini?')">
                        Proses Penghapusan
                    </button>

                    <a href="{{ route('aset.show', $aset->id) }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
