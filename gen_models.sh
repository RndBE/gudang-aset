mkdir -p app/Models

cat > app/Models/BaseModel.php <<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';
    protected $guarded = [];
}
PHP

cat > app/Models/Instansi.php <<'PHP'
<?php

namespace App\Models;

class Instansi extends BaseModel
{
    protected $table = 'instansi';

    public function unitOrganisasi() { return $this->hasMany(UnitOrganisasi::class, 'instansi_id'); }
    public function pengguna() { return $this->hasMany(Pengguna::class, 'instansi_id'); }
    public function peran() { return $this->hasMany(Peran::class, 'instansi_id'); }
    public function gudang() { return $this->hasMany(Gudang::class, 'instansi_id'); }
    public function kategoriBarang() { return $this->hasMany(KategoriBarang::class, 'instansi_id'); }
    public function pemasok() { return $this->hasMany(Pemasok::class, 'instansi_id'); }
    public function barang() { return $this->hasMany(Barang::class, 'instansi_id'); }
    public function kontrak() { return $this->hasMany(Kontrak::class, 'instansi_id'); }
    public function pesananPembelian() { return $this->hasMany(PesananPembelian::class, 'instansi_id'); }
    public function penerimaan() { return $this->hasMany(Penerimaan::class, 'instansi_id'); }
    public function pergerakanStok() { return $this->hasMany(PergerakanStok::class, 'instansi_id'); }
    public function permintaan() { return $this->hasMany(Permintaan::class, 'instansi_id'); }
    public function pengeluaran() { return $this->hasMany(Pengeluaran::class, 'instansi_id'); }
    public function transfer() { return $this->hasMany(Transfer::class, 'instansi_id'); }
    public function aset() { return $this->hasMany(Aset::class, 'instansi_id'); }
    public function rencanaPerawatan() { return $this->hasMany(RencanaPerawatan::class, 'instansi_id'); }
    public function perintahKerja() { return $this->hasMany(PerintahKerja::class, 'instansi_id'); }
    public function stokOpname() { return $this->hasMany(StokOpname::class, 'instansi_id'); }
    public function berkas() { return $this->hasMany(Berkas::class, 'instansi_id'); }
    public function alurPersetujuan() { return $this->hasMany(AlurPersetujuan::class, 'instansi_id'); }
    public function permintaanPersetujuan() { return $this->hasMany(PermintaanPersetujuan::class, 'instansi_id'); }
    public function notifikasi() { return $this->hasMany(Notifikasi::class, 'instansi_id'); }
    public function logAudit() { return $this->hasMany(LogAudit::class, 'instansi_id'); }
    public function urutanNomorDokumen() { return $this->hasMany(UrutanNomorDokumen::class, 'instansi_id'); }
}
PHP

cat > app/Models/UnitOrganisasi.php <<'PHP'
<?php

namespace App\Models;

class UnitOrganisasi extends BaseModel
{
    protected $table = 'unit_organisasi';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function induk() { return $this->belongsTo(UnitOrganisasi::class, 'induk_id'); }
    public function anak() { return $this->hasMany(UnitOrganisasi::class, 'induk_id'); }

    public function pengguna() { return $this->hasMany(Pengguna::class, 'unit_organisasi_id'); }
    public function gudang() { return $this->hasMany(Gudang::class, 'unit_organisasi_id'); }

    public function kontrak() { return $this->hasMany(Kontrak::class, 'unit_organisasi_id'); }
    public function pesananPembelian() { return $this->hasMany(PesananPembelian::class, 'unit_organisasi_id'); }
    public function permintaan() { return $this->hasMany(Permintaan::class, 'unit_organisasi_id'); }
    public function pengeluaran() { return $this->hasMany(Pengeluaran::class, 'unit_organisasi_id'); }

    public function asetSaatIni() { return $this->hasMany(Aset::class, 'unit_organisasi_saat_ini_id'); }
}
PHP

cat > app/Models/Pengguna.php <<'PHP'
<?php

namespace App\Models;

class Pengguna extends BaseModel
{
    protected $table = 'pengguna';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function unitOrganisasi() { return $this->belongsTo(UnitOrganisasi::class, 'unit_organisasi_id'); }

