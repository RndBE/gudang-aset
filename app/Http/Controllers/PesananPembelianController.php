<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\Pemasok;
use App\Models\PesananPembelian;
use App\Models\PesananPembelianDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ApprovalService;

class PesananPembelianController extends Controller
{

    public function __construct(private ApprovalService $approvalService) {}

    public function index(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $q = PesananPembelian::query()
            ->with(['pemasok', 'permintaanPersetujuan'])
            ->where('instansi_id', $instansiId)
            ->orderByDesc('id');

        if ($request->filled('status')) {
            $status = (string) $request->input('status');
            $q->where('status', $status);
            // $q->where('status', $request->string('status'));
        }

        if ($request->filled('keyword')) {
            // $kw = $request->string('keyword');
            $kw = trim((string) $request->input('keyword'));
            $q->where(function ($x) use ($kw) {
                $x->where('nomor_po', 'like', "%{$kw}%")
                    ->orWhere('catatan', 'like', "%{$kw}%");
            });
        }

        $data = $q->paginate(15)->withQueryString();

        return view('pesanan_pembelian.index', compact('data'));
    }

    public function create()
    {
        $instansiId = Auth::user()->instansi_id;

        $pemasok = Pemasok::where('instansi_id', $instansiId)->where('status', 'aktif')->orderBy('nama')->get();
        $barang = Barang::where('instansi_id', $instansiId)->where('status', 'aktif')->orderBy('nama')->get();

        return view('pesanan_pembelian.form', [
            'mode' => 'create',
            'row' => new PesananPembelian([
                'tanggal_po' => now()->toDateString(),
                'mata_uang' => 'IDR',
                'status' => 'draft'
            ]),
            'pemasok' => $pemasok,
            'barang' => $barang,
            'detail' => collect(),
        ]);
    }

    public function store(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $data = $request->validate([
            'pemasok_id' => ['required', 'integer', 'exists:pemasok,id'],
            'nomor_po' => ['required', 'string', 'max:120'],
            'tanggal_po' => ['required', 'date'],
            'tanggal_estimasi' => ['nullable', 'date'],
            'mata_uang' => ['required', 'string', 'max:10'],
            'catatan' => ['nullable', 'string'],
            'barang_id' => ['required', 'array', 'min:1'],
            'barang_id.*' => ['required', 'integer', 'exists:barang,id'],
            'qty' => ['required', 'array'],
            'qty.*' => ['required', 'numeric', 'min:0.0001'],
            'harga_satuan' => ['required', 'array'],
            'harga_satuan.*' => ['required', 'numeric', 'min:0'],
            'tarif_pajak' => ['required', 'array'],
            'tarif_pajak.*' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'array'],
            'deskripsi.*' => ['nullable', 'string', 'max:500'],
        ]);

