<?php

namespace App\Http\Controllers;

use App\Models\SaldoStok;
use App\Models\Gudang;
use App\Models\Barang;
use Illuminate\Http\Request;

class SaldoStokController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $instansiId = $user->instansi_id;

        $q = trim((string) $request->query('q', ''));
        $gudangId = $request->query('gudang_id');
        $barangId = $request->query('barang_id');

        $query = SaldoStok::query()
            ->with(['gudang', 'lokasi', 'barang'])
            ->where('instansi_id', $instansiId);

        if ($gudangId) $query->where('gudang_id', $gudangId);
        if ($barangId) $query->where('barang_id', $barangId);

        if ($q !== '') {
            $query->whereHas('barang', function ($b) use ($q) {
                $b->where('nama', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        $items = $query
            ->orderBy('gudang_id')
            ->orderBy('lokasi_id')
            ->orderBy('barang_id')
            ->paginate(20)
            ->withQueryString();

        $gudangList = Gudang::query()
            ->where('instansi_id', $instansiId)
            ->orderBy('nama')
            ->get();

        $barangList = Barang::query()
            ->where('instansi_id', $instansiId)
            ->orderBy('nama')
            ->limit(500)
            ->get();

        return view('saldo_stok.index', compact('items', 'gudangList', 'barangList', 'q', 'gudangId', 'barangId'));
    }
}