    public function peran()
    {
        return $this->belongsToMany(Peran::class, 'pengguna_peran', 'pengguna_id', 'peran_id')->withPivot(['dibuat_pada']);
    }

    public function notifikasi() { return $this->hasMany(Notifikasi::class, 'pengguna_id'); }
    public function logAudit() { return $this->hasMany(LogAudit::class, 'pengguna_id'); }
}
PHP

cat > app/Models/Peran.php <<'PHP'
<?php

namespace App\Models;

class Peran extends BaseModel
{
    protected $table = 'peran';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }

    public function pengguna()
    {
        return $this->belongsToMany(Pengguna::class, 'pengguna_peran', 'peran_id', 'pengguna_id')->withPivot(['dibuat_pada']);
    }

    public function izin()
    {
        return $this->belongsToMany(Izin::class, 'peran_izin', 'peran_id', 'izin_id')->withPivot(['dibuat_pada']);
    }
}
PHP

cat > app/Models/Izin.php <<'PHP'
<?php

namespace App\Models;

class Izin extends BaseModel
{
    protected $table = 'izin';

    public function peran()
    {
        return $this->belongsToMany(Peran::class, 'peran_izin', 'izin_id', 'peran_id')->withPivot(['dibuat_pada']);
    }
}
PHP

cat > app/Models/PenggunaPeran.php <<'PHP'
<?php

namespace App\Models;

class PenggunaPeran extends BaseModel
{
    protected $table = 'pengguna_peran';
    const UPDATED_AT = null;

    public function pengguna() { return $this->belongsTo(Pengguna::class, 'pengguna_id'); }
    public function peran() { return $this->belongsTo(Peran::class, 'peran_id'); }
}
PHP

cat > app/Models/PeranIzin.php <<'PHP'
<?php

namespace App\Models;

class PeranIzin extends BaseModel
{
    protected $table = 'peran_izin';
    const UPDATED_AT = null;

    public function peran() { return $this->belongsTo(Peran::class, 'peran_id'); }
    public function izin() { return $this->belongsTo(Izin::class, 'izin_id'); }
}
PHP

cat > app/Models/UrutanNomorDokumen.php <<'PHP'
<?php

namespace App\Models;

class UrutanNomorDokumen extends BaseModel
{
    protected $table = 'urutan_nomor_dokumen';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function unitOrganisasi() { return $this->belongsTo(UnitOrganisasi::class, 'unit_organisasi_id'); }
}
PHP

cat > app/Models/Gudang.php <<'PHP'
<?php

namespace App\Models;

class Gudang extends BaseModel
{
    protected $table = 'gudang';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function unitOrganisasi() { return $this->belongsTo(UnitOrganisasi::class, 'unit_organisasi_id'); }

    public function lokasi() { return $this->hasMany(LokasiGudang::class, 'gudang_id'); }
    public function penerimaan() { return $this->hasMany(Penerimaan::class, 'gudang_id'); }
    public function saldoStok() { return $this->hasMany(SaldoStok::class, 'gudang_id'); }
    public function pengeluaran() { return $this->hasMany(Pengeluaran::class, 'gudang_id'); }

    public function asetSaatIni() { return $this->hasMany(Aset::class, 'gudang_saat_ini_id'); }
}
PHP

cat > app/Models/LokasiGudang.php <<'PHP'
<?php

namespace App\Models;

class LokasiGudang extends BaseModel
{
    protected $table = 'lokasi_gudang';

    public function gudang() { return $this->belongsTo(Gudang::class, 'gudang_id'); }
    public function induk() { return $this->belongsTo(LokasiGudang::class, 'induk_id'); }
    public function anak() { return $this->hasMany(LokasiGudang::class, 'induk_id'); }

    public function saldoStok() { return $this->hasMany(SaldoStok::class, 'lokasi_id'); }
    public function penerimaanDetail() { return $this->hasMany(PenerimaanDetail::class, 'lokasi_id'); }
    public function pengeluaranDetail() { return $this->hasMany(PengeluaranDetail::class, 'lokasi_id'); }

    public function asetSaatIni() { return $this->hasMany(Aset::class, 'lokasi_saat_ini_id'); }
}
PHP

cat > app/Models/SatuanBarang.php <<'PHP'
<?php

namespace App\Models;

class SatuanBarang extends BaseModel
{
    protected $table = 'satuan_barang';

