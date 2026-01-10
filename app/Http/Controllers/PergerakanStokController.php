<?php

namespace App\Http\Controllers;

use App\Models\PergerakanStok;
use App\Models\DetailPergerakanStok;
use App\Models\SaldoStok;
use App\Models\Penerimaan;
use App\Models\PenerimaanDetail;
use App\Models\Gudang;
use App\Models\LokasiGudang;
use App\Models\UrutanNomorDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PergerakanStokController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $instansiId = $user->instansi_id;

        $q = trim((string) $request->query('q', ''));
        $jenis = $request->query('jenis_pergerakan');
        $status = $request->query('status');

        $query = PergerakanStok::query()
            ->with(['gudang', 'dibuatOleh', 'dipostingOleh'])
            ->where('instansi_id', $instansiId);

        if ($jenis) $query->where('jenis_pergerakan', $jenis);
        if ($status) $query->where('status', $status);

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('nomor_pergerakan', 'like', "%{$q}%")
                    ->orWhere('tipe_referensi', 'like', "%{$q}%");
            });
        }

        $items = $query->orderByDesc('tanggal_pergerakan')->paginate(20)->withQueryString();

        return view('pergerakan_stok.index', compact('items', 'q', 'jenis', 'status'));
    }

    public function show(PergerakanStok $pergerakan_stok)
    {
        $user = auth()->user();
        if ((int) $pergerakan_stok->instansi_id !== (int) $user->instansi_id) abort(404);

        $pergerakan_stok->load([
            'gudang',
            'dibuatOleh',
            'dipostingOleh',
            'detail.barang',
            'detail.keGudang',
            'detail.keLokasi',
            'detail.dariGudang',
            'detail.dariLokasi',
        ]);

        return view('pergerakan_stok.show', compact('pergerakan_stok'));
    }

    public function postingDariPenerimaan(Penerimaan $penerimaan)
    {
        $user = auth()->user();
        if ((int) $penerimaan->instansi_id !== (int) $user->instansi_id) abort(404);

        $penerimaan->load([
            'gudang',
            'details.barang',
            'details.lokasi',
        ]);

        $instansiId = $user->instansi_id;

        $lokasiList = LokasiGudang::query()
            ->where('gudang_id', $penerimaan->gudang_id)
            ->where('status', 'aktif')
            ->orderBy('kode')
            ->get();

        return view('pergerakan_stok.posting_dari_penerimaan', compact('penerimaan', 'lokasiList'));
    }

    public function simpanPostingDariPenerimaan(Request $request, Penerimaan $penerimaan)
    {
        $user = auth()->user();
        $instansiId = $user->instansi_id;

        if ((int) $penerimaan->instansi_id !== (int) $instansiId) abort(404);

        $data = $request->validate([
            'tanggal_pergerakan' => ['required', 'date'],
            'lokasi_id' => ['required', 'array'],
            'lokasi_id.*' => ['nullable', 'integer', 'exists:lokasi_gudang,id'],
        ]);

        return DB::transaction(function () use ($data, $penerimaan, $user, $instansiId) {
            $penerimaan = Penerimaan::query()
                ->where('id', $penerimaan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($penerimaan->status === 'diposting') {
                return redirect()->route('penerimaan.edit', $penerimaan->id)->with('ok', 'Penerimaan sudah diposting.');
            }

            $details = PenerimaanDetail::query()
                ->where('penerimaan_id', $penerimaan->id)
                ->get();

            if ($details->count() === 0) {
                return redirect()->route('penerimaan.edit', $penerimaan->id)->withErrors(['msg' => 'Detail penerimaan kosong.']);
            }

            $nomor = $this->nextNomorDokumen(
                $instansiId,
                $user->unit_organisasi_id,
                'pergerakan_stok_masuk',
                (int) date('Y')
            );

            $pergerakan = PergerakanStok::create([
                'instansi_id' => $instansiId,
                'nomor_pergerakan' => $nomor,
                'jenis_pergerakan' => 'penerimaan',
                'tipe_referensi' => 'penerimaan',
                'id_referensi' => $penerimaan->id,
                'tanggal_pergerakan' => $data['tanggal_pergerakan'],
                'gudang_id' => $penerimaan->gudang_id,
                'catatan' => 'Posting stok masuk dari penerimaan: ' . $penerimaan->nomor_penerimaan,
                'diposting_oleh' => $user->id,
                'status' => 'diposting',
                'dibuat_oleh' => $user->id,
            ]);

            foreach ($details as $d) {
                if ((float) $d->qty_diterima <= 0) continue;

                $lokasiId = $data['lokasi_id'][$d->id] ?? $d->lokasi_id;

                DetailPergerakanStok::create([
                    'pergerakan_stok_id' => $pergerakan->id,
                    'barang_id' => $d->barang_id,
                    'dari_gudang_id' => null,
                    'dari_lokasi_id' => null,
                    'ke_gudang_id' => $penerimaan->gudang_id,
                    'ke_lokasi_id' => $lokasiId,
                    'no_lot' => $d->no_lot,
                    'tanggal_kedaluwarsa' => $d->tanggal_kedaluwarsa,
                    'qty' => $d->qty_diterima,
                    'biaya_satuan' => $d->biaya_satuan,
                ]);

                $existing = SaldoStok::query()->where([
                    'instansi_id' => $instansiId,
                    'gudang_id' => $penerimaan->gudang_id,
                    'lokasi_id' => $lokasiId,
                    'barang_id' => $d->barang_id,
                    'no_lot' => $d->no_lot,
                    'tanggal_kedaluwarsa' => $d->tanggal_kedaluwarsa,
                ])->lockForUpdate()->first();

                if ($existing) {
                    $existing->qty_tersedia = (float) $existing->qty_tersedia + (float) $d->qty_diterima;
                    $existing->qty_bisa_dipakai = (float) $existing->qty_bisa_dipakai + (float) $d->qty_diterima;
                    $existing->pergerakan_terakhir_pada = now();
                    $existing->save();
                } else {
                    SaldoStok::create([
                        'instansi_id' => $instansiId,
                        'gudang_id' => $penerimaan->gudang_id,
                        'lokasi_id' => $lokasiId,
                        'barang_id' => $d->barang_id,
                        'no_lot' => $d->no_lot,
                        'tanggal_kedaluwarsa' => $d->tanggal_kedaluwarsa,
                        'qty_tersedia' => (float) $d->qty_diterima,
                        'qty_dipesan' => 0,
                        'qty_bisa_dipakai' => (float) $d->qty_diterima,
                        'pergerakan_terakhir_pada' => now(),
                    ]);
                }
            }

            $penerimaan->status = 'diposting';
            $penerimaan->save();

            return redirect()->route('pergerakan-stok.show', $pergerakan->id)->with('ok', 'Posting stok masuk berhasil.');
        });
    }

    private function nextNomorDokumen(int $instansiId, ?int $unitOrganisasiId, string $tipeDokumen, int $tahun): string
    {
        $row = UrutanNomorDokumen::query()
            ->where('instansi_id', $instansiId)
            ->where('unit_organisasi_id', $unitOrganisasiId)
            ->where('tipe_dokumen', $tipeDokumen)
            ->where('tahun', $tahun)
            ->lockForUpdate()
            ->first();

        if (!$row) {
            $row = UrutanNomorDokumen::create([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $unitOrganisasiId,
                'tipe_dokumen' => $tipeDokumen,
                'tahun' => $tahun,
                'nomor_terakhir' => 0,
                'awalan' => strtoupper($tipeDokumen) . '/' . $tahun . '/',
                'akhiran' => null,
            ]);
        }

        $row->nomor_terakhir = ((int) $row->nomor_terakhir) + 1;
        $row->save();

        $num = str_pad((string) $row->nomor_terakhir, 5, '0', STR_PAD_LEFT);
        return ($row->awalan ?? '') . $num . ($row->akhiran ?? '');
    }
}
