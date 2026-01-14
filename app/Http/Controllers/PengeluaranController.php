<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPergerakanStok;
use App\Models\Gudang;
use App\Models\LokasiGudang;
use App\Models\Pengeluaran;
use App\Models\PengeluaranDetail;
use App\Models\Pengguna;
use App\Models\PergerakanStok;
use App\Models\SaldoStok;
use App\Models\UnitOrganisasi;
use App\Models\UrutanNomorDokumen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengeluaranController extends Controller
{
    public function index()
    {
        $instansiId = auth()->user()->instansi_id;

        $items = Pengeluaran::query()
            ->with(['gudang'])
            ->where('instansi_id', $instansiId)
            ->orderByDesc('id')
            ->paginate(20);

        return view('pengeluaran.index', compact('items'));
    }

    public function create()
    {
        $instansiId = auth()->user()->instansi_id;

        $gudang = Gudang::query()
            ->where('instansi_id', $instansiId)
            ->orderBy('nama')
            ->get();

        $unit = UnitOrganisasi::query()
            ->where('instansi_id', $instansiId)
            ->orderBy('nama')
            ->get();

        $pengguna = Pengguna::query()
            ->where('instansi_id', $instansiId)
            ->orderBy('nama_lengkap')
            ->get();

        $barang = Barang::query()
            ->where('instansi_id', $instansiId)
            ->orderBy('nama')
            ->get();

        $lokasiByGudang = [];
        foreach ($gudang as $g) {
            $lokasiByGudang[$g->id] = LokasiGudang::query()
                ->where('gudang_id', $g->id)
                ->orderBy('jalur')
                ->orderBy('kode')
                ->get()
                ->map(fn($l) => [
                    'id' => $l->id,
                    'text' => trim(($l->jalur ? $l->jalur . ' — ' : '') . ($l->kode ?? '') . ($l->nama ? ' (' . $l->nama . ')' : '')),
                ])
                ->values()
                ->all();
        }

        return view('pengeluaran.create', compact('gudang', 'unit', 'pengguna', 'barang', 'lokasiByGudang'));
    }

    public function store(Request $request)
    {
        $instansiId = auth()->user()->instansi_id;
        $unitOrgId = auth()->user()->unit_organisasi_id;

        $data = $request->validate([
            'gudang_id' => ['required', 'integer', 'exists:gudang,id'],
            'unit_organisasi_id' => ['nullable', 'integer', 'exists:unit_organisasi,id'],
            'tanggal_pengeluaran' => ['required', 'date'],
            'diserahkan_ke_pengguna_id' => ['nullable', 'integer', 'exists:pengguna,id'],
            'diserahkan_ke_unit_id' => ['nullable', 'integer', 'exists:unit_organisasi,id'],
            'catatan' => ['nullable', 'string'],

            'barang_id' => ['required', 'array', 'min:1'],
            'barang_id.*' => ['required', 'integer', 'exists:barang,id'],
            'lokasi_id' => ['nullable', 'array'],
            'lokasi_id.*' => ['nullable', 'integer', 'exists:lokasi_gudang,id'],
            'qty' => ['required', 'array'],
            'qty.*' => ['required', 'numeric', 'min:0.0001'],
            'no_lot' => ['nullable', 'array'],
            'no_lot.*' => ['nullable', 'string', 'max:120'],
            'tanggal_kedaluwarsa' => ['nullable', 'array'],
            'tanggal_kedaluwarsa.*' => ['nullable', 'date'],
            'biaya_satuan' => ['nullable', 'array'],
            'biaya_satuan.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($data, $instansiId, $unitOrgId) {
            $tanggal = Carbon::parse($data['tanggal_pengeluaran']);

            $nomor = $this->nextNomor('PENGELUARAN', $instansiId, $unitOrgId, $tanggal);

            $pengeluaran = Pengeluaran::create([
                'instansi_id' => $instansiId,
                'gudang_id' => $data['gudang_id'],
                'unit_organisasi_id' => $data['unit_organisasi_id'] ?? $unitOrgId,
                'permintaan_id' => null,
                'nomor_pengeluaran' => $nomor,
                'tanggal_pengeluaran' => $tanggal,
                'diserahkan_ke_pengguna_id' => $data['diserahkan_ke_pengguna_id'] ?? null,
                'diserahkan_ke_unit_id' => $data['diserahkan_ke_unit_id'] ?? null,
                'status' => 'draft',
                'catatan' => $data['catatan'] ?? null,
                'dibuat_oleh' => auth()->user()->id,
                'diposting_oleh' => null,
            ]);

            foreach ($data['barang_id'] as $i => $barangId) {
                PengeluaranDetail::create([
                    'pengeluaran_id' => $pengeluaran->id,
                    'barang_id' => $barangId,
                    'lokasi_id' => $data['lokasi_id'][$i] ?? null,
                    'no_lot' => $data['no_lot'][$i] ?? null,
                    'tanggal_kedaluwarsa' => $data['tanggal_kedaluwarsa'][$i] ?? null,
                    'qty' => (float) $data['qty'][$i],
                    'biaya_satuan' => isset($data['biaya_satuan'][$i]) ? (float) $data['biaya_satuan'][$i] : 0,
                ]);
            }

            return redirect()->route('pengeluaran.edit', $pengeluaran->id)->with('ok', 'Pengeluaran dibuat (draft).');
        });
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $instansiId = auth()->user()->instansi_id;

        abort_unless($pengeluaran->instansi_id === $instansiId, 404);

        $pengeluaran->load(['detail.barang', 'gudang']);

        $gudang = Gudang::query()
            ->where('instansi_id', $instansiId)
            ->orderBy('nama')
            ->get();

        $unit = UnitOrganisasi::query()
            ->where('instansi_id', $instansiId)
            ->orderBy('nama')
            ->get();

        $pengguna = Pengguna::query()
            ->where('instansi_id', $instansiId)
            ->orderBy('nama_lengkap')
            ->get();

        $barang = Barang::query()
            ->where('instansi_id', $instansiId)
            ->orderBy('nama')
            ->get();

        $lokasiByGudang = [];
        foreach ($gudang as $g) {
            $lokasiByGudang[$g->id] = LokasiGudang::query()
                ->where('gudang_id', $g->id)
                ->orderBy('jalur')
                ->orderBy('kode')
                ->get()
                ->map(fn($l) => [
                    'id' => $l->id,
                    'text' => trim(($l->jalur ? $l->jalur . ' — ' : '') . ($l->kode ?? '') . ($l->nama ? ' (' . $l->nama . ')' : '')),
                ])
                ->values()
                ->all();
        }

        return view('pengeluaran.edit', compact('pengeluaran', 'gudang', 'unit', 'pengguna', 'barang', 'lokasiByGudang'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $instansiId = auth()->user()->instansi_id;

        abort_unless($pengeluaran->instansi_id === $instansiId, 404);

        if (in_array($pengeluaran->status, ['dikeluarkan', 'dibatalkan'], true)) {
            return back()->withErrors(['status' => 'Tidak bisa ubah karena sudah diposting/dibatalkan.']);
        }

        $data = $request->validate([
            'gudang_id' => ['required', 'integer', 'exists:gudang,id'],
            'unit_organisasi_id' => ['nullable', 'integer', 'exists:unit_organisasi,id'],
            'tanggal_pengeluaran' => ['required', 'date'],
            'diserahkan_ke_pengguna_id' => ['nullable', 'integer', 'exists:pengguna,id'],
            'diserahkan_ke_unit_id' => ['nullable', 'integer', 'exists:unit_organisasi,id'],
            'catatan' => ['nullable', 'string'],

            'barang_id' => ['required', 'array', 'min:1'],
            'barang_id.*' => ['required', 'integer', 'exists:barang,id'],
            'lokasi_id' => ['nullable', 'array'],
            'lokasi_id.*' => ['nullable', 'integer', 'exists:lokasi_gudang,id'],
            'qty' => ['required', 'array'],
            'qty.*' => ['required', 'numeric', 'min:0.0001'],
            'no_lot' => ['nullable', 'array'],
            'no_lot.*' => ['nullable', 'string', 'max:120'],
            'tanggal_kedaluwarsa' => ['nullable', 'array'],
            'tanggal_kedaluwarsa.*' => ['nullable', 'date'],
            'biaya_satuan' => ['nullable', 'array'],
            'biaya_satuan.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($data, $pengeluaran) {
            $pengeluaran->update([
                'gudang_id' => $data['gudang_id'],
                'unit_organisasi_id' => $data['unit_organisasi_id'] ?? $pengeluaran->unit_organisasi_id,
                'tanggal_pengeluaran' => Carbon::parse($data['tanggal_pengeluaran']),
                'diserahkan_ke_pengguna_id' => $data['diserahkan_ke_pengguna_id'] ?? null,
                'diserahkan_ke_unit_id' => $data['diserahkan_ke_unit_id'] ?? null,
                'catatan' => $data['catatan'] ?? null,
            ]);

            $pengeluaran->detail()->delete();

            foreach ($data['barang_id'] as $i => $barangId) {
                PengeluaranDetail::create([
                    'pengeluaran_id' => $pengeluaran->id,
                    'barang_id' => $barangId,
                    'lokasi_id' => $data['lokasi_id'][$i] ?? null,
                    'no_lot' => $data['no_lot'][$i] ?? null,
                    'tanggal_kedaluwarsa' => $data['tanggal_kedaluwarsa'][$i] ?? null,
                    'qty' => (float) $data['qty'][$i],
                    'biaya_satuan' => isset($data['biaya_satuan'][$i]) ? (float) $data['biaya_satuan'][$i] : 0,
                ]);
            }

            return back()->with('ok', 'Pengeluaran diperbarui.');
        });
    }

    public function posting(Pengeluaran $pengeluaran)
    {
        $instansiId = auth()->user()->instansi_id;
        $unitOrgId = auth()->user()->unit_organisasi_id;

        abort_unless($pengeluaran->instansi_id === $instansiId, 404);

        if (in_array($pengeluaran->status, ['dikeluarkan', 'dibatalkan'], true)) {
            return back()->withErrors(['status' => 'Sudah diposting/dibatalkan.']);
        }

        $pengeluaran->load(['detail']);

        if ($pengeluaran->detail->count() < 1) {
            return back()->withErrors(['detail' => 'Detail pengeluaran masih kosong.']);
        }

        return DB::transaction(function () use ($pengeluaran, $instansiId, $unitOrgId) {
            $tanggal = $pengeluaran->tanggal_pengeluaran ? Carbon::parse($pengeluaran->tanggal_pengeluaran) : now();

            $nomorPergerakan = $this->nextNomor('PERGERAKAN_STOK', $instansiId, $unitOrgId, $tanggal);

            $pergerakan = PergerakanStok::create([
                'instansi_id' => $instansiId,
                'nomor_pergerakan' => $nomorPergerakan,
                'jenis_pergerakan' => 'pengeluaran',
                'tipe_referensi' => 'pengeluaran',
                'id_referensi' => $pengeluaran->id,
                'tanggal_pergerakan' => $tanggal,
                'gudang_id' => $pengeluaran->gudang_id,
                'catatan' => $pengeluaran->catatan,
                'diposting_oleh' => auth()->user()->id,
                'status' => 'diposting',
                'dibuat_oleh' => auth()->user()->id,
            ]);

            foreach ($pengeluaran->detail as $d) {
                $qty = (float) $d->qty;

                $saldoQ = SaldoStok::query()
                    ->where('instansi_id', $instansiId)
                    ->where('gudang_id', $pengeluaran->gudang_id)
                    ->where('barang_id', $d->barang_id);

                if ($d->lokasi_id) {
                    $saldoQ->where('lokasi_id', $d->lokasi_id);
                } else {
                    $saldoQ->whereNull('lokasi_id');
                }

                if ($d->no_lot) {
                    $saldoQ->where('no_lot', $d->no_lot);
                } else {
                    $saldoQ->whereNull('no_lot');
                }

                if ($d->tanggal_kedaluwarsa) {
                    $saldoQ->whereDate('tanggal_kedaluwarsa', $d->tanggal_kedaluwarsa);
                } else {
                    $saldoQ->whereNull('tanggal_kedaluwarsa');
                }

                $saldo = $saldoQ->lockForUpdate()->first();

                if (!$saldo) {
                    return back()->withErrors(['stok' => 'Saldo stok tidak ditemukan untuk salah satu baris pengeluaran.']);
                }

                if ((float) $saldo->qty_bisa_dipakai < $qty) {
                    return back()->withErrors(['stok' => 'Stok tidak cukup untuk salah satu baris (qty melebihi qty_bisa_dipakai).']);
                }

                $saldo->update([
                    'qty_tersedia' => (float) $saldo->qty_tersedia - $qty,
                    'qty_bisa_dipakai' => (float) $saldo->qty_bisa_dipakai - $qty,
                    'pergerakan_terakhir_pada' => now(),
                ]);

                DetailPergerakanStok::create([
                    'pergerakan_stok_id' => $pergerakan->id,
                    'barang_id' => $d->barang_id,
                    'dari_gudang_id' => $pengeluaran->gudang_id,
                    'dari_lokasi_id' => $d->lokasi_id,
                    'ke_gudang_id' => null,
                    'ke_lokasi_id' => null,
                    'no_lot' => $d->no_lot,
                    'tanggal_kedaluwarsa' => $d->tanggal_kedaluwarsa,
                    'qty' => $qty,
                    'biaya_satuan' => $d->biaya_satuan ?? 0,
                ]);
            }

            $pengeluaran->update([
                'status' => 'dikeluarkan',
                'diposting_oleh' => auth()->user()->id,
            ]);

            return back()->with('ok', 'Pengeluaran diposting. Saldo stok berkurang & pergerakan stok tercatat.');
        });
    }

    public function batalkan(Pengeluaran $pengeluaran)
    {
        $instansiId = auth()->user()->instansi_id;

        abort_unless($pengeluaran->instansi_id === $instansiId, 404);

        if ($pengeluaran->status === 'dikeluarkan') {
            return back()->withErrors(['status' => 'Pengeluaran sudah diposting, pembatalan perlu alur reversal terpisah.']);
        }

        $pengeluaran->update(['status' => 'dibatalkan']);

        return back()->with('ok', 'Pengeluaran dibatalkan.');
    }

    private function nextNomor(string $tipe, int $instansiId, ?int $unitOrgId, Carbon $tanggal): string
    {
        $tahun = (int) $tanggal->year;

        $q = UrutanNomorDokumen::query()
            ->where('instansi_id', $instansiId)
            ->where('tipe_dokumen', $tipe)
            ->where('tahun', $tahun);

        if ($unitOrgId) {
            $q->where('unit_organisasi_id', $unitOrgId);
        } else {
            $q->whereNull('unit_organisasi_id');
        }

        $row = $q->lockForUpdate()->first();

        if (!$row) {
            UrutanNomorDokumen::create([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $unitOrgId,
                'tipe_dokumen' => $tipe,
                'tahun' => $tahun,
                'nomor_terakhir' => 0,
                'awalan' => null,
                'akhiran' => null,
            ]);

            $row = $q->lockForUpdate()->first();
        }

        $next = ((int) $row->nomor_terakhir) + 1;

        $row->update(['nomor_terakhir' => $next]);

        $pad = str_pad((string) $next, 5, '0', STR_PAD_LEFT);

        $prefix = $row->awalan ?: ($tipe . '/' . $tahun . '/');
        $suffix = $row->akhiran ? ('/' . $row->akhiran) : '';

        return $prefix . $pad . $suffix;
    }
}