    public function barang() { return $this->hasMany(Barang::class, 'satuan_id'); }
}
PHP

cat > app/Models/KategoriBarang.php <<'PHP'
<?php

namespace App\Models;

class KategoriBarang extends BaseModel
{
    protected $table = 'kategori_barang';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function induk() { return $this->belongsTo(KategoriBarang::class, 'induk_id'); }
    public function anak() { return $this->hasMany(KategoriBarang::class, 'induk_id'); }

    public function barang() { return $this->hasMany(Barang::class, 'kategori_id'); }
}
PHP

cat > app/Models/Pemasok.php <<'PHP'
<?php

namespace App\Models;

class Pemasok extends BaseModel
{
    protected $table = 'pemasok';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function kontrak() { return $this->hasMany(Kontrak::class, 'pemasok_id'); }
    public function pesananPembelian() { return $this->hasMany(PesananPembelian::class, 'pemasok_id'); }
    public function penerimaan() { return $this->hasMany(Penerimaan::class, 'pemasok_id'); }
}
PHP

cat > app/Models/Barang.php <<'PHP'
<?php

namespace App\Models;

class Barang extends BaseModel
{
    protected $table = 'barang';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function kategori() { return $this->belongsTo(KategoriBarang::class, 'kategori_id'); }
    public function satuan() { return $this->belongsTo(SatuanBarang::class, 'satuan_id'); }

    public function saldoStok() { return $this->hasMany(SaldoStok::class, 'barang_id'); }
    public function pesananPembelianDetail() { return $this->hasMany(PesananPembelianDetail::class, 'barang_id'); }
    public function penerimaanDetail() { return $this->hasMany(PenerimaanDetail::class, 'barang_id'); }
    public function pengeluaranDetail() { return $this->hasMany(PengeluaranDetail::class, 'barang_id'); }
    public function transferDetail() { return $this->hasMany(TransferDetail::class, 'barang_id'); }
    public function detailPergerakanStok() { return $this->hasMany(DetailPergerakanStok::class, 'barang_id'); }
    public function aset() { return $this->hasMany(Aset::class, 'barang_id'); }
}
PHP

cat > app/Models/Kontrak.php <<'PHP'
<?php

namespace App\Models;

class Kontrak extends BaseModel
{
    protected $table = 'kontrak';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function unitOrganisasi() { return $this->belongsTo(UnitOrganisasi::class, 'unit_organisasi_id'); }
    public function pemasok() { return $this->belongsTo(Pemasok::class, 'pemasok_id'); }
    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }

    public function pesananPembelian() { return $this->hasMany(PesananPembelian::class, 'kontrak_id'); }
}
PHP

cat > app/Models/PesananPembelian.php <<'PHP'
<?php

namespace App\Models;

class PesananPembelian extends BaseModel
{
    protected $table = 'pesanan_pembelian';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function unitOrganisasi() { return $this->belongsTo(UnitOrganisasi::class, 'unit_organisasi_id'); }
    public function pemasok() { return $this->belongsTo(Pemasok::class, 'pemasok_id'); }
    public function kontrak() { return $this->belongsTo(Kontrak::class, 'kontrak_id'); }
    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }

    public function detail() { return $this->hasMany(PesananPembelianDetail::class, 'pesanan_pembelian_id'); }
    public function penerimaan() { return $this->hasMany(Penerimaan::class, 'pesanan_pembelian_id'); }
}
PHP

cat > app/Models/PesananPembelianDetail.php <<'PHP'
<?php

namespace App\Models;

class PesananPembelianDetail extends BaseModel
{
    protected $table = 'pesanan_pembelian_detail';

    public function pesananPembelian() { return $this->belongsTo(PesananPembelian::class, 'pesanan_pembelian_id'); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id'); }

    public function penerimaanDetail() { return $this->hasMany(PenerimaanDetail::class, 'po_detail_id'); }
}
PHP

cat > app/Models/Penerimaan.php <<'PHP'
<?php

namespace App\Models;

class Penerimaan extends BaseModel
{
    protected $table = 'penerimaan';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function gudang() { return $this->belongsTo(Gudang::class, 'gudang_id'); }
    public function pemasok() { return $this->belongsTo(Pemasok::class, 'pemasok_id'); }
    public function pesananPembelian() { return $this->belongsTo(PesananPembelian::class, 'pesanan_pembelian_id'); }
    public function diterimaOleh() { return $this->belongsTo(Pengguna::class, 'diterima_oleh'); }
    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }

