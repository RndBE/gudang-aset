<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\DetailPergerakanStok;
use App\Models\Gudang;
use App\Models\LokasiGudang;
use App\Models\Penerimaan;
use App\Models\PenerimaanDetail;
use App\Models\PergerakanStok;
use App\Models\PesananPembelian;
use App\Models\SaldoStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    public function index(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $q = Penerimaan::query()
            ->with(['gudang', 'pesananPembelian'])
            ->where('instansi_id', $instansiId)
            ->orderByDesc('id');

        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }

        $data = $q->paginate(15)->withQueryString();

        return view('penerimaan.index', compact('data'));
    }

    public function create(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $gudang = Gudang::where('instansi_id', $instansiId)->where('status', 'aktif')->orderBy('nama')->get();

        $po = null;
        $poDetail = collect();

        if ($request->filled('pesanan_pembelian_id')) {
            $po = PesananPembelian::with('detail.barang')
                ->where('instansi_id', $instansiId)
                ->findOrFail((int) $request->input('pesanan_pembelian_id'));

            $poDetail = $po->detail;
        }

        return view('penerimaan.form', [
            'mode' => 'create',
            'row' => new Penerimaan([
                'tanggal_penerimaan' => now()->toDateString(),
                'status' => 'draft',
                'pesanan_pembelian_id' => $po?->id,
                'pemasok_id' => $po?->pemasok_id,
            ]),
            'gudang' => $gudang,
            'po' => $po,
            'poDetail' => $poDetail,
            'detail' => collect(),
        ]);
    }

    public function store(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $data = $request->validate([
            'gudang_id' => ['required', 'integer', 'exists:gudang,id'],
            'pesanan_pembelian_id' => ['nullable', 'integer', 'exists:pesanan_pembelian,id'],
            'nomor_penerimaan' => ['required', 'string', 'max:120'],
            'tanggal_penerimaan' => ['required', 'date'],
            'catatan' => ['nullable', 'string'],
            'barang_id' => ['required', 'array', 'min:1'],
            'barang_id.*' => ['required', 'integer', 'exists:barang,id'],
            'po_detail_id' => ['nullable', 'array'],
            'po_detail_id.*' => ['nullable', 'integer', 'exists:pesanan_pembelian_detail,id'],
            'qty_diterima' => ['required', 'array'],
            'qty_diterima.*' => ['required', 'numeric', 'min:0.0001'],
            'no_lot' => ['nullable', 'array'],
            'no_lot.*' => ['nullable', 'string', 'max:120'],
            'tanggal_kedaluwarsa' => ['nullable', 'array'],
            'tanggal_kedaluwarsa.*' => ['nullable', 'date'],
            'biaya_satuan' => ['required', 'array'],
            'biaya_satuan.*' => ['required', 'numeric', 'min:0'],
            'lokasi_id' => ['nullable', 'array'],
            'lokasi_id.*' => ['nullable', 'integer', 'exists:lokasi_gudang,id'],
            'catatan_detail' => ['nullable', 'array'],
            'catatan_detail.*' => ['nullable', 'string'],
        ]);

        return DB::transaction(function () use ($data, $instansiId) {
            $penerimaan = Penerimaan::create([
                'instansi_id' => $instansiId,
                'gudang_id' => $data['gudang_id'],
                'pemasok_id' => null,
                'pesanan_pembelian_id' => $data['pesanan_pembelian_id'] ?? null,
                'nomor_penerimaan' => $data['nomor_penerimaan'],
                'tanggal_penerimaan' => $data['tanggal_penerimaan'],
                'diterima_oleh' =>  Auth::user()->id,
                'status' => 'diterima',
                'catatan' => $data['catatan'] ?? null,
                'dibuat_oleh' =>  Auth::user()->id,
            ]);

            foreach ($data['barang_id'] as $i => $barangId) {
                PenerimaanDetail::create([
                    'penerimaan_id' => $penerimaan->id,
                    'barang_id' => $barangId,
                    'po_detail_id' => $data['po_detail_id'][$i] ?? null,
                    'qty_diterima' => (float) $data['qty_diterima'][$i],
                    'no_lot' => $data['no_lot'][$i] ?? null,
                    'tanggal_kedaluwarsa' => $data['tanggal_kedaluwarsa'][$i] ?? null,
                    'biaya_satuan' => (float) $data['biaya_satuan'][$i],
                    'lokasi_id' => $data['lokasi_id'][$i] ?? null,
                    'catatan' => $data['catatan_detail'][$i] ?? null,
                ]);
            }

            return redirect()->route('penerimaan.edit', $penerimaan)->with('ok', 'Penerimaan dibuat.');
        });
    }

    public function edit(Penerimaan $penerimaan)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($penerimaan->instansi_id === $instansiId, 404);

        $gudang = Gudang::where('instansi_id', $instansiId)->where('status', 'aktif')->orderBy('nama')->get();
        $detail = $penerimaan->detail()->with('barang')->orderBy('id')->get();

        $po = null;
        if ($penerimaan->pesanan_pembelian_id) {
            $po = PesananPembelian::with('detail.barang')->where('instansi_id', $instansiId)->find($penerimaan->pesanan_pembelian_id);
        }

        return view('penerimaan.form', [
            'mode' => 'edit',
            'row' => $penerimaan,
            'gudang' => $gudang,
            'po' => $po,
            'poDetail' => $po?->detail ?? collect(),
            'detail' => $detail,
        ]);
    }

    public function update(Request $request, Penerimaan $penerimaan)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($penerimaan->instansi_id === $instansiId, 404);

        if (in_array($penerimaan->status, ['diposting', 'dibatalkan'], true)) {
            return back()->withErrors(['status' => 'Penerimaan tidak bisa diubah pada status ini.']);
        }

        $data = $request->validate([
            'gudang_id' => ['required', 'integer', 'exists:gudang,id'],
            'nomor_penerimaan' => ['required', 'string', 'max:120'],
            'tanggal_penerimaan' => ['required', 'date'],
            'catatan' => ['nullable', 'string'],
            'barang_id' => ['required', 'array', 'min:1'],
            'barang_id.*' => ['required', 'integer', 'exists:barang,id'],
            'po_detail_id' => ['nullable', 'array'],
            'po_detail_id.*' => ['nullable', 'integer', 'exists:pesanan_pembelian_detail,id'],
            'qty_diterima' => ['required', 'array'],
            'qty_diterima.*' => ['required', 'numeric', 'min:0.0001'],
            'no_lot' => ['nullable', 'array'],
            'no_lot.*' => ['nullable', 'string', 'max:120'],
            'tanggal_kedaluwarsa' => ['nullable', 'array'],
            'tanggal_kedaluwarsa.*' => ['nullable', 'date'],
            'biaya_satuan' => ['required', 'array'],
            'biaya_satuan.*' => ['required', 'numeric', 'min:0'],
            'lokasi_id' => ['nullable', 'array'],
            'lokasi_id.*' => ['nullable', 'integer', 'exists:lokasi_gudang,id'],
            'catatan_detail' => ['nullable', 'array'],
            'catatan_detail.*' => ['nullable', 'string'],
        ]);

        return DB::transaction(function () use ($data, $penerimaan) {
            $penerimaan->update([
                'gudang_id' => $data['gudang_id'],
                'nomor_penerimaan' => $data['nomor_penerimaan'],
                'tanggal_penerimaan' => $data['tanggal_penerimaan'],
                'catatan' => $data['catatan'] ?? null,
            ]);

            $penerimaan->detail()->delete();

            foreach ($data['barang_id'] as $i => $barangId) {
                PenerimaanDetail::create([
                    'penerimaan_id' => $penerimaan->id,
                    'barang_id' => $barangId,
                    'po_detail_id' => $data['po_detail_id'][$i] ?? null,
                    'qty_diterima' => (float) $data['qty_diterima'][$i],
                    'no_lot' => $data['no_lot'][$i] ?? null,
                    'tanggal_kedaluwarsa' => $data['tanggal_kedaluwarsa'][$i] ?? null,
                    'biaya_satuan' => (float) $data['biaya_satuan'][$i],
                    'lokasi_id' => $data['lokasi_id'][$i] ?? null,
                    'catatan' => $data['catatan_detail'][$i] ?? null,
                ]);
            }

            return redirect()->route('penerimaan.edit', $penerimaan)->with('ok', 'Penerimaan diperbarui.');
        });
    }

    public function qcMulai(Penerimaan $penerimaan)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($penerimaan->instansi_id === $instansiId, 404);

        if (in_array($penerimaan->status, ['diposting', 'dibatalkan'], true)) {
            return back()->withErrors(['status' => 'Tidak bisa mulai QC pada status ini.']);
        }

        $penerimaan->update(['status' => 'qc_menunggu']);

        return redirect()->route('inspeksi-qc.create', ['penerimaan_id' => $penerimaan->id])->with('ok', 'QC dimulai.');
    }

    public function postingStokMasuk(Penerimaan $penerimaan)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($penerimaan->instansi_id === $instansiId, 404);

        if ($penerimaan->status === 'diposting') {
            return back()->with('ok', 'Sudah diposting.');
        }

        $penerimaan->load(['detail', 'qc.detail.penerimaanDetail']);

        $qc = $penerimaan->qc;

        if ($qc && !in_array($qc->status, ['lulus', 'sebagian'], true)) {
            return back()->withErrors(['status' => 'QC belum selesai atau tidak memenuhi untuk diposting.']);
        }

        return DB::transaction(function () use ($penerimaan, $qc, $instansiId) {
            $nomor = 'PG-' . now()->format('YmdHis') . '-' . $penerimaan->id;

            $pergerakan = PergerakanStok::create([
                'instansi_id' => $instansiId,
                'nomor_pergerakan' => $nomor,
                'jenis_pergerakan' => 'penerimaan',
                'tipe_referensi' => 'penerimaan',
                'id_referensi' => $penerimaan->id,
                'tanggal_pergerakan' => now(),
                'gudang_id' => $penerimaan->gudang_id,
                'catatan' => $penerimaan->catatan,
                'diposting_oleh' =>  Auth::user()->id,
                'status' => 'diposting',
                'dibuat_oleh' =>  Auth::user()->id,
            ]);

            $acceptedMap = [];
            if ($qc) {
                foreach ($qc->detail as $qcd) {
                    $accepted = max(0, (float) $qcd->qty_diterima);
                    $acceptedMap[$qcd->penerimaan_detail_id] = [
                        'accepted' => $accepted,
                        'hasil' => $qcd->hasil,
                    ];
                }
            }

            foreach ($penerimaan->detail as $d) {
                $qty = (float) $d->qty_diterima;

                if ($qc) {
                    $m = $acceptedMap[$d->id] ?? null;
                    if (!$m) {
                        continue;
                    }
                    if (!in_array($m['hasil'], ['lulus'], true)) {
                        continue;
                    }
                    $qty = (float) $m['accepted'];
                }

                if ($qty <= 0) {
                    continue;
                }

                DetailPergerakanStok::create([
                    'pergerakan_stok_id' => $pergerakan->id,
                    'barang_id' => $d->barang_id,
                    'dari_gudang_id' => null,
                    'dari_lokasi_id' => null,
                    'ke_gudang_id' => $penerimaan->gudang_id,
                    'ke_lokasi_id' => $d->lokasi_id,
                    'no_lot' => $d->no_lot,
                    'tanggal_kedaluwarsa' => $d->tanggal_kedaluwarsa,
                    'qty' => $qty,
                    'biaya_satuan' => (float) $d->biaya_satuan,
                ]);

                $saldoQ = SaldoStok::query()
                    ->where('instansi_id', $instansiId)
                    ->where('gudang_id', $penerimaan->gudang_id)
                    ->where('barang_id', $d->barang_id);

                if ($d->lokasi_id) $saldoQ->where('lokasi_id', $d->lokasi_id);
                else $saldoQ->whereNull('lokasi_id');

                if ($d->no_lot !== null) $saldoQ->where('no_lot', $d->no_lot);
                else $saldoQ->whereNull('no_lot');

                if ($d->tanggal_kedaluwarsa !== null) $saldoQ->whereDate('tanggal_kedaluwarsa', $d->tanggal_kedaluwarsa);
                else $saldoQ->whereNull('tanggal_kedaluwarsa');

                $saldo = $saldoQ->lockForUpdate()->first();

                if (!$saldo) {
                    $saldo = SaldoStok::create([
                        'instansi_id' => $instansiId,
                        'gudang_id' => $penerimaan->gudang_id,
                        'lokasi_id' => $d->lokasi_id,
                        'barang_id' => $d->barang_id,
                        'no_lot' => $d->no_lot,
                        'tanggal_kedaluwarsa' => $d->tanggal_kedaluwarsa,
                        'qty_tersedia' => 0,
                        'qty_dipesan' => 0,
                        'qty_bisa_dipakai' => 0,
                        'pergerakan_terakhir_pada' => now(),
                    ]);
                }

                $saldo->update([
                    'qty_tersedia' => (float) $saldo->qty_tersedia + $qty,
                    'qty_bisa_dipakai' => (float) $saldo->qty_bisa_dipakai + $qty,
                    'pergerakan_terakhir_pada' => now(),
                ]);
            }

            $penerimaan->update(['status' => 'diposting']);

            $po = $penerimaan->pesananPembelian;
            if ($po) {
                $po->load('detail');

                $received = PenerimaanDetail::query()
                    ->whereHas('penerimaan', function ($x) use ($po) {
                        $x->where('pesanan_pembelian_id', $po->id)->where('status', 'diposting');
                    })
                    ->selectRaw('barang_id, SUM(qty_diterima) as qty')
                    ->groupBy('barang_id')
                    ->pluck('qty', 'barang_id');

                $allOk = true;
                $any = false;
                foreach ($po->detail as $pd) {
                    $any = true;
                    $r = (float) ($received[$pd->barang_id] ?? 0);
                    if ($r + 0.0000001 < (float) $pd->qty) {
                        $allOk = false;
                    }
                }

                if ($any) {
                    $po->update(['status' => $allOk ? 'diterima' : 'diterima_sebagian']);
                }
            }

            return back()->with('ok', 'Posting stok masuk berhasil.');
        });
    }
}