        return DB::transaction(function () use ($data, $instansiId) {
            $rows = [];
            $subtotal = 0;
            $pajak = 0;

            foreach ($data['barang_id'] as $i => $barangId) {
                $qty = (float) $data['qty'][$i];
                $harga = (float) $data['harga_satuan'][$i];
                $tarif = (float) $data['tarif_pajak'][$i];

                $nilaiBaris = $qty * $harga;
                $nilaiPajak = $nilaiBaris * ($tarif / 100);
                $totalBaris = $nilaiBaris + $nilaiPajak;

                $subtotal += $nilaiBaris;
                $pajak += $nilaiPajak;

                $rows[] = [
                    'barang_id' => $barangId,
                    'deskripsi' => $data['deskripsi'][$i] ?? null,
                    'qty' => $qty,
                    'harga_satuan' => $harga,
                    'tarif_pajak' => $tarif,
                    'nilai_pajak' => $nilaiPajak,
                    'total_baris' => $totalBaris,
                ];
            }

            $po = PesananPembelian::create([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' =>  Auth::user()->unit_organisasi_id,
                'pemasok_id' => $data['pemasok_id'],
                'kontrak_id' => null,
                'nomor_po' => $data['nomor_po'],
                'tanggal_po' => $data['tanggal_po'],
                'tanggal_estimasi' => $data['tanggal_estimasi'] ?? null,
                'mata_uang' => $data['mata_uang'],
                'subtotal' => $subtotal,
                'pajak' => $pajak,
                'total' => $subtotal + $pajak,
                'status' => 'draft',
                'catatan' => $data['catatan'] ?? null,
                'dibuat_oleh' => Auth::user()->id,
            ]);

            foreach ($rows as $r) {
                PesananPembelianDetail::create([
                    'pesanan_pembelian_id' => $po->id,
                    ...$r
                ]);
            }

            return redirect()->route('pesanan-pembelian.edit', $po)->with('ok', 'PO berhasil dibuat.');
        });
    }

    public function edit(PesananPembelian $pesanan_pembelian)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($pesanan_pembelian->instansi_id === $instansiId, 404);

        $pemasok = Pemasok::where('instansi_id', $instansiId)->where('status', 'aktif')->orderBy('nama')->get();
        $barang = Barang::where('instansi_id', $instansiId)->where('status', 'aktif')->orderBy('nama')->get();

        $detail = $pesanan_pembelian->detail()->with('barang')->orderBy('id')->get();

        return view('pesanan_pembelian.form', [
            'mode' => 'edit',
            'row' => $pesanan_pembelian,
            'pemasok' => $pemasok,
            'barang' => $barang,
            'detail' => $detail,
        ]);
    }

    public function update(Request $request, PesananPembelian $pesanan_pembelian)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($pesanan_pembelian->instansi_id === $instansiId, 404);

        if (!in_array($pesanan_pembelian->status, ['draft', 'diajukan'], true)) {
            return back()->withErrors(['status' => 'PO tidak bisa diubah pada status ini.']);
        }

        $data = $request->validate([
            'pemasok_id' => ['required', 'integer', 'exists:pemasok,id'],
            'nomor_po' => ['required', 'string', 'max:120'],
            'tanggal_po' => ['required', 'date'],
            'tanggal_estimasi' => ['nullable', 'date'],
            'mata_uang' => ['required', 'string', 'max:10'],
            'catatan' => ['nullable', 'string'],
            'barang_id' => ['required', 'array', 'min:1'],
            'barang_id.*' => ['required', 'integer', 'exists:barang,id'],
            'qty' => ['required', 'array'],
            'qty.*' => ['required', 'numeric', 'min:0.0001'],
            'harga_satuan' => ['required', 'array'],
            'harga_satuan.*' => ['required', 'numeric', 'min:0'],
            'tarif_pajak' => ['required', 'array'],
            'tarif_pajak.*' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'array'],
            'deskripsi.*' => ['nullable', 'string', 'max:500'],
        ]);

        return DB::transaction(function () use ($data, $pesanan_pembelian) {
            $rows = [];
            $subtotal = 0;
            $pajak = 0;

            foreach ($data['barang_id'] as $i => $barangId) {
                $qty = (float) $data['qty'][$i];
                $harga = (float) $data['harga_satuan'][$i];
                $tarif = (float) $data['tarif_pajak'][$i];

                $nilaiBaris = $qty * $harga;
                $nilaiPajak = $nilaiBaris * ($tarif / 100);
                $totalBaris = $nilaiBaris + $nilaiPajak;

                $subtotal += $nilaiBaris;
                $pajak += $nilaiPajak;

                $rows[] = [
                    'barang_id' => $barangId,
                    'deskripsi' => $data['deskripsi'][$i] ?? null,
                    'qty' => $qty,
                    'harga_satuan' => $harga,
                    'tarif_pajak' => $tarif,
                    'nilai_pajak' => $nilaiPajak,
                    'total_baris' => $totalBaris,
                ];
            }

            $pesanan_pembelian->update([
                'pemasok_id' => $data['pemasok_id'],
                'nomor_po' => $data['nomor_po'],
                'tanggal_po' => $data['tanggal_po'],
                'tanggal_estimasi' => $data['tanggal_estimasi'] ?? null,
                'mata_uang' => $data['mata_uang'],
                'subtotal' => $subtotal,
                'pajak' => $pajak,
                'total' => $subtotal + $pajak,
                'catatan' => $data['catatan'] ?? null,
            ]);

            $pesanan_pembelian->detail()->delete();

            foreach ($rows as $r) {
                PesananPembelianDetail::create([
                    'pesanan_pembelian_id' => $pesanan_pembelian->id,
                    ...$r
                ]);
            }

            return redirect()->route('pesanan-pembelian.edit', $pesanan_pembelian)->with('ok', 'PO berhasil diperbarui.');
        });
    }

    public function ajukan(PesananPembelian $pesanan_pembelian)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($pesanan_pembelian->instansi_id === $instansiId, 404);

        if ($pesanan_pembelian->status !== 'draft') {
            return back()->withErrors(['status' => 'Hanya PO draft yang bisa diajukan.']);
        }

        // $pesanan_pembelian->update(['status' => 'diajukan']);

        $permintaan = $this->approvalService->buatPermintaan(
            berlakuUntuk: 'pesanan_pembelian',
            tipeEntitas: 'pesanan_pembelian',
            idEntitas: $pesanan_pembelian->id,
            ringkasan: "Pesanan Pembelian #" . ($pesanan_pembelian->nomor_po ?? $pesanan_pembelian->id)
        );

        $pesanan_pembelian->update([
            'status' => 'diajukan',
            'permintaan_persetujuan_id' => $permintaan->id,
        ]);

        return back()->with('ok', 'PO diajukan dan menunggu persetujuan.');
    }

    public function setujui(PesananPembelian $pesanan_pembelian)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($pesanan_pembelian->instansi_id === $instansiId, 404);

        if ($pesanan_pembelian->status !== 'diajukan') {
            return back()->withErrors(['status' => 'Hanya PO diajukan yang bisa disetujui.']);
        }

        $pesanan_pembelian->update(['status' => 'disetujui']);

        return back()->with('ok', 'PO disetujui.');
    }

    public function batalkan(PesananPembelian $pesanan_pembelian)
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($pesanan_pembelian->instansi_id === $instansiId, 404);

        if (in_array($pesanan_pembelian->status, ['diterima', 'dibatalkan'], true)) {
            return back()->withErrors(['status' => 'PO tidak bisa dibatalkan pada status ini.']);
        }

        $pesanan_pembelian->update(['status' => 'dibatalkan']);

        return back()->with('ok', 'PO dibatalkan.');
    }
}