    public function detail() { return $this->hasMany(PenerimaanDetail::class, 'penerimaan_id'); }
    public function inspeksiQc() { return $this->hasOne(InspeksiQc::class, 'penerimaan_id'); }

    public function permintaanPersetujuan()
    {
        return $this->morphMany(PermintaanPersetujuan::class, 'entitas', 'tipe_entitas', 'id_entitas');
    }

    public function lampiran()
    {
        return $this->morphMany(LampiranEntitas::class, 'entitas', 'tipe_entitas', 'id_entitas');
    }
}
PHP

cat > app/Models/PenerimaanDetail.php <<'PHP'
<?php

namespace App\Models;

class PenerimaanDetail extends BaseModel
{
    protected $table = 'penerimaan_detail';

    public function penerimaan() { return $this->belongsTo(Penerimaan::class, 'penerimaan_id'); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id'); }
    public function poDetail() { return $this->belongsTo(PesananPembelianDetail::class, 'po_detail_id'); }
    public function lokasi() { return $this->belongsTo(LokasiGudang::class, 'lokasi_id'); }

    public function qcDetail() { return $this->hasMany(InspeksiQcDetail::class, 'penerimaan_detail_id'); }
}
PHP

cat > app/Models/InspeksiQc.php <<'PHP'
<?php

namespace App\Models;

class InspeksiQc extends BaseModel
{
    protected $table = 'inspeksi_qc';

    public function penerimaan() { return $this->belongsTo(Penerimaan::class, 'penerimaan_id'); }
    public function pemeriksa() { return $this->belongsTo(Pengguna::class, 'pemeriksa_id'); }

    public function detail() { return $this->hasMany(InspeksiQcDetail::class, 'inspeksi_qc_id'); }
}
PHP

cat > app/Models/InspeksiQcDetail.php <<'PHP'
<?php

namespace App\Models;

class InspeksiQcDetail extends BaseModel
{
    protected $table = 'inspeksi_qc_detail';

    public function inspeksiQc() { return $this->belongsTo(InspeksiQc::class, 'inspeksi_qc_id'); }
    public function penerimaanDetail() { return $this->belongsTo(PenerimaanDetail::class, 'penerimaan_detail_id'); }
}
PHP

cat > app/Models/SaldoStok.php <<'PHP'
<?php

namespace App\Models;

class SaldoStok extends BaseModel
{
    protected $table = 'saldo_stok';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function gudang() { return $this->belongsTo(Gudang::class, 'gudang_id'); }
    public function lokasi() { return $this->belongsTo(LokasiGudang::class, 'lokasi_id'); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id'); }
}
PHP

cat > app/Models/PergerakanStok.php <<'PHP'
<?php

namespace App\Models;

class PergerakanStok extends BaseModel
{
    protected $table = 'pergerakan_stok';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function gudang() { return $this->belongsTo(Gudang::class, 'gudang_id'); }
    public function dipostingOleh() { return $this->belongsTo(Pengguna::class, 'diposting_oleh'); }
    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }

    public function detail() { return $this->hasMany(DetailPergerakanStok::class, 'pergerakan_stok_id'); }

    public function referensi()
    {
        return $this->morphTo(null, 'tipe_referensi', 'id_referensi');
    }
}
PHP

cat > app/Models/DetailPergerakanStok.php <<'PHP'
<?php

namespace App\Models;

class DetailPergerakanStok extends BaseModel
{
    protected $table = 'detail_pergerakan_stok';

    public function pergerakanStok() { return $this->belongsTo(PergerakanStok::class, 'pergerakan_stok_id'); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id'); }

    public function dariGudang() { return $this->belongsTo(Gudang::class, 'dari_gudang_id'); }
    public function dariLokasi() { return $this->belongsTo(LokasiGudang::class, 'dari_lokasi_id'); }
    public function keGudang() { return $this->belongsTo(Gudang::class, 'ke_gudang_id'); }
    public function keLokasi() { return $this->belongsTo(LokasiGudang::class, 'ke_lokasi_id'); }
}
PHP

cat > app/Models/Permintaan.php <<'PHP'
<?php

namespace App\Models;

class Permintaan extends BaseModel
{
    protected $table = 'permintaan';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function unitOrganisasi() { return $this->belongsTo(UnitOrganisasi::class, 'unit_organisasi_id'); }
    public function pemohon() { return $this->belongsTo(Pengguna::class, 'pemohon_id'); }
    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }

