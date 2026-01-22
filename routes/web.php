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
use App\Http\Controllers\PenghapusanAsetController;
use App\Http\Controllers\PeminjamanAsetController;
use App\Http\Controllers\PenugasanAsetController;
use App\Http\Controllers\RencanaPerawatanController;
use App\Http\Controllers\PerintahKerjaController;

use App\Http\Controllers\PesananPembelianController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\InspeksiQcController;

use App\Http\Controllers\SaldoStokController;
use App\Http\Controllers\PergerakanStokController;

use App\Http\Controllers\AlurPersetujuanController;
use App\Http\Controllers\PermintaanPersetujuanController;

use App\Http\Controllers\LogAuditController;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\ExportController;
use App\Http\Middleware\AuditActivity;

use App\Http\Controllers\Api\WarehouseApiController;

Route::get('/login', [AuthController::class, 'formLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::middleware('auth')->group(function () {
Route::middleware(['auth', AuditActivity::class])->group(function () {
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/export/barang', [ExportController::class, 'barang'])->name('export.barang');
    Route::get('/export/aset', [ExportController::class, 'aset'])->name('export.aset');

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

    Route::get('/barang/import-ocr', [BarangController::class, 'importOcr'])->name('barang.import_ocr');
    Route::post('/barang/import-ocr/scan', [BarangController::class, 'scanOcr'])->name('barang.import_ocr.scan');

    Route::post('/barang/import-ocr', [BarangController::class, 'importOcrStore'])->name('barang.import_ocr.store');
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

    Route::resource('penugasan-aset', PenugasanAsetController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->middleware('izin:penugasan_aset.lihat|penugasan_aset.kelola');

    Route::post('penugasan-aset/{penugasan_aset}/kembalikan', [PenugasanAsetController::class, 'kembalikan'])
        ->name('penugasan-aset.kembalikan')
        ->middleware('izin:penugasan_aset.kelola');

    Route::post('penugasan-aset/{penugasan_aset}/batalkan', [PenugasanAsetController::class, 'batalkan'])
        ->name('penugasan-aset.batalkan')
        ->middleware('izin:penugasan_aset.kelola');

    Route::resource('peminjaman-aset', PeminjamanAsetController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->middleware('izin:peminjaman_aset.lihat|peminjaman_aset.kelola');

    Route::post('peminjaman-aset/{peminjaman_aset}/kembalikan', [PeminjamanAsetController::class, 'kembalikan'])
        ->name('peminjaman-aset.kembalikan')
        ->middleware('izin:peminjaman_aset.kelola');

    Route::post('peminjaman-aset/{peminjaman_aset}/batalkan', [PeminjamanAsetController::class, 'batalkan'])
        ->name('peminjaman-aset.batalkan')
        ->middleware('izin:peminjaman_aset.kelola');

    Route::resource('penghapusan-aset', PenghapusanAsetController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->middleware('izin:penghapusan_aset.lihat|penghapusan_aset.kelola');

    Route::post('penghapusan-aset/{penghapusan_aset}/setujui', [PenghapusanAsetController::class, 'setujui'])
        ->name('penghapusan-aset.setujui')
        ->middleware('izin:penghapusan_aset.kelola');

    Route::post('penghapusan-aset/{penghapusan_aset}/eksekusi', [PenghapusanAsetController::class, 'eksekusi'])
        ->name('penghapusan-aset.eksekusi')
        ->middleware('izin:penghapusan_aset.kelola');

    Route::post('penghapusan-aset/{penghapusan_aset}/batalkan', [PenghapusanAsetController::class, 'batalkan'])
        ->name('penghapusan-aset.batalkan')
        ->middleware('izin:penghapusan_aset.kelola');

    Route::resource('rencana-perawatan', RencanaPerawatanController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->middleware('izin:perawatan.lihat|perawatan.kelola');

    Route::resource('perintah-kerja', PerintahKerjaController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->middleware('izin:pk.lihat|pk.kelola');

    // Route::get('aset/{aset}/penghapusan', [AsetController::class, 'penghapusanForm'])
    //     ->name('aset.penghapusan.form')
    //     ->middleware('izin:penghapusan_aset.lihat|penghapusan_aset.kelola');

    // Route::post('aset/{aset}/penghapusan', [AsetController::class, 'penghapusanStore'])
    //     ->name('aset.penghapusan.store')
    //     ->middleware('izin:penghapusan_aset.kelola');

    Route::resource('pesanan-pembelian', PesananPembelianController::class)
        ->only(['index'])
        ->middleware('izin:pesanan_pembelian.lihat');

    Route::resource('pesanan-pembelian', PesananPembelianController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->middleware('izin:pesanan_pembelian.kelola');

    Route::post('pesanan-pembelian/{pesanan_pembelian}/ajukan', [PesananPembelianController::class, 'ajukan'])
        ->name('pesanan-pembelian.ajukan')
        ->middleware('izin:pesanan_pembelian.kelola');

    Route::post('pesanan-pembelian/{pesanan_pembelian}/setujui', [PesananPembelianController::class, 'setujui'])
        ->name('pesanan-pembelian.setujui')
        ->middleware('izin:pesanan_pembelian.kelola');

    Route::post('pesanan-pembelian/{pesanan_pembelian}/batalkan', [PesananPembelianController::class, 'batalkan'])
        ->name('pesanan-pembelian.batalkan')
        ->middleware('izin:pesanan_pembelian.kelola');


    Route::resource('penerimaan', PenerimaanController::class)
        ->only(['index'])
        ->middleware('izin:penerimaan.lihat');

    Route::resource('penerimaan', PenerimaanController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->middleware('izin:penerimaan.kelola');

    Route::post('penerimaan/{penerimaan}/qc-mulai', [PenerimaanController::class, 'qcMulai'])
        ->name('penerimaan.qcMulai')
        ->middleware('izin:penerimaan.kelola');

    Route::post('penerimaan/{penerimaan}/posting-stok-masuk', [PenerimaanController::class, 'postingStokMasuk'])
        ->name('penerimaan.postingStokMasuk')
        ->middleware('izin:stok.posting');


    Route::resource('inspeksi-qc', InspeksiQcController::class)
        ->only(['index'])
        ->middleware('izin:qc.lihat');

    Route::resource('inspeksi-qc', InspeksiQcController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->middleware('izin:qc.kelola');

    Route::get('saldo-stok', [SaldoStokController::class, 'index'])
        ->name('saldo-stok.index')
        ->middleware('izin:saldo_stok.lihat');

    Route::get('pergerakan-stok', [PergerakanStokController::class, 'index'])
        ->name('pergerakan-stok.index')
        ->middleware('izin:pergerakan_stok.lihat');

    Route::get('pergerakan-stok/{pergerakan_stok}', [PergerakanStokController::class, 'show'])
        ->name('pergerakan-stok.show')
        ->middleware('izin:pergerakan_stok.lihat');

    Route::get('penerimaan/{penerimaan}/posting', [PergerakanStokController::class, 'postingDariPenerimaan'])
        ->name('penerimaan.posting')
        ->middleware('izin:pergerakan_stok.kelola');

    Route::post('penerimaan/{penerimaan}/posting', [PergerakanStokController::class, 'simpanPostingDariPenerimaan'])
        ->name('penerimaan.posting.store')
        ->middleware('izin:pergerakan_stok.kelola');

    Route::resource('alur-persetujuan', AlurPersetujuanController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->middleware('izin:alur_persetujuan.lihat|alur_persetujuan.kelola');

    Route::resource('permintaan-persetujuan', PermintaanPersetujuanController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->middleware('izin:permintaan_persetujuan.lihat|permintaan_persetujuan.kelola');

    Route::post('permintaan-persetujuan/{permintaan_persetujuan}/setujui', [PermintaanPersetujuanController::class, 'setujui'])
        ->name('permintaan-persetujuan.setujui')
        ->middleware('izin:permintaan_persetujuan.kelola');

    Route::post('permintaan-persetujuan/{permintaan_persetujuan}/tolak', [PermintaanPersetujuanController::class, 'tolak'])
        ->name('permintaan-persetujuan.tolak')
        ->middleware('izin:permintaan_persetujuan.kelola');

    Route::resource('log-audit', LogAuditController::class)
        ->only(['index', 'show'])
        // ->middleware('izin:log_audit.lihat|log_audit.kelola');
        ->middleware('izin:audit.lihat');

    Route::get('permintaan', [\App\Http\Controllers\PermintaanController::class, 'index'])
        ->name('permintaan.index')
        ->middleware('izin:permintaan.lihat');

    Route::get('permintaan/create', [\App\Http\Controllers\PermintaanController::class, 'create'])
        ->name('permintaan.create')
        ->middleware('izin:permintaan.kelola');

    Route::post('permintaan', [\App\Http\Controllers\PermintaanController::class, 'store'])
        ->name('permintaan.store')
        ->middleware('izin:permintaan.kelola');

    Route::get('permintaan/{permintaan}/edit', [\App\Http\Controllers\PermintaanController::class, 'edit'])
        ->name('permintaan.edit')
        ->middleware('izin:permintaan.kelola');

    Route::put('permintaan/{permintaan}', [\App\Http\Controllers\PermintaanController::class, 'update'])
        ->name('permintaan.update')
        ->middleware('izin:permintaan.kelola');

    Route::post('permintaan/{permintaan}/ajukan', [\App\Http\Controllers\PermintaanController::class, 'ajukan'])
        ->name('permintaan.ajukan')
        ->middleware('izin:permintaan.kelola');

    Route::resource('pengeluaran', \App\Http\Controllers\PengeluaranController::class)
        ->only(['index', 'create', 'store', 'edit', 'update'])
        ->middleware('izin:pengeluaran.lihat|pengeluaran.kelola');

    Route::post('pengeluaran/{pengeluaran}/posting', [\App\Http\Controllers\PengeluaranController::class, 'posting'])
        ->name('pengeluaran.posting')
        ->middleware('izin:pengeluaran.kelola');

    Route::post('pengeluaran/{pengeluaran}/batalkan', [\App\Http\Controllers\PengeluaranController::class, 'batalkan'])
        ->name('pengeluaran.batalkan')
        ->middleware('izin:pengeluaran.kelola');
});

Route::prefix('api')->group(function () {
    Route::get('/barang', [WarehouseApiController::class, 'barangList']);
    Route::get('/gudang', [WarehouseApiController::class, 'gudangList']);

    Route::get('/stok/saldo', [WarehouseApiController::class, 'stokSaldo']);
    Route::get('/stok/mutasi', [WarehouseApiController::class, 'stokMutasi']);

    Route::get('/po', [WarehouseApiController::class, 'poList']);
    Route::get('/penerimaan', [WarehouseApiController::class, 'penerimaanList']);
    Route::get('/qc', [WarehouseApiController::class, 'qcList']);

    Route::get('/permintaan', [WarehouseApiController::class, 'permintaanList']);
    Route::get('/pengeluaran', [WarehouseApiController::class, 'pengeluaranList']);

    Route::get('/aset', [WarehouseApiController::class, 'asetList']);
    Route::get('/approval/pending', [WarehouseApiController::class, 'approvalPending']);

    Route::get('/lookup/barang', [WarehouseApiController::class, 'lookupBarang']);
    Route::get('/lookup/gudang', [WarehouseApiController::class, 'lookupGudang']);
    Route::get('/lookup/lokasi', [WarehouseApiController::class, 'lookupLokasi']);
    Route::get('/lookup/pemasok', [WarehouseApiController::class, 'lookupPemasok']);
    Route::get('/lookup/unit', [WarehouseApiController::class, 'lookupUnit']);
});
