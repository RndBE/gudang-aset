<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\PeranController;
use App\Http\Controllers\RbacController;
use App\Http\Controllers\UnitOrganisasiController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\LokasiGudangController;
use App\Http\Controllers\SatuanBarangController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PemasokController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\AsetController;


Route::get('/login', [AuthController::class, 'formLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::resource('pengguna', PenggunaController::class)
        ->only(['index'])
        ->middleware('izin:pengguna.lihat|pengguna.kelola');

    Route::resource('pengguna', PenggunaController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->middleware('izin:pengguna.kelola');


    Route::resource('unit-organisasi', UnitOrganisasiController::class)
        ->only(['index'])
        ->middleware('izin:unit_org.lihat|unit_org.kelola');

    Route::resource('unit-organisasi', UnitOrganisasiController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->middleware('izin:unit_org.kelola');


    Route::resource('peran', PeranController::class)
        ->only(['index'])
        ->middleware('izin:peran.lihat|peran.kelola');

    Route::resource('peran', PeranController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->middleware('izin:peran.kelola');


    Route::resource('izin', IzinController::class)
        ->only(['index'])
        ->middleware('izin:izin.lihat|izin.kelola');

    Route::resource('izin', IzinController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->middleware('izin:izin.kelola');


    Route::resource('instansi', InstansiController::class)
        ->only(['index'])
        ->middleware('izin:instansi.lihat|instansi.kelola');

    Route::resource('instansi', InstansiController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->middleware('izin:instansi.kelola');

    Route::resource('gudang', GudangController::class)
        ->only(['index'])
        ->middleware('izin:gudang.lihat');

    Route::resource('gudang', GudangController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->middleware('izin:gudang.kelola');


    Route::resource('lokasi-gudang', LokasiGudangController::class)
        ->only(['index'])
        ->middleware('izin:lokasi_gudang.lihat');

    Route::resource('lokasi-gudang', LokasiGudangController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->middleware('izin:lokasi_gudang.kelola');


    Route::resource('satuan-barang', SatuanBarangController::class)
        ->only(['index'])
        ->middleware('izin:satuan_barang.lihat');

    Route::resource('satuan-barang', SatuanBarangController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->middleware('izin:satuan_barang.kelola');


    Route::resource('kategori-barang', KategoriBarangController::class)
        ->only(['index'])
        ->middleware('izin:kategori_barang.lihat');

    Route::resource('kategori-barang', KategoriBarangController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->middleware('izin:kategori_barang.kelola');


    Route::resource('barang', BarangController::class)
        ->only(['index'])
        ->middleware('izin:barang.lihat');

    Route::resource('barang', BarangController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->middleware('izin:barang.kelola');


    Route::resource('pemasok', PemasokController::class)
        ->only(['index'])
        ->middleware('izin:pemasok.lihat');

    Route::resource('pemasok', PemasokController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->middleware('izin:pemasok.kelola');
    Route::get('/rbac', [RbacController::class, 'index'])
        ->name('rbac.index')
        ->middleware('izin:rbac.lihat|rbac.kelola');

    Route::post('/rbac/peran/{peran}/izin', [RbacController::class, 'simpanIzinPeran'])
        ->name('rbac.peran.izin')
        ->middleware('izin:rbac.kelola');

    Route::post('/rbac/pengguna/{pengguna}/peran', [RbacController::class, 'simpanPeranPengguna'])
        ->name('rbac.pengguna.peran')
        ->middleware('izin:rbac.kelola');

    Route::resource('aset', AsetController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->middleware('izin:aset.lihat|aset.kelola');

    Route::get('aset/{aset}/penghapusan', [AsetController::class, 'penghapusanForm'])
        ->name('aset.penghapusan.form')
        ->middleware('izin:penghapusan_aset.lihat|penghapusan_aset.kelola');

    Route::post('aset/{aset}/penghapusan', [AsetController::class, 'penghapusanStore'])
        ->name('aset.penghapusan.store')
        ->middleware('izin:penghapusan_aset.kelola');
});
