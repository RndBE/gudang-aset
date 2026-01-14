<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Permintaan;
use App\Models\PermintaanDetail;
use App\Models\UrutanNomorDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PermintaanController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        $q = Permintaan::query()
            ->where('instansi_id', $user->instansi_id)
            ->with(['pemohon:id,nama_lengkap', 'unitOrganisasi:id,nama'])
            ->orderByDesc('tanggal_permintaan');

        if ($request->filled('status')) {
            $q->where('status', $request->string('status')->toString());
        }

        if ($request->filled('keyword')) {
            $kw = $request->string('keyword')->toString();
            $q->where(function ($w) use ($kw) {
                $w->where('nomor_permintaan', 'like', "%{$kw}%")
                    ->orWhere('tujuan', 'like', "%{$kw}%");
            });
        }

        $items = $q->paginate(15)->withQueryString();

        $statusList = [
            'draft' => 'Draft',
            'diajukan' => 'Diajukan',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'dipenuhi' => 'Dipenuhi',
            'dibatalkan' => 'Dibatalkan',
        ];

        return view('permintaan.index', compact('items', 'statusList'));
    }

    public function create()
    {
        $user = Auth::user();

        $barang = Barang::query()
            ->where('instansi_id', $user->instansi_id)
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get(['id', 'sku', 'nama']);

        $tipeList = [
            'habis_pakai' => 'Habis Pakai',
            'penugasan_aset' => 'Penugasan Aset',
            'peminjaman_aset' => 'Peminjaman Aset',
        ];

        $prioritasList = [
            'rendah' => 'Rendah',
            'normal' => 'Normal',
            'tinggi' => 'Tinggi',
            'mendesak' => 'Mendesak',
        ];

        $nomorPreview = $this->previewNomor('permintaan', $user->instansi_id, $user->unit_organisasi_id);

        return view('permintaan.create', compact('barang', 'tipeList', 'prioritasList', 'nomorPreview'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'tanggal_permintaan' => ['required', 'date'],
            'tipe_permintaan' => ['required', 'in:habis_pakai,penugasan_aset,peminjaman_aset'],
            'prioritas' => ['required', 'in:rendah,normal,tinggi,mendesak'],
            'tujuan' => ['nullable', 'string'],
            'dibutuhkan_pada' => ['nullable', 'date'],

            'barang_id' => ['required', 'array', 'min:1'],
            'barang_id.*' => ['required', 'integer', 'exists:barang,id'],
            'qty_diminta' => ['required', 'array', 'min:1'],
            'qty_diminta.*' => ['required', 'numeric', 'min:0.0001'],
            'catatan_item' => ['nullable', 'array'],
            'catatan_item.*' => ['nullable', 'string'],
        ]);

        return DB::transaction(function () use ($data, $user) {
            $validBarangIds = Barang::query()
                ->where('instansi_id', $user->instansi_id)
                ->whereIn('id', $data['barang_id'])
                ->pluck('id')
                ->map(fn($v) => (int)$v)
                ->all();

            foreach ($data['barang_id'] as $bid) {
                if (!in_array((int)$bid, $validBarangIds, true)) {
                    throw ValidationException::withMessages([
                        'barang_id' => 'Ada barang yang tidak valid untuk instansi ini.'
                    ]);
                }
            }

            $nomor = $this->nextNomor('permintaan', $user->instansi_id, $user->unit_organisasi_id);

            $permintaan = Permintaan::create([
                'instansi_id' => $user->instansi_id,
                'unit_organisasi_id' => $user->unit_organisasi_id,
                'nomor_permintaan' => $nomor,
                'tanggal_permintaan' => $data['tanggal_permintaan'],
                'pemohon_id' => $user->id,
                'tipe_permintaan' => $data['tipe_permintaan'],
                'prioritas' => $data['prioritas'],
                'status' => 'draft',
                'tujuan' => $data['tujuan'] ?? null,
                'dibutuhkan_pada' => $data['dibutuhkan_pada'] ?? null,
                'catatan_persetujuan' => null,
                'dibuat_oleh' => $user->id,
            ]);

            $rows = [];
            foreach ($data['barang_id'] as $i => $barangId) {
                $rows[] = [
                    'permintaan_id' => $permintaan->id,
                    'barang_id' => (int)$barangId,
                    'qty_diminta' => (float)$data['qty_diminta'][$i],
                    'qty_disetujui' => 0,
                    'qty_dipenuhi' => 0,
                    'catatan' => $data['catatan_item'][$i] ?? null,
                ];
            }

            $unique = [];
            foreach ($rows as $r) {
                $key = $r['barang_id'];
                if (isset($unique[$key])) {
                    $unique[$key]['qty_diminta'] = (float)$unique[$key]['qty_diminta'] + (float)$r['qty_diminta'];
                    if (!empty($r['catatan'])) {
                        $unique[$key]['catatan'] = trim(($unique[$key]['catatan'] ?? '') . ' | ' . $r['catatan']);
                    }
                } else {
                    $unique[$key] = $r;
                }
            }

            PermintaanDetail::insert(array_values($unique));

            return redirect()
                ->route('permintaan.edit', $permintaan->id)
                ->with('ok', 'Permintaan berhasil dibuat.');
        });
    }

    public function edit(Permintaan $permintaan)
    {
        $user = Auth::user();

        abort_unless($permintaan->instansi_id === $user->instansi_id, 404);

        $permintaan->load(['detail.barang:id,sku,nama']);

        $barang = Barang::query()
            ->where('instansi_id', $user->instansi_id)
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get(['id', 'sku', 'nama']);

        $tipeList = [
            'habis_pakai' => 'Habis Pakai',
            'penugasan_aset' => 'Penugasan Aset',
            'peminjaman_aset' => 'Peminjaman Aset',
        ];

        $prioritasList = [
            'rendah' => 'Rendah',
            'normal' => 'Normal',
            'tinggi' => 'Tinggi',
            'mendesak' => 'Mendesak',
        ];

        $statusList = [
            'draft' => 'Draft',
            'diajukan' => 'Diajukan',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'dipenuhi' => 'Dipenuhi',
            'dibatalkan' => 'Dibatalkan',
        ];

        return view('permintaan.edit', compact('permintaan', 'barang', 'tipeList', 'prioritasList', 'statusList'));
    }

    public function update(Request $request, Permintaan $permintaan)
    {
        $user = Auth::user();

        abort_unless($permintaan->instansi_id === $user->instansi_id, 404);

        $data = $request->validate([
            'tanggal_permintaan' => ['required', 'date'],
            'tipe_permintaan' => ['required', 'in:habis_pakai,penugasan_aset,peminjaman_aset'],
            'prioritas' => ['required', 'in:rendah,normal,tinggi,mendesak'],
            'status' => ['required', 'in:draft,diajukan,disetujui,ditolak,dipenuhi,dibatalkan'],
            'tujuan' => ['nullable', 'string'],
            'dibutuhkan_pada' => ['nullable', 'date'],

            'barang_id' => ['required', 'array', 'min:1'],
            'barang_id.*' => ['required', 'integer', 'exists:barang,id'],
            'qty_diminta' => ['required', 'array', 'min:1'],
            'qty_diminta.*' => ['required', 'numeric', 'min:0.0001'],
            'catatan_item' => ['nullable', 'array'],
            'catatan_item.*' => ['nullable', 'string'],
        ]);

        if (in_array($permintaan->status, ['dipenuhi', 'dibatalkan'], true)) {
            return back()->withErrors(['status' => 'Permintaan sudah final, tidak bisa diubah.']);
        }

        return DB::transaction(function () use ($data, $permintaan, $user) {
            $validBarangIds = Barang::query()
                ->where('instansi_id', $user->instansi_id)
                ->whereIn('id', $data['barang_id'])
                ->pluck('id')
                ->map(fn($v) => (int)$v)
                ->all();

            foreach ($data['barang_id'] as $bid) {
                if (!in_array((int)$bid, $validBarangIds, true)) {
                    throw ValidationException::withMessages([
                        'barang_id' => 'Ada barang yang tidak valid untuk instansi ini.'
                    ]);
                }
            }

            $permintaan->update([
                'tanggal_permintaan' => $data['tanggal_permintaan'],
                'tipe_permintaan' => $data['tipe_permintaan'],
                'prioritas' => $data['prioritas'],
                'status' => $data['status'],
                'tujuan' => $data['tujuan'] ?? null,
                'dibutuhkan_pada' => $data['dibutuhkan_pada'] ?? null,
            ]);

            $rows = [];
            foreach ($data['barang_id'] as $i => $barangId) {
                $rows[] = [
                    'permintaan_id' => $permintaan->id,
                    'barang_id' => (int)$barangId,
                    'qty_diminta' => (float)$data['qty_diminta'][$i],
                    'catatan' => $data['catatan_item'][$i] ?? null,
                ];
            }

            $unique = [];
            foreach ($rows as $r) {
                $key = $r['barang_id'];
                if (isset($unique[$key])) {
                    $unique[$key]['qty_diminta'] = (float)$unique[$key]['qty_diminta'] + (float)$r['qty_diminta'];
                    if (!empty($r['catatan'])) {
                        $unique[$key]['catatan'] = trim(($unique[$key]['catatan'] ?? '') . ' | ' . $r['catatan']);
                    }
                } else {
                    $unique[$key] = $r;
                }
            }

            PermintaanDetail::where('permintaan_id', $permintaan->id)->delete();

            $insert = [];
            foreach (array_values($unique) as $r) {
                $insert[] = [
                    'permintaan_id' => $r['permintaan_id'],
                    'barang_id' => $r['barang_id'],
                    'qty_diminta' => $r['qty_diminta'],
                    'qty_disetujui' => 0,
                    'qty_dipenuhi' => 0,
                    'catatan' => $r['catatan'] ?? null,
                ];
            }

            PermintaanDetail::insert($insert);

            return redirect()
                ->route('permintaan.edit', $permintaan->id)
                ->with('ok', 'Permintaan berhasil diperbarui.');
        });
    }

    private function previewNomor(string $tipeDokumen, int $instansiId, ?int $unitId): string
    {
        $tahun = (int) now()->format('Y');

        $row = UrutanNomorDokumen::query()
            ->where('instansi_id', $instansiId)
            ->where('unit_organisasi_id', $unitId)
            ->where('tipe_dokumen', $tipeDokumen)
            ->where('tahun', $tahun)
            ->first();

        $last = $row ? (int)$row->nomor_terakhir : 0;
        $next = $last + 1;

        $awalan = $row?->awalan;
        $akhiran = $row?->akhiran;

        $num = str_pad((string)$next, 5, '0', STR_PAD_LEFT);

        $parts = [];
        if ($awalan) $parts[] = $awalan;
        $parts[] = strtoupper($tipeDokumen) . '/' . $tahun . '/' . $num;
        if ($akhiran) $parts[] = $akhiran;

        return implode('/', $parts);
    }

    private function nextNomor(string $tipeDokumen, int $instansiId, ?int $unitId): string
    {
        $tahun = (int) now()->format('Y');

        $row = UrutanNomorDokumen::query()
            ->where('instansi_id', $instansiId)
            ->where('unit_organisasi_id', $unitId)
            ->where('tipe_dokumen', $tipeDokumen)
            ->where('tahun', $tahun)
            ->lockForUpdate()
            ->first();

        if (!$row) {
            $row = UrutanNomorDokumen::create([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $unitId,
                'tipe_dokumen' => $tipeDokumen,
                'tahun' => $tahun,
                'nomor_terakhir' => 0,
                'awalan' => null,
                'akhiran' => null,
            ]);
        }

        $row->nomor_terakhir = ((int)$row->nomor_terakhir) + 1;
        $row->save();

        $num = str_pad((string)$row->nomor_terakhir, 5, '0', STR_PAD_LEFT);

        $parts = [];
        if (!empty($row->awalan)) $parts[] = $row->awalan;
        $parts[] = strtoupper($tipeDokumen) . '/' . $tahun . '/' . $num;
        if (!empty($row->akhiran)) $parts[] = $row->akhiran;

        return implode('/', $parts);
    }
}