    public function detail() { return $this->hasMany(PermintaanDetail::class, 'permintaan_id'); }
    public function pengeluaran() { return $this->hasMany(Pengeluaran::class, 'permintaan_id'); }

    public function permintaanPersetujuan()
    {
        return $this->morphMany(PermintaanPersetujuan::class, 'entitas', 'tipe_entitas', 'id_entitas');
    }

    public function lampiran()
    {
        return $this->morphMany(LampiranEntitas::class, 'entitas', 'tipe_entitas', 'id_entitas');
    }
}
PHP

cat > app/Models/PermintaanDetail.php <<'PHP'
<?php

namespace App\Models;

class PermintaanDetail extends BaseModel
{
    protected $table = 'permintaan_detail';

    public function permintaan() { return $this->belongsTo(Permintaan::class, 'permintaan_id'); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id'); }
}
PHP

cat > app/Models/Pengeluaran.php <<'PHP'
<?php

namespace App\Models;

class Pengeluaran extends BaseModel
{
    protected $table = 'pengeluaran';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function gudang() { return $this->belongsTo(Gudang::class, 'gudang_id'); }
    public function unitOrganisasi() { return $this->belongsTo(UnitOrganisasi::class, 'unit_organisasi_id'); }
    public function permintaan() { return $this->belongsTo(Permintaan::class, 'permintaan_id'); }

    public function diserahkanKePengguna() { return $this->belongsTo(Pengguna::class, 'diserahkan_ke_pengguna_id'); }
    public function diserahkanKeUnit() { return $this->belongsTo(UnitOrganisasi::class, 'diserahkan_ke_unit_id'); }

    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }
    public function dipostingOleh() { return $this->belongsTo(Pengguna::class, 'diposting_oleh'); }

    public function detail() { return $this->hasMany(PengeluaranDetail::class, 'pengeluaran_id'); }

    public function permintaanPersetujuan()
    {
        return $this->morphMany(PermintaanPersetujuan::class, 'entitas', 'tipe_entitas', 'id_entitas');
    }

    public function lampiran()
    {
        return $this->morphMany(LampiranEntitas::class, 'entitas', 'tipe_entitas', 'id_entitas');
    }
}
PHP

cat > app/Models/PengeluaranDetail.php <<'PHP'
<?php

namespace App\Models;

class PengeluaranDetail extends BaseModel
{
    protected $table = 'pengeluaran_detail';

    public function pengeluaran() { return $this->belongsTo(Pengeluaran::class, 'pengeluaran_id'); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id'); }
    public function lokasi() { return $this->belongsTo(LokasiGudang::class, 'lokasi_id'); }
}
PHP

cat > app/Models/Transfer.php <<'PHP'
<?php

namespace App\Models;

class Transfer extends BaseModel
{
    protected $table = 'transfer';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function dariGudang() { return $this->belongsTo(Gudang::class, 'dari_gudang_id'); }
    public function keGudang() { return $this->belongsTo(Gudang::class, 'ke_gudang_id'); }

    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }
    public function dipostingOleh() { return $this->belongsTo(Pengguna::class, 'diposting_oleh'); }

    public function detail() { return $this->hasMany(TransferDetail::class, 'transfer_id'); }

    public function permintaanPersetujuan()
    {
        return $this->morphMany(PermintaanPersetujuan::class, 'entitas', 'tipe_entitas', 'id_entitas');
    }

    public function lampiran()
    {
        return $this->morphMany(LampiranEntitas::class, 'entitas', 'tipe_entitas', 'id_entitas');
    }
}
PHP

cat > app/Models/TransferDetail.php <<'PHP'
<?php

namespace App\Models;

class TransferDetail extends BaseModel
{
    protected $table = 'transfer_detail';

