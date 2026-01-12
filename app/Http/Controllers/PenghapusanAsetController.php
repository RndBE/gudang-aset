<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aset;
use App\Models\PenghapusanAset;
use Illuminate\Support\Str;

class PenghapusanAsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');
        $status = $request->query('status');

        $data = PenghapusanAset::query()
            ->with('aset')
            ->where('instansi_id', auth()->user()->instansi_id)
            ->when($q, function ($query) use ($q) {
                $query->where('nomor_penghapusan', 'like', "%$q%")
                    ->orWhereHas('aset', function ($qa) use ($q) {
                        $qa->where('tag_aset', 'like', "%$q%")
                            ->orWhere('no_serial', 'like', "%$q%");
                    });
            })
            ->when($status, fn($query) => $query->where('status', $status))
            ->latest('dibuat_pada')
            ->paginate(15)
            ->withQueryString();

        return view('penghapusan_aset.index', compact('data', 'q', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $aset = Aset::query()
            ->where('instansi_id', auth()->user()->instansi_id)
            ->where('status_siklus', '!=', 'dihapus')
            ->orderBy('tag_aset')
            ->get();

        $nomor = 'DEL-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));

        return view('penghapusan_aset.create', compact('aset', 'nomor'));
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
    public function show(PenghapusanAset $penghapusan_aset)
    {
        $penghapusan_aset->load('aset', 'disetujui', 'dibuat', 'instansi');
        return view('penghapusan_aset.show', ['data' => $penghapusan_aset]);
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
