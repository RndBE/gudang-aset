<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WarehouseApiController;

Route::get('/lookup/barang', [WarehouseApiController::class, 'lookupBarang']);
Route::get('/lookup/gudang', [WarehouseApiController::class, 'lookupGudang']);
Route::get('/lookup/lokasi', [WarehouseApiController::class, 'lookupLokasi']);
Route::get('/lookup/pemasok', [WarehouseApiController::class, 'lookupPemasok']);
Route::get('/lookup/unit', [WarehouseApiController::class, 'lookupUnit']);

Route::get('/barang', [WarehouseApiController::class, 'barangList']);
Route::get('/gudang', [WarehouseApiController::class, 'gudangList']);
Route::get('/lokasi_gudang', [WarehouseApiController::class, 'lokasiGudangList']);
Route::get('/pemasok', [WarehouseApiController::class, 'pemasokList']);

Route::get('/po', [WarehouseApiController::class, 'poList']);
Route::get('/penerimaan', [WarehouseApiController::class, 'penerimaanList']);
Route::get('/qc', [WarehouseApiController::class, 'qcList']);

Route::get('/stok/saldo', [WarehouseApiController::class, 'stokSaldo']);
Route::get('/stok/mutasi', [WarehouseApiController::class, 'stokMutasi']);

Route::get('/permintaan', [WarehouseApiController::class, 'permintaanList']);
Route::get('/pengeluaran', [WarehouseApiController::class, 'pengeluaranList']);
Route::get('/aset', [WarehouseApiController::class, 'asetList']);

Route::get('/approval/pending', [WarehouseApiController::class, 'approvalPending']);