    public function transfer() { return $this->belongsTo(Transfer::class, 'transfer_id'); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id'); }
    public function dariLokasi() { return $this->belongsTo(LokasiGudang::class, 'dari_lokasi_id'); }
    public function keLokasi() { return $this->belongsTo(LokasiGudang::class, 'ke_lokasi_id'); }
}
PHP

cat > app/Models/Aset.php <<'PHP'
<?php

namespace App\Models;

class Aset extends BaseModel
{
    protected $table = 'aset';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id'); }
    public function penerimaan() { return $this->belongsTo(Penerimaan::class, 'penerimaan_id'); }

    public function unitOrganisasiSaatIni() { return $this->belongsTo(UnitOrganisasi::class, 'unit_organisasi_saat_ini_id'); }
    public function gudangSaatIni() { return $this->belongsTo(Gudang::class, 'gudang_saat_ini_id'); }
    public function lokasiSaatIni() { return $this->belongsTo(LokasiGudang::class, 'lokasi_saat_ini_id'); }
    public function pemegangPengguna() { return $this->belongsTo(Pengguna::class, 'pemegang_pengguna_id'); }

    public function penugasan() { return $this->hasMany(PenugasanAset::class, 'aset_id'); }
    public function peminjaman() { return $this->hasMany(PeminjamanAset::class, 'aset_id'); }
    public function logKondisi() { return $this->hasMany(LogKondisiAset::class, 'aset_id'); }
    public function penghapusan() { return $this->hasMany(PenghapusanAset::class, 'aset_id'); }

    public function rencanaPerawatanAset() { return $this->hasMany(RencanaPerawatanAset::class, 'aset_id'); }
    public function perintahKerja() { return $this->hasMany(PerintahKerja::class, 'aset_id'); }

    public function lampiran()
    {
        return $this->morphMany(LampiranEntitas::class, 'entitas', 'tipe_entitas', 'id_entitas');
    }
}
PHP

cat > app/Models/PenugasanAset.php <<'PHP'
<?php

namespace App\Models;

class PenugasanAset extends BaseModel
{
    protected $table = 'penugasan_aset';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function aset() { return $this->belongsTo(Aset::class, 'aset_id'); }

    public function ditugaskanKePengguna() { return $this->belongsTo(Pengguna::class, 'ditugaskan_ke_pengguna_id'); }
    public function ditugaskanKeUnit() { return $this->belongsTo(UnitOrganisasi::class, 'ditugaskan_ke_unit_id'); }

    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }
}
PHP

cat > app/Models/PeminjamanAset.php <<'PHP'
<?php

namespace App\Models;

class PeminjamanAset extends BaseModel
{
    protected $table = 'peminjaman_aset';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function aset() { return $this->belongsTo(Aset::class, 'aset_id'); }

    public function peminjamPengguna() { return $this->belongsTo(Pengguna::class, 'peminjam_pengguna_id'); }
    public function peminjamUnit() { return $this->belongsTo(UnitOrganisasi::class, 'peminjam_unit_id'); }

    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }
}
PHP

cat > app/Models/LogKondisiAset.php <<'PHP'
<?php

namespace App\Models;

class LogKondisiAset extends BaseModel
{
    protected $table = 'log_kondisi_aset';
    const UPDATED_AT = null;

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function aset() { return $this->belongsTo(Aset::class, 'aset_id'); }
    public function dicatatOleh() { return $this->belongsTo(Pengguna::class, 'dicatat_oleh'); }
}
PHP

cat > app/Models/PenghapusanAset.php <<'PHP'
<?php

namespace App\Models;

class PenghapusanAset extends BaseModel
{
    protected $table = 'penghapusan_aset';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function aset() { return $this->belongsTo(Aset::class, 'aset_id'); }
    public function disetujuiOleh() { return $this->belongsTo(Pengguna::class, 'disetujui_oleh'); }
    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }

    public function lampiran()
    {
        return $this->morphMany(LampiranEntitas::class, 'entitas', 'tipe_entitas', 'id_entitas');
    }
}
PHP

cat > app/Models/RencanaPerawatan.php <<'PHP'
<?php

namespace App\Models;

class RencanaPerawatan extends BaseModel
{
    protected $table = 'rencana_perawatan';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }

    public function aset() { return $this->hasMany(RencanaPerawatanAset::class, 'rencana_perawatan_id'); }
}
PHP

