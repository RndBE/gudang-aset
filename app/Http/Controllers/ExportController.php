<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\AsetExport;
use App\Exports\BarangExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function barang()
    {
        $instansiId = auth()->user()->instansi_id;
        return Excel::download(new BarangExport($instansiId), 'data barang.xlsx');
    }

    public function aset()
    {
        $instansiId = auth()->user()->instansi_id;
        return Excel::download(new AsetExport($instansiId), 'data aset.xlsx');
    }
}
