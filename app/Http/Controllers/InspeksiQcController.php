<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\InspeksiQc;
use App\Models\InspeksiQcDetail;
use App\Models\Penerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InspeksiQcController extends Controller
{
    public function index(Request $request)
    {

        $instansiId = Auth::user()->instansi_id;

        $q = InspeksiQc::query()
            ->with(['penerimaan'])
            ->whereHas('penerimaan', fn($x) => $x->where('instansi_id', $instansiId))
            ->orderByDesc('id');

        $data = $q->paginate(15)->withQueryString();

        return view('inspeksi_qc.index', compact('data'));
    }

    public function create(Request $request)
    {

        $instansiId = Auth::user()->instansi_id;

        $penerimaanId = (int) $request->input('penerimaan_id');
        $penerimaan = Penerimaan::with(['detail.barang', 'qc'])->where('instansi_id', $instansiId)->findOrFail($penerimaanId);

        if ($penerimaan->qc) {
            return redirect()->route('inspeksi-qc.edit', $penerimaan->qc)->with('ok', 'QC sudah ada.');
        }

        return view('inspeksi_qc.form', [
            'mode' => 'create',
            'row' => new InspeksiQc([
                'penerimaan_id' => $penerimaan->id,
                'nomor_qc' => 'QC-' . now()->format('YmdHis') . '-' . $penerimaan->id,
                'tanggal_qc' => now()->toDateString(),
                'status' => 'menunggu',
            ]),
            'penerimaan' => $penerimaan,
            'detail' => $penerimaan->detail,
            'qcDetail' => collect(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'penerimaan_id' => ['required', 'integer', 'exists:penerimaan,id'],
            'nomor_qc' => ['required', 'string', 'max:120'],
            'tanggal_qc' => ['required', 'date'],
            'status' => ['required', 'in:menunggu,lulus,gagal,sebagian'],
            'ringkasan' => ['nullable', 'string'],
            'penerimaan_detail_id' => ['required', 'array', 'min:1'],
            'penerimaan_detail_id.*' => ['required', 'integer', 'exists:penerimaan_detail,id'],
            'hasil' => ['required', 'array'],
            'hasil.*' => ['required', 'in:menunggu,lulus,gagal'],
            'qty_diterima' => ['required', 'array'],
            'qty_diterima.*' => ['required', 'numeric', 'min:0'],
            'qty_ditolak' => ['required', 'array'],
            'qty_ditolak.*' => ['required', 'numeric', 'min:0'],
            'catatan_cacat' => ['nullable', 'array'],
            'catatan_cacat.*' => ['nullable', 'string'],
        ]);


        $instansiId = Auth::user()->instansi_id;

        $penerimaan = Penerimaan::where('instansi_id', $instansiId)->findOrFail($data['penerimaan_id']);

        return DB::transaction(function () use ($data, $penerimaan) {
            $qc = InspeksiQc::create([
                'penerimaan_id' => $penerimaan->id,
                'nomor_qc' => $data['nomor_qc'],
                'tanggal_qc' => $data['tanggal_qc'],
                'pemeriksa_id' => Auth::user()->id,
                'status' => $data['status'],
                'ringkasan' => $data['ringkasan'] ?? null,
            ]);

            foreach ($data['penerimaan_detail_id'] as $i => $pdid) {
                InspeksiQcDetail::create([
                    'inspeksi_qc_id' => $qc->id,
                    'penerimaan_detail_id' => $pdid,
                    'hasil' => $data['hasil'][$i],
                    'catatan_cacat' => $data['catatan_cacat'][$i] ?? null,
                    'qty_diterima' => (float) $data['qty_diterima'][$i],
                    'qty_ditolak' => (float) $data['qty_ditolak'][$i],
                ]);
            }

            $penerimaan->update(['status' => 'qc_selesai']);

            return redirect()->route('inspeksi-qc.edit', $qc)->with('ok', 'QC tersimpan.');
        });
    }

    public function edit(InspeksiQc $inspeksi_qc)
    {

        $instansiId = Auth::user()->instansi_id;
        $inspeksi_qc->load(['penerimaan.detail.barang', 'detail']);

        abort_unless($inspeksi_qc->penerimaan->instansi_id === $instansiId, 404);

        $qcDetail = $inspeksi_qc->detail->keyBy('penerimaan_detail_id');

        return view('inspeksi_qc.form', [
            'mode' => 'edit',
            'row' => $inspeksi_qc,
            'penerimaan' => $inspeksi_qc->penerimaan,
            'detail' => $inspeksi_qc->penerimaan->detail,
            'qcDetail' => $qcDetail,
        ]);
    }

    public function update(Request $request, InspeksiQc $inspeksi_qc)
    {

        $instansiId = Auth::user()->instansi_id;
        $inspeksi_qc->load('penerimaan');
        abort_unless($inspeksi_qc->penerimaan->instansi_id === $instansiId, 404);

        $data = $request->validate([
            'nomor_qc' => ['required', 'string', 'max:120'],
            'tanggal_qc' => ['required', 'date'],
            'status' => ['required', 'in:menunggu,lulus,gagal,sebagian'],
            'ringkasan' => ['nullable', 'string'],
            'penerimaan_detail_id' => ['required', 'array', 'min:1'],
            'penerimaan_detail_id.*' => ['required', 'integer', 'exists:penerimaan_detail,id'],
            'hasil' => ['required', 'array'],
            'hasil.*' => ['required', 'in:menunggu,lulus,gagal'],
            'qty_diterima' => ['required', 'array'],
            'qty_diterima.*' => ['required', 'numeric', 'min:0'],
            'qty_ditolak' => ['required', 'array'],
            'qty_ditolak.*' => ['required', 'numeric', 'min:0'],
            'catatan_cacat' => ['nullable', 'array'],
            'catatan_cacat.*' => ['nullable', 'string'],
        ]);

        return DB::transaction(function () use ($data, $inspeksi_qc) {
            $inspeksi_qc->update([
                'nomor_qc' => $data['nomor_qc'],
                'tanggal_qc' => $data['tanggal_qc'],
                'status' => $data['status'],
                'ringkasan' => $data['ringkasan'] ?? null,
            ]);

            $inspeksi_qc->detail()->delete();

            foreach ($data['penerimaan_detail_id'] as $i => $pdid) {
                InspeksiQcDetail::create([
                    'inspeksi_qc_id' => $inspeksi_qc->id,
                    'penerimaan_detail_id' => $pdid,
                    'hasil' => $data['hasil'][$i],
                    'catatan_cacat' => $data['catatan_cacat'][$i] ?? null,
                    'qty_diterima' => (float) $data['qty_diterima'][$i],
                    'qty_ditolak' => (float) $data['qty_ditolak'][$i],
                ]);
            }

            $inspeksi_qc->penerimaan()->update(['status' => 'qc_selesai']);

            return back()->with('ok', 'QC diperbarui.');
        });
    }
}