cat > app/Models/RencanaPerawatanAset.php <<'PHP'
<?php

namespace App\Models;

class RencanaPerawatanAset extends BaseModel
{
    protected $table = 'rencana_perawatan_aset';

    public function rencanaPerawatan() { return $this->belongsTo(RencanaPerawatan::class, 'rencana_perawatan_id'); }
    public function aset() { return $this->belongsTo(Aset::class, 'aset_id'); }
}
PHP

cat > app/Models/PerintahKerja.php <<'PHP'
<?php

namespace App\Models;

class PerintahKerja extends BaseModel
{
    protected $table = 'perintah_kerja';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function aset() { return $this->belongsTo(Aset::class, 'aset_id'); }
    public function rencanaPerawatan() { return $this->belongsTo(RencanaPerawatan::class, 'rencana_perawatan_id'); }

    public function dibukaOleh() { return $this->belongsTo(Pengguna::class, 'dibuka_oleh'); }
    public function ditutupOleh() { return $this->belongsTo(Pengguna::class, 'ditutup_oleh'); }
    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }

    public function pembaruan() { return $this->hasMany(PembaruanPerintahKerja::class, 'perintah_kerja_id'); }
    public function sukuCadang() { return $this->hasMany(SukuCadangPerintahKerja::class, 'perintah_kerja_id'); }

    public function lampiran()
    {
        return $this->morphMany(LampiranEntitas::class, 'entitas', 'tipe_entitas', 'id_entitas');
    }

    public function permintaanPersetujuan()
    {
        return $this->morphMany(PermintaanPersetujuan::class, 'entitas', 'tipe_entitas', 'id_entitas');
    }
}
PHP

cat > app/Models/PembaruanPerintahKerja.php <<'PHP'
<?php

namespace App\Models;

class PembaruanPerintahKerja extends BaseModel
{
    protected $table = 'pembaruan_perintah_kerja';
    const UPDATED_AT = null;

    public function perintahKerja() { return $this->belongsTo(PerintahKerja::class, 'perintah_kerja_id'); }
    public function diupdateOleh() { return $this->belongsTo(Pengguna::class, 'diupdate_oleh'); }
}
PHP

cat > app/Models/SukuCadangPerintahKerja.php <<'PHP'
<?php

namespace App\Models;

class SukuCadangPerintahKerja extends BaseModel
{
    protected $table = 'suku_cadang_perintah_kerja';
    const UPDATED_AT = null;

    public function perintahKerja() { return $this->belongsTo(PerintahKerja::class, 'perintah_kerja_id'); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id'); }
}
PHP

cat > app/Models/StokOpname.php <<'PHP'
<?php

namespace App\Models;

class StokOpname extends BaseModel
{
    protected $table = 'stok_opname';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function gudang() { return $this->belongsTo(Gudang::class, 'gudang_id'); }
    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }
    public function dipostingOleh() { return $this->belongsTo(Pengguna::class, 'diposting_oleh'); }

    public function detail() { return $this->hasMany(StokOpnameDetail::class, 'stok_opname_id'); }

    public function lampiran()
    {
        return $this->morphMany(LampiranEntitas::class, 'entitas', 'tipe_entitas', 'id_entitas');
    }
}
PHP

cat > app/Models/StokOpnameDetail.php <<'PHP'
<?php

namespace App\Models;

class StokOpnameDetail extends BaseModel
{
    protected $table = 'stok_opname_detail';

    public function stokOpname() { return $this->belongsTo(StokOpname::class, 'stok_opname_id'); }
    public function lokasi() { return $this->belongsTo(LokasiGudang::class, 'lokasi_id'); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id'); }
    public function dihitungOleh() { return $this->belongsTo(Pengguna::class, 'dihitung_oleh'); }
}
PHP

cat > app/Models/Berkas.php <<'PHP'
<?php

namespace App\Models;

class Berkas extends BaseModel
{
    protected $table = 'berkas';
    const UPDATED_AT = null;

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function diunggahOleh() { return $this->belongsTo(Pengguna::class, 'diunggah_oleh'); }

