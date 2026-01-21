<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseApiController extends Controller
{
    private function limit(Request $r, int $default = 20, int $max = 200): int
    {
        $n = (int) $r->query('limit', $default);
        if ($n < 1) $n = 1;
        if ($n > $max) $n = $max;
        return $n;
    }

    private function likeQ($q): ?string
    {
        $q = is_string($q) ? trim($q) : null;
        return ($q !== null && $q !== '') ? "%{$q}%" : null;
    }

    private function dateRange(Request $r, string $col, $query)
    {
        $from = $r->query('from');
        $to = $r->query('to');

        if ($from) $query->whereDate($col, '>=', $from);
        if ($to) $query->whereDate($col, '<=', $to);

        return $query;
    }

    public function lookupBarang(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $q = $this->likeQ($r->query('q'));
        $limit = $this->limit($r, 20);

        $qb = DB::table('barang')
            ->select(['id', 'sku', 'nama']);

        if ($instansiId) $qb->where('instansi_id', $instansiId);
        if ($q) {
            $qb->where(function ($w) use ($q) {
                $w->where('nama', 'like', $q)->orWhere('sku', 'like', $q);
            });
        }

        $items = $qb->orderBy('nama')
            ->limit($limit)
            ->get()
            ->map(function ($x) {
                return [
                    'id' => $x->id,
                    'sku' => $x->sku,
                    'nama' => $x->nama,
                    'label' => "{$x->sku} — {$x->nama}",
                ];
            })->values();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function lookupGudang(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $q = $this->likeQ($r->query('q'));
        $limit = $this->limit($r, 20);

        $qb = DB::table('gudang')->select(['id', 'kode', 'nama']);

        if ($instansiId) $qb->where('instansi_id', $instansiId);
        if ($q) {
            $qb->where(function ($w) use ($q) {
                $w->where('nama', 'like', $q)->orWhere('kode', 'like', $q);
            });
        }

        $items = $qb->orderBy('nama')
            ->limit($limit)
            ->get()
            ->map(function ($x) {
                return [
                    'id' => $x->id,
                    'kode' => $x->kode,
                    'nama' => $x->nama,
                    'label' => "{$x->kode} — {$x->nama}",
                ];
            })->values();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function lookupLokasi(Request $r)
    {
        $gudangId = $r->query('gudang_id');
        $q = $this->likeQ($r->query('q'));
        $limit = $this->limit($r, 20);

        if (!$gudangId) {
            return response()->json(['items' => [], 'meta' => ['error' => 'gudang_id_required']], 422);
        }

        $qb = DB::table('lokasi_gudang')
            ->select(['id', 'kode', 'nama', 'jalur', 'gudang_id'])
            ->where('gudang_id', $gudangId);

        if ($q) {
            $qb->where(function ($w) use ($q) {
                $w->where('kode', 'like', $q)->orWhere('nama', 'like', $q)->orWhere('jalur', 'like', $q);
            });
        }

        $items = $qb->orderBy('kode')
            ->limit($limit)
            ->get()
            ->map(function ($x) {
                $nm = $x->nama ? $x->nama : $x->kode;
                return [
                    'id' => $x->id,
                    'kode' => $x->kode,
                    'nama' => $x->nama,
                    'jalur' => $x->jalur,
                    'label' => $x->jalur ? "{$x->kode} — {$nm} ({$x->jalur})" : "{$x->kode} — {$nm}",
                ];
            })->values();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit, 'gudang_id' => (int) $gudangId]]);
    }

    public function lookupPemasok(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $q = $this->likeQ($r->query('q'));
        $limit = $this->limit($r, 20);

        $qb = DB::table('pemasok')->select(['id', 'kode', 'nama']);

        if ($instansiId) $qb->where('instansi_id', $instansiId);
        if ($q) {
            $qb->where(function ($w) use ($q) {
                $w->where('nama', 'like', $q)->orWhere('kode', 'like', $q);
            });
        }

        $items = $qb->orderBy('nama')
            ->limit($limit)
            ->get()
            ->map(function ($x) {
                return [
                    'id' => $x->id,
                    'kode' => $x->kode,
                    'nama' => $x->nama,
                    'label' => "{$x->kode} — {$x->nama}",
                ];
            })->values();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function lookupUnit(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $q = $this->likeQ($r->query('q'));
        $limit = $this->limit($r, 20);

        $qb = DB::table('unit_organisasi')
            ->select(['id', 'kode', 'nama', 'tipe_unit', 'instansi_id']);

        if ($instansiId) $qb->where('instansi_id', $instansiId);
        if ($q) {
            $qb->where(function ($w) use ($q) {
                $w->where('nama', 'like', $q)->orWhere('kode', 'like', $q);
            });
        }

        $items = $qb->orderBy('nama')
            ->limit($limit)
            ->get()
            ->map(function ($x) {
                return [
                    'id' => $x->id,
                    'kode' => $x->kode,
                    'nama' => $x->nama,
                    'tipe_unit' => $x->tipe_unit,
                    'label' => "{$x->kode} — {$x->nama} ({$x->tipe_unit})",
                ];
            })->values();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function barangList(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $q = $this->likeQ($r->query('q'));
        $kategoriId = $r->query('kategori_id');
        $status = $r->query('status');
        $limit = $this->limit($r, 50);

        $qb = DB::table('barang as b')
            ->leftJoin('kategori_barang as k', 'k.id', '=', 'b.kategori_id')
            ->leftJoin('satuan_barang as s', 's.id', '=', 'b.satuan_id')
            ->select([
                'b.id',
                'b.sku',
                'b.nama',
                'b.tipe_barang',
                'b.metode_pelacakan',
                'b.stok_minimum',
                'b.titik_pesan_ulang',
                'b.status',
                'k.nama as kategori_nama',
                's.kode as satuan_kode',
            ]);

        if ($instansiId) $qb->where('b.instansi_id', $instansiId);
        if ($kategoriId) $qb->where('b.kategori_id', $kategoriId);
        if ($status) $qb->where('b.status', $status);
        if ($q) {
            $qb->where(function ($w) use ($q) {
                $w->where('b.nama', 'like', $q)->orWhere('b.sku', 'like', $q);
            });
        }

        $items = $qb->orderBy('b.nama')->limit($limit)->get();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function gudangList(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $unitId = $r->query('unit_organisasi_id') ?? $r->query('unit_id');
        $status = $r->query('status');
        $q = $this->likeQ($r->query('q'));
        $limit = $this->limit($r, 50);

        $qb = DB::table('gudang')
            ->select(['id', 'instansi_id', 'unit_organisasi_id', 'kode', 'nama', 'alamat', 'status']);

        if ($instansiId) $qb->where('instansi_id', $instansiId);
        if ($unitId) $qb->where('unit_organisasi_id', $unitId);
        if ($status) $qb->where('status', $status);
        if ($q) {
            $qb->where(function ($w) use ($q) {
                $w->where('nama', 'like', $q)->orWhere('kode', 'like', $q);
            });
        }

        $items = $qb->orderBy('nama')->limit($limit)->get();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function lokasiGudangList(Request $r)
    {
        $gudangId = $r->query('gudang_id');
        $q = $this->likeQ($r->query('q'));
        $status = $r->query('status');
        $limit = $this->limit($r, 80);

        $qb = DB::table('lokasi_gudang')
            ->select(['id', 'gudang_id', 'induk_id', 'tipe_lokasi', 'kode', 'nama', 'jalur', 'bisa_picking', 'status']);

        if ($gudangId) $qb->where('gudang_id', $gudangId);
        if ($status) $qb->where('status', $status);
        if ($q) {
            $qb->where(function ($w) use ($q) {
                $w->where('kode', 'like', $q)->orWhere('nama', 'like', $q)->orWhere('jalur', 'like', $q);
            });
        }

        $items = $qb->orderBy('kode')->limit($limit)->get();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function pemasokList(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $q = $this->likeQ($r->query('q'));
        $status = $r->query('status');
        $limit = $this->limit($r, 50);

        $qb = DB::table('pemasok')
            ->select(['id', 'instansi_id', 'kode', 'nama', 'npwp', 'nama_kontak', 'telepon', 'email', 'status']);

        if ($instansiId) $qb->where('instansi_id', $instansiId);
        if ($status) $qb->where('status', $status);
        if ($q) {
            $qb->where(function ($w) use ($q) {
                $w->where('nama', 'like', $q)->orWhere('kode', 'like', $q)->orWhere('npwp', 'like', $q);
            });
        }

        $items = $qb->orderBy('nama')->limit($limit)->get();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function poList(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $status = $r->query('status');
        $pemasokId = $r->query('pemasok_id');
        $limit = $this->limit($r, 50);

        $qb = DB::table('pesanan_pembelian as p')
            ->leftJoin('pemasok as s', 's.id', '=', 'p.pemasok_id')
            ->select([
                'p.id',
                'p.nomor_po',
                'p.tanggal_po',
                'p.tanggal_estimasi',
                'p.mata_uang',
                'p.subtotal',
                'p.pajak',
                'p.total',
                'p.status',
                'p.pemasok_id',
                's.nama as pemasok_nama',
            ]);

        if ($instansiId) $qb->where('p.instansi_id', $instansiId);
        if ($status) $qb->where('p.status', $status);
        if ($pemasokId) $qb->where('p.pemasok_id', $pemasokId);

        $qb = $this->dateRange($r, 'p.tanggal_po', $qb);

        $items = $qb->orderByDesc('p.tanggal_po')->limit($limit)->get();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function penerimaanList(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $gudangId = $r->query('gudang_id');
        $status = $r->query('status');
        $poId = $r->query('po_id') ?? $r->query('pesanan_pembelian_id');
        $limit = $this->limit($r, 50);

        $qb = DB::table('penerimaan as p')
            ->leftJoin('gudang as g', 'g.id', '=', 'p.gudang_id')
            ->leftJoin('pemasok as s', 's.id', '=', 'p.pemasok_id')
            ->select([
                'p.id',
                'p.nomor_penerimaan',
                'p.tanggal_penerimaan',
                'p.status',
                'p.gudang_id',
                'g.nama as gudang_nama',
                'p.pemasok_id',
                's.nama as pemasok_nama',
                'p.pesanan_pembelian_id',
            ]);

        if ($instansiId) $qb->where('p.instansi_id', $instansiId);
        if ($gudangId) $qb->where('p.gudang_id', $gudangId);
        if ($status) $qb->where('p.status', $status);
        if ($poId) $qb->where('p.pesanan_pembelian_id', $poId);

        $qb = $this->dateRange($r, 'p.tanggal_penerimaan', $qb);

        $items = $qb->orderByDesc('p.tanggal_penerimaan')->limit($limit)->get();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function qcList(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $status = $r->query('status');
        $penerimaanId = $r->query('penerimaan_id') ?? $r->query('penerimaanId');
        $limit = $this->limit($r, 50);

        $qb = DB::table('inspeksi_qc as q')
            ->join('penerimaan as p', 'p.id', '=', 'q.penerimaan_id')
            ->select([
                'q.id',
                'q.nomor_qc',
                'q.tanggal_qc',
                'q.status',
                'q.penerimaan_id',
                'p.nomor_penerimaan',
            ]);

        if ($instansiId) $qb->where('p.instansi_id', $instansiId);
        if ($status) $qb->where('q.status', $status);
        if ($penerimaanId) $qb->where('q.penerimaan_id', $penerimaanId);

        $qb = $this->dateRange($r, 'q.tanggal_qc', $qb);

        $items = $qb->orderByDesc('q.tanggal_qc')->limit($limit)->get();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function stokSaldo(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $gudangId = $r->query('gudang_id');
        $lokasiId = $r->query('lokasi_id');
        $barangId = $r->query('barang_id');
        $q = $this->likeQ($r->query('q'));
        $limit = $this->limit($r, 80);

        $qb = DB::table('saldo_stok as s')
            ->join('barang as b', 'b.id', '=', 's.barang_id')
            ->join('gudang as g', 'g.id', '=', 's.gudang_id')
            ->leftJoin('lokasi_gudang as l', 'l.id', '=', 's.lokasi_id')
            ->select([
                's.id',
                's.instansi_id',
                's.gudang_id',
                'g.nama as gudang_nama',
                's.lokasi_id',
                'l.kode as lokasi_kode',
                'l.nama as lokasi_nama',
                's.barang_id',
                'b.sku as barang_sku',
                'b.nama as barang_nama',
                's.no_lot',
                's.tanggal_kedaluwarsa',
                's.qty_tersedia',
                's.qty_dipesan',
                's.qty_bisa_dipakai',
                's.pergerakan_terakhir_pada',
            ]);

        if ($instansiId) $qb->where('s.instansi_id', $instansiId);
        if ($gudangId) $qb->where('s.gudang_id', $gudangId);
        if ($lokasiId) $qb->where('s.lokasi_id', $lokasiId);
        if ($barangId) $qb->where('s.barang_id', $barangId);
        if ($q) {
            $qb->where(function ($w) use ($q) {
                $w->where('b.nama', 'like', $q)
                    ->orWhere('b.sku', 'like', $q)
                    ->orWhere('g.nama', 'like', $q)
                    ->orWhere('l.kode', 'like', $q);
            });
        }

        $items = $qb->orderBy('b.nama')->limit($limit)->get();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function stokMutasi(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $jenis = $r->query('jenis') ?? $r->query('jenis_pergerakan');
        $gudangId = $r->query('gudang_id');
        $barangId = $r->query('barang_id');
        $limit = $this->limit($r, 80);

        $qb = DB::table('pergerakan_stok as p')
            ->join('detail_pergerakan_stok as d', 'd.pergerakan_stok_id', '=', 'p.id')
            ->join('barang as b', 'b.id', '=', 'd.barang_id')
            ->leftJoin('gudang as gd', 'gd.id', '=', 'd.dari_gudang_id')
            ->leftJoin('gudang as gk', 'gk.id', '=', 'd.ke_gudang_id')
            ->leftJoin('lokasi_gudang as ld', 'ld.id', '=', 'd.dari_lokasi_id')
            ->leftJoin('lokasi_gudang as lk', 'lk.id', '=', 'd.ke_lokasi_id')
            ->select([
                'p.id as pergerakan_id',
                'p.nomor_pergerakan',
                'p.jenis_pergerakan',
                'p.tipe_referensi',
                'p.id_referensi',
                'p.tanggal_pergerakan',
                'p.gudang_id',
                'p.status as status_pergerakan',
                'd.id as detail_id',
                'd.barang_id',
                'b.sku as barang_sku',
                'b.nama as barang_nama',
                'd.qty',
                'd.no_lot',
                'd.tanggal_kedaluwarsa',
                'd.dari_gudang_id',
                'gd.nama as dari_gudang_nama',
                'd.dari_lokasi_id',
                'ld.kode as dari_lokasi_kode',
                'd.ke_gudang_id',
                'gk.nama as ke_gudang_nama',
                'd.ke_lokasi_id',
                'lk.kode as ke_lokasi_kode',
            ]);

        if ($instansiId) $qb->where('p.instansi_id', $instansiId);
        if ($jenis) $qb->where('p.jenis_pergerakan', $jenis);
        if ($gudangId) {
            $qb->where(function ($w) use ($gudangId) {
                $w->where('p.gudang_id', $gudangId)
                    ->orWhere('d.dari_gudang_id', $gudangId)
                    ->orWhere('d.ke_gudang_id', $gudangId);
            });
        }
        if ($barangId) $qb->where('d.barang_id', $barangId);

        $qb = $this->dateRange($r, 'p.tanggal_pergerakan', $qb);

        $items = $qb->orderByDesc('p.tanggal_pergerakan')->limit($limit)->get();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function permintaanList(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $status = $r->query('status');
        $unitId = $r->query('unit_organisasi_id') ?? $r->query('unit_id');
        $pemohonId = $r->query('pemohon_id');
        $limit = $this->limit($r, 50);

        $qb = DB::table('permintaan as p')
            ->leftJoin('pengguna as u', 'u.id', '=', 'p.pemohon_id')
            ->leftJoin('unit_organisasi as un', 'un.id', '=', 'p.unit_organisasi_id')
            ->select([
                'p.id',
                'p.nomor_permintaan',
                'p.tanggal_permintaan',
                'p.tipe_permintaan',
                'p.prioritas',
                'p.status',
                'p.unit_organisasi_id',
                'un.nama as unit_nama',
                'p.pemohon_id',
                'u.nama_lengkap as pemohon_nama',
                'p.dibutuhkan_pada',
            ]);

        if ($instansiId) $qb->where('p.instansi_id', $instansiId);
        if ($status) $qb->where('p.status', $status);
        if ($unitId) $qb->where('p.unit_organisasi_id', $unitId);
        if ($pemohonId) $qb->where('p.pemohon_id', $pemohonId);

        $qb = $this->dateRange($r, 'p.tanggal_permintaan', $qb);

        $items = $qb->orderByDesc('p.tanggal_permintaan')->limit($limit)->get();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function pengeluaranList(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $gudangId = $r->query('gudang_id');
        $status = $r->query('status');
        $permintaanId = $r->query('permintaan_id');
        $limit = $this->limit($r, 50);

        $qb = DB::table('pengeluaran as p')
            ->leftJoin('gudang as g', 'g.id', '=', 'p.gudang_id')
            ->leftJoin('permintaan as rqt', 'rqt.id', '=', 'p.permintaan_id')
            ->select([
                'p.id',
                'p.nomor_pengeluaran',
                'p.tanggal_pengeluaran',
                'p.status',
                'p.gudang_id',
                'g.nama as gudang_nama',
                'p.permintaan_id',
                'rqt.nomor_permintaan',
            ]);

        if ($instansiId) $qb->where('p.instansi_id', $instansiId);
        if ($gudangId) $qb->where('p.gudang_id', $gudangId);
        if ($status) $qb->where('p.status', $status);
        if ($permintaanId) $qb->where('p.permintaan_id', $permintaanId);

        $qb = $this->dateRange($r, 'p.tanggal_pengeluaran', $qb);

        $items = $qb->orderByDesc('p.tanggal_pengeluaran')->limit($limit)->get();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function asetList(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $barangId = $r->query('barang_id');
        $unitId = $r->query('unit_id') ?? $r->query('unit_organisasi_id');
        $gudangId = $r->query('gudang_id');
        $statusSiklus = $r->query('status_siklus');
        $statusKondisi = $r->query('status_kondisi');
        $q = $this->likeQ($r->query('q'));
        $limit = $this->limit($r, 50);

        $qb = DB::table('aset as a')
            ->join('barang as b', 'b.id', '=', 'a.barang_id')
            ->leftJoin('unit_organisasi as un', 'un.id', '=', 'a.unit_organisasi_saat_ini_id')
            ->leftJoin('gudang as g', 'g.id', '=', 'a.gudang_saat_ini_id')
            ->leftJoin('lokasi_gudang as l', 'l.id', '=', 'a.lokasi_saat_ini_id')
            ->leftJoin('pengguna as u', 'u.id', '=', 'a.pemegang_pengguna_id')
            ->select([
                'a.id',
                'a.tag_aset',
                'a.no_serial',
                'a.status_kondisi',
                'a.status_siklus',
                'a.biaya_perolehan',
                'a.mata_uang',
                'a.barang_id',
                'b.sku as barang_sku',
                'b.nama as barang_nama',
                'a.unit_organisasi_saat_ini_id',
                'un.nama as unit_nama',
                'a.gudang_saat_ini_id',
                'g.nama as gudang_nama',
                'a.lokasi_saat_ini_id',
                'l.kode as lokasi_kode',
                'a.pemegang_pengguna_id',
                'u.nama_lengkap as pemegang_nama',
            ]);

        if ($instansiId) $qb->where('a.instansi_id', $instansiId);
        if ($barangId) $qb->where('a.barang_id', $barangId);
        if ($unitId) $qb->where('a.unit_organisasi_saat_ini_id', $unitId);
        if ($gudangId) $qb->where('a.gudang_saat_ini_id', $gudangId);
        if ($statusSiklus) $qb->where('a.status_siklus', $statusSiklus);
        if ($statusKondisi) $qb->where('a.status_kondisi', $statusKondisi);

        if ($q) {
            $qb->where(function ($w) use ($q) {
                $w->where('a.tag_aset', 'like', $q)
                    ->orWhere('a.no_serial', 'like', $q)
                    ->orWhere('b.nama', 'like', $q)
                    ->orWhere('b.sku', 'like', $q)
                    ->orWhere('u.nama_lengkap', 'like', $q);
            });
        }

        $items = $qb->orderBy('a.tag_aset')->limit($limit)->get();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }

    public function approvalPending(Request $r)
    {
        $instansiId = $r->query('instansi_id');
        $tipeEntitas = $r->query('tipe_entitas') ?? $r->query('entity_type');
        $limit = $this->limit($r, 50);

        $qb = DB::table('permintaan_persetujuan as p')
            ->leftJoin('pengguna as u', 'u.id', '=', 'p.diminta_oleh')
            ->select([
                'p.id',
                'p.nomor_persetujuan',
                'p.tipe_entitas',
                'p.id_entitas',
                'p.diminta_pada',
                'p.status',
                'p.langkah_saat_ini',
                'p.ringkasan',
                'p.diminta_oleh',
                'u.nama_lengkap as diminta_oleh_nama',
            ])
            ->where('p.status', 'menunggu');

        if ($instansiId) $qb->where('p.instansi_id', $instansiId);
        if ($tipeEntitas) $qb->where('p.tipe_entitas', $tipeEntitas);

        $qb = $this->dateRange($r, 'p.diminta_pada', $qb);

        $items = $qb->orderByDesc('p.diminta_pada')->limit($limit)->get();

        return response()->json(['items' => $items, 'meta' => ['limit' => $limit]]);
    }
}
