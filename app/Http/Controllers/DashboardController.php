<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $totalInstansi = DB::table('instansi')->count();
        $totalGudang = DB::table('gudang')->count();
        $totalBarang = DB::table('barang')->count();
        $totalPemasok = DB::table('pemasok')->count();

        $totalAset = DB::table('aset')->count();
        $asetDitugaskan = DB::table('penugasan_aset')->count();
        $asetDipinjamkan = DB::table('peminjaman_aset')->count();
        $asetDihapus = DB::table('penghapusan_aset')->count();

        $year = (int) ($request->get('year') ?: now()->year);
        $mode = $request->get('mode') === 'monthly' ? 'monthly' : 'weekly';

        if ($mode === 'monthly') {
            $start = Carbon::create($year, 1, 1)->startOfDay();
            $end = Carbon::create($year, 12, 31)->endOfDay();

            $penerimaan = DB::table('penerimaan')
                ->join('penerimaan_detail', 'penerimaan_detail.penerimaan_id', '=', 'penerimaan.id')
                ->where('penerimaan.instansi_id', auth()->user()->instansi_id)
                ->whereBetween('penerimaan.tanggal_penerimaan', [$start, $end])
                ->whereIn('penerimaan.status', ['diterima', 'diposting'])
                ->selectRaw('MONTH(tanggal_penerimaan) as m, SUM(penerimaan_detail.qty_diterima) as total_qty')
                ->groupByRaw('MONTH(tanggal_penerimaan)')
                ->pluck('total_qty', 'm');

            $pengeluaran = DB::table('pengeluaran')
                ->join('pengeluaran_detail', 'pengeluaran_detail.pengeluaran_id', '=', 'pengeluaran.id')
                ->where('pengeluaran.instansi_id', auth()->user()->instansi_id)
                ->whereBetween('pengeluaran.tanggal_pengeluaran', [$start, $end])
                ->whereIn('pengeluaran.status', ['dikeluarkan', 'diposting'])
                ->selectRaw('MONTH(tanggal_pengeluaran) as m, SUM(pengeluaran_detail.qty) as total_qty')
                ->groupByRaw('MONTH(tanggal_pengeluaran)')
                ->pluck('total_qty', 'm');

            $labels = [];
            $in = [];
            $out = [];
            for ($m = 1; $m <= 12; $m++) {
                $labels[] = Carbon::create($year, $m, 1)->format('M');
                $in[] = (float) ($penerimaan[$m] ?? 0);
                $out[] = (float) ($pengeluaran[$m] ?? 0);
            }
            // } else {
            //     $start = now()->startOfMonth()->startOfDay();
            //     $end = now()->endOfMonth()->endOfDay();

            //     $startDate = $start->toDateString();
            //     $endDate = $end->toDateString();

            //     $penerimaanDaily = DB::table('penerimaan')
            //         ->leftJoin('penerimaan_detail', 'penerimaan_detail.penerimaan_id', '=', 'penerimaan.id')
            //         ->where('penerimaan.instansi_id', auth()->user()->instansi_id)
            //         ->whereIn('penerimaan.status', ['diterima', 'diposting'])
            //         ->whereDate('penerimaan.tanggal_penerimaan', '>=', $startDate)
            //         ->whereDate('penerimaan.tanggal_penerimaan', '<=', $endDate)
            //         ->selectRaw('DATE(penerimaan.tanggal_penerimaan) as tgl, COALESCE(SUM(penerimaan_detail.qty_diterima),0) as total_qty')
            //         ->groupByRaw('DATE(penerimaan.tanggal_penerimaan)')
            //         ->orderBy('tgl')
            //         ->pluck('total_qty', 'tgl');

            //     $pengeluaranDaily = DB::table('pengeluaran')
            //         ->leftJoin('pengeluaran_detail', 'pengeluaran_detail.pengeluaran_id', '=', 'pengeluaran.id')
            //         ->where('pengeluaran.instansi_id', auth()->user()->instansi_id)
            //         ->whereIn('pengeluaran.status', ['dikeluarkan', 'diposting'])
            //         ->whereDate('pengeluaran.tanggal_pengeluaran', '>=', $startDate)
            //         ->whereDate('pengeluaran.tanggal_pengeluaran', '<=', $endDate)
            //         ->selectRaw('DATE(pengeluaran.tanggal_pengeluaran) as tgl, COALESCE(SUM(pengeluaran_detail.qty),0) as total_qty')
            //         ->groupByRaw('DATE(pengeluaran.tanggal_pengeluaran)')
            //         ->orderBy('tgl')
            //         ->pluck('total_qty', 'tgl');

            //     $labels = [];
            //     $in = [];
            //     $out = [];

            //     $daysInMonth = $start->daysInMonth;
            //     for ($i = 0; $i < $daysInMonth; $i++) {
            //         $d = $start->copy()->addDays($i)->toDateString();
            //         $labels[] = \Carbon\Carbon::parse($d)->format('d M');
            //         $in[] = (float) ($penerimaanDaily[$d] ?? 0);
            //         $out[] = (float) ($pengeluaranDaily[$d] ?? 0);
            //     }
            // }

            // $yearOptions = range(now()->year - 5, now()->year + 1);
        } else {
            $month = (int) ($request->get('month') ?: now()->month);

            $start = Carbon::create($year, $month, 1)->startOfDay();
            $end = $start->copy()->endOfMonth()->endOfDay();

            $penerimaanDaily = DB::table('penerimaan')
                ->leftJoin('penerimaan_detail', 'penerimaan_detail.penerimaan_id', '=', 'penerimaan.id')
                ->where('penerimaan.instansi_id', auth()->user()->instansi_id)
                ->whereBetween('penerimaan.tanggal_penerimaan', [$start, $end])
                ->whereIn('penerimaan.status', ['diterima', 'diposting'])
                ->selectRaw('DATE(penerimaan.tanggal_penerimaan) as tgl, COALESCE(SUM(penerimaan_detail.qty_diterima),0) as total_qty')
                ->groupByRaw('DATE(penerimaan.tanggal_penerimaan)')
                ->orderBy('tgl')
                ->pluck('total_qty', 'tgl');

            $pengeluaranDaily = DB::table('pengeluaran')
                ->leftJoin('pengeluaran_detail', 'pengeluaran_detail.pengeluaran_id', '=', 'pengeluaran.id')
                ->where('pengeluaran.instansi_id', auth()->user()->instansi_id)
                ->whereBetween('pengeluaran.tanggal_pengeluaran', [$start, $end])
                ->whereIn('pengeluaran.status', ['dikeluarkan', 'diposting'])
                ->selectRaw('DATE(pengeluaran.tanggal_pengeluaran) as tgl, COALESCE(SUM(pengeluaran_detail.qty),0) as total_qty')
                ->groupByRaw('DATE(pengeluaran.tanggal_pengeluaran)')
                ->orderBy('tgl')
                ->pluck('total_qty', 'tgl');

            $labels = [];
            $in = [];
            $out = [];

            $daysInMonth = $start->daysInMonth;
            for ($i = 0; $i < $daysInMonth; $i++) {
                $d = $start->copy()->addDays($i)->toDateString();
                $labels[] = Carbon::parse($d)->format('d M');
                $in[] = (float) ($penerimaanDaily[$d] ?? 0);
                $out[] = (float) ($pengeluaranDaily[$d] ?? 0);
            }
        }

        $yearOptions = collect(range(now()->year - 5, now()->year + 1))->values()->all();
        $monthOptions = range(1, 12);

        $topN = 10;
        $instansiId = auth()->user()->instansi_id;

        $rows = DB::table('barang')
            ->join('kategori_barang', 'kategori_barang.id', '=', 'barang.kategori_id')
            ->where('barang.instansi_id', $instansiId)
            ->where('barang.status', 'aktif')
            ->selectRaw('kategori_barang.nama as kategori, COUNT(DISTINCT barang.id) as total_barang')
            ->groupBy('kategori_barang.nama')
            ->orderByDesc('total_barang')
            ->get();

        // $top = $rows->take($topN);
        // $lainnya = (float) $rows->skip($topN)->sum('qty');

        $pieLabels = $rows->pluck('kategori')->values()->all();
        $pieValues = $rows->pluck('total_barang')->map(fn($v) => (float)$v)->values()->all();

        // if ($lainnya > 0) {
        //     $pieLabels[] = 'Lainnya';
        //     $pieValues[] = $lainnya;
        // }
        $donut = DB::table('pergerakan_stok as ps')
            ->leftJoin('detail_pergerakan_stok as dps', 'dps.pergerakan_stok_id', '=', 'ps.id')
            ->where('ps.instansi_id', $instansiId)
            ->where('ps.status', 'diposting')
            ->whereIn('ps.tipe_referensi', ['penerimaan', 'pengeluaran',])
            // ->selectRaw('ps.tipe_referensi as tipe, COALESCE(SUM(dps.qty),0) as total_qty')
            ->selectRaw('ps.tipe_referensi as tipe, COUNT(DISTINCT ps.id) as total_qty')
            ->groupBy('ps.tipe_referensi')
            ->pluck('total_qty', 'tipe');

        $donutLabels = ['Penerimaan', 'Pengeluaran'];
        $donutValues = [
            (float) ($donut['penerimaan'] ?? 0),
            (float) ($donut['pengeluaran'] ?? 0),
        ];
        return view('dashboard', compact(
            'totalInstansi',
            'totalGudang',
            'totalBarang',
            'totalPemasok',
            'totalAset',
            'asetDitugaskan',
            'asetDipinjamkan',
            'asetDihapus',
            'labels',
            'in',
            'out',
            'year',
            'mode',
            'yearOptions',
            'pieLabels',
            'pieValues',
            'donutLabels',
            'donutValues'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