    public function lampiran() { return $this->hasMany(LampiranEntitas::class, 'berkas_id'); }
}
PHP

cat > app/Models/LampiranEntitas.php <<'PHP'
<?php

namespace App\Models;

class LampiranEntitas extends BaseModel
{
    protected $table = 'lampiran_entitas';
    const UPDATED_AT = null;

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function berkas() { return $this->belongsTo(Berkas::class, 'berkas_id'); }
    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }

    public function entitas()
    {
        return $this->morphTo(null, 'tipe_entitas', 'id_entitas');
    }
}
PHP

cat > app/Models/TandaTangan.php <<'PHP'
<?php

namespace App\Models;

class TandaTangan extends BaseModel
{
    protected $table = 'tanda_tangan';
    const UPDATED_AT = null;

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function penandaTangan() { return $this->belongsTo(Pengguna::class, 'penanda_tangan_id'); }

    public function entitas()
    {
        return $this->morphTo(null, 'tipe_entitas', 'id_entitas');
    }
}
PHP

cat > app/Models/AlurPersetujuan.php <<'PHP'
<?php

namespace App\Models;

class AlurPersetujuan extends BaseModel
{
    protected $table = 'alur_persetujuan';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function dibuatOleh() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }

    public function langkah() { return $this->hasMany(LangkahAlurPersetujuan::class, 'alur_persetujuan_id'); }
    public function permintaanPersetujuan() { return $this->hasMany(PermintaanPersetujuan::class, 'alur_persetujuan_id'); }
}
PHP

cat > app/Models/LangkahAlurPersetujuan.php <<'PHP'
<?php

namespace App\Models;

class LangkahAlurPersetujuan extends BaseModel
{
    protected $table = 'langkah_alur_persetujuan';

    public function alur() { return $this->belongsTo(AlurPersetujuan::class, 'alur_persetujuan_id'); }
    public function peran() { return $this->belongsTo(Peran::class, 'peran_id'); }
    public function pengguna() { return $this->belongsTo(Pengguna::class, 'pengguna_id'); }
    public function unitOrganisasi() { return $this->belongsTo(UnitOrganisasi::class, 'unit_organisasi_id'); }
}
PHP

cat > app/Models/PermintaanPersetujuan.php <<'PHP'
<?php

namespace App\Models;

class PermintaanPersetujuan extends BaseModel
{
    protected $table = 'permintaan_persetujuan';

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function alur() { return $this->belongsTo(AlurPersetujuan::class, 'alur_persetujuan_id'); }
    public function dimintaOleh() { return $this->belongsTo(Pengguna::class, 'diminta_oleh'); }

    public function langkah() { return $this->hasMany(LangkahPermintaanPersetujuan::class, 'permintaan_persetujuan_id'); }

    public function entitas()
    {
        return $this->morphTo(null, 'tipe_entitas', 'id_entitas');
    }

    public function tandaTangan()
    {
        return $this->morphMany(TandaTangan::class, 'entitas', 'tipe_entitas', 'id_entitas');
    }
}
PHP

cat > app/Models/LangkahPermintaanPersetujuan.php <<'PHP'
<?php

namespace App\Models;

class LangkahPermintaanPersetujuan extends BaseModel
{
    protected $table = 'langkah_permintaan_persetujuan';

    public function permintaanPersetujuan() { return $this->belongsTo(PermintaanPersetujuan::class, 'permintaan_persetujuan_id'); }
    public function diputuskanOleh() { return $this->belongsTo(Pengguna::class, 'diputuskan_oleh'); }
}
PHP

cat > app/Models/Notifikasi.php <<'PHP'
<?php

namespace App\Models;

class Notifikasi extends BaseModel
{
    protected $table = 'notifikasi';
    const UPDATED_AT = null;

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function pengguna() { return $this->belongsTo(Pengguna::class, 'pengguna_id'); }

    public function entitas()
    {
        return $this->morphTo(null, 'tipe_entitas', 'id_entitas');
    }
}
PHP

cat > app/Models/LogAudit.php <<'PHP'
<?php

namespace App\Models;

class LogAudit extends BaseModel
{
    protected $table = 'log_audit';
    const UPDATED_AT = null;

    public function instansi() { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function pengguna() { return $this->belongsTo(Pengguna::class, 'pengguna_id'); }

    public function referensi()
    {
        return $this->morphTo(null, 'tipe_referensi', 'id_referensi');
    }
}
PHP

