<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalInstansi = DB::table('instansi')->count();
        $totalGudang = DB::table('gudang')->count();
        $totalBarang = DB::table('barang')->count();
        $totalPemasok = DB::table('pemasok')->count();

        $totalAset = DB::table('aset')->count();
        $asetDitugaskan = DB::table('penugasan_aset')->count();
        $asetDipinjamkan = DB::table('peminjaman_aset')->count();
        $asetDihapus = DB::table('penghapusan_aset')->count();

        return view('dashboard', compact(
            'totalInstansi',
            'totalGudang',
            'totalBarang',
            'totalPemasok',
            'totalAset',
            'asetDitugaskan',
            'asetDipinjamkan',
            'asetDihapus'
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
