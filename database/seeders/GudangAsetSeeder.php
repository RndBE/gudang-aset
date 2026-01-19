<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class GudangAsetSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::transaction(function () use ($now) {
            $instansiId = DB::table('instansi')->insertGetId([
                'kode' => 'PT',
                'nama' => 'PT SUMBER MAKMUR JAYA ABADI',
                'alamat' => 'Jakarta',
                'telepon' => '021-000000',
                'email' => 'SUMBER@example.go.id',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $mabesId = DB::table('unit_organisasi')->insertGetId([
                'instansi_id' => $instansiId,
                'induk_id' => null,
                'tipe_unit' => 'lainnya',
                'kode' => 'tokobangunan-01',
                'nama' => 'toko bangunan sumber abadi',
                'alamat' => 'Jakarta',
                'telepon' => '021-111111',
                'email' => 'tokobangunan@example.go.id',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $cabangId = DB::table('unit_organisasi')->insertGetId([
                'instansi_id' => $instansiId,
                'induk_id' => null,
                'tipe_unit' => 'lainnya',
                'kode' => 'tokobangunan-02',
                'nama' => 'toko bangunan sumber makmur',
                'alamat' => 'Jawa Barat',
                'telepon' => '022-222222',
                'email' => 'sumbermakmur@example.go.id',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $izinRows = [
                ['kode' => 'master.lihat', 'nama' => 'Lihat master data'],
                ['kode' => 'master.kelola', 'nama' => 'Kelola master data'],
                ['kode' => 'rbac.lihat', 'nama' => 'Lihat pengguna & peran'],
                ['kode' => 'rbac.kelola', 'nama' => 'Kelola pengguna & peran'],
                ['kode' => 'pesanan_pembelian.lihat', 'nama' => 'Buat pesanan pembelian'],
                ['kode' => 'pesanan_pembelian.kelola', 'nama' => 'Ajukan pesanan pembelian'],
                ['kode' => 'penerimaan.buat', 'nama' => 'Buat penerimaan barang'],
                ['kode' => 'qc.proses', 'nama' => 'Proses inspeksi QC'],
                ['kode' => 'stok.lihat', 'nama' => 'Lihat stok'],
                ['kode' => 'stok.posting', 'nama' => 'Posting pergerakan stok'],
                ['kode' => 'permintaan.buat', 'nama' => 'Buat permintaan'],
                ['kode' => 'permintaan.ajukan', 'nama' => 'Ajukan permintaan'],
                ['kode' => 'permintaan.setujui', 'nama' => 'Setujui permintaan'],
                ['kode' => 'pengeluaran.buat', 'nama' => 'Buat pengeluaran'],
                ['kode' => 'aset.lihat', 'nama' => 'Lihat aset'],
                ['kode' => 'aset.kelola', 'nama' => 'Kelola aset'],
                ['kode' => 'aset.hapus', 'nama' => 'Penghapusan aset'],
                ['kode' => 'perawatan.kelola', 'nama' => 'Kelola perawatan'],
                ['kode' => 'opname.kelola', 'nama' => 'Kelola stok opname'],
                ['kode' => 'audit.lihat', 'nama' => 'Lihat audit log'],
                ['kode' => 'notif.lihat', 'nama' => 'Lihat notifikasi'],
                ['kode' => 'approval.proses', 'nama' => 'Proses persetujuan'],

                ['kode' => 'instansi.lihat', 'nama' => 'Lihat instansi'],
                ['kode' => 'instansi.kelola', 'nama' => 'Kelola instansi'],

                ['kode' => 'unit_org.lihat', 'nama' => 'Lihat unit organisasi'],
                ['kode' => 'unit_org.kelola', 'nama' => 'Kelola unit organisasi'],

                ['kode' => 'gudang.lihat', 'nama' => 'Lihat gudang'],
                ['kode' => 'gudang.kelola', 'nama' => 'Kelola gudang'],

                ['kode' => 'lokasi_gudang.lihat', 'nama' => 'Lihat lokasi gudang'],
                ['kode' => 'lokasi_gudang.kelola', 'nama' => 'Kelola lokasi gudang'],

                ['kode' => 'barang.lihat', 'nama' => 'Lihat barang'],
                ['kode' => 'barang.kelola', 'nama' => 'Kelola barang'],

                ['kode' => 'kategori_barang.lihat', 'nama' => 'Lihat kategori barang'],
                ['kode' => 'kategori_barang.kelola', 'nama' => 'Kelola kategori barang'],

                ['kode' => 'satuan_barang.lihat', 'nama' => 'Lihat satuan barang'],
                ['kode' => 'satuan_barang.kelola', 'nama' => 'Kelola satuan barang'],

                ['kode' => 'pemasok.lihat', 'nama' => 'Lihat pemasok'],
                ['kode' => 'pemasok.kelola', 'nama' => 'Kelola pemasok'],

                ['kode' => 'pengguna.lihat', 'nama' => 'Lihat pengguna'],
                ['kode' => 'pengguna.kelola', 'nama' => 'Kelola pengguna'],
                ['kode' => 'peran.lihat', 'nama' => 'Lihat peran'],
                ['kode' => 'peran.kelola', 'nama' => 'Kelola peran'],
                ['kode' => 'izin.lihat', 'nama' => 'Lihat izin'],
                ['kode' => 'izin.kelola', 'nama' => 'Kelola izin'],

                ['kode' => 'po.lihat', 'nama' => 'Lihat pesanan pembelian'],
                ['kode' => 'po.kelola', 'nama' => 'Kelola pesanan pembelian'],

                ['kode' => 'penerimaan.lihat', 'nama' => 'Lihat penerimaan'],
                ['kode' => 'penerimaan.kelola', 'nama' => 'Kelola penerimaan'],

                ['kode' => 'qc.lihat', 'nama' => 'Lihat inspeksi QC'],
                ['kode' => 'qc.kelola', 'nama' => 'Kelola inspeksi QC'],

                ['kode' => 'penugasan_aset.lihat', 'nama' => 'Lihat aset penugasan'],
                ['kode' => 'penugasan_aset.kelola', 'nama' => 'Kelola aset penugasan'],
                ['kode' => 'peminjaman_aset.lihat', 'nama' => 'Lihat aset peminjaman '],
                ['kode' => 'peminjaman_aset.kelola', 'nama' => 'Kelola aset peminjaman'],
                ['kode' => 'penghapusan_aset.lihat', 'nama' => 'Lihat aset penghapusan '],
                ['kode' => 'penghapusan_aset.kelola', 'nama' => 'Kelola aset penghapusan'],

                ['kode' => 'permintaan.lihat', 'nama' => 'Lihat permintaan'],
                ['kode' => 'permintaan.kelola', 'nama' => 'Kelola permintaan'],

                ['kode' => 'pengeluaran.lihat', 'nama' => 'Lihat pengeluaran'],
                ['kode' => 'pengeluaran.kelola', 'nama' => 'Kelola pengeluaran'],

                ['kode' => 'pergerakan_stok.lihat', 'nama' => 'Melihat Pergerakan Stok'],
                ['kode' => 'saldo_stok.lihat', 'nama' => 'Izin Melihat Stok'],

                ['kode' => 'transfer.lihat', 'nama' => 'Lihat transfer'],
                ['kode' => 'transfer.kelola', 'nama' => 'Kelola transfer'],

                ['kode' => 'opname.lihat', 'nama' => 'Lihat stok opname'],

                ['kode' => 'alur_persetujuan.lihat', 'nama' => 'Lihat alur persetujuan'],
                ['kode' => 'alur_persetujuan.kelola', 'nama' => 'Kelola alur persetujuan'],
                ['kode' => 'permintaan_persetujuan.lihat', 'nama' => 'Lihat permintaan persetujuan'],
                ['kode' => 'permintaan_persetujuan.kelola', 'nama' => 'Kelola permintaan persetujuan'],
            ];

            $izinMap = [];
            foreach ($izinRows as $r) {
                $izinMap[$r['kode']] = DB::table('izin')->insertGetId([
                    'kode' => $r['kode'],
                    'nama' => $r['nama'],
                    'deskripsi' => null,
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);
            }

            $peran = [];
            $peran['superadmin'] = DB::table('peran')->insertGetId([
                'instansi_id' => $instansiId,
                'kode' => 'superadmin',
                'nama' => 'Super Admin Instansi',
                'deskripsi' => 'Akses penuh',
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);
            $peran['kepala_unit'] = DB::table('peran')->insertGetId([
                'instansi_id' => $instansiId,
                'kode' => 'kepala_unit',
                'nama' => 'Kepala Unit',
                'deskripsi' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);
            $peran['kepala_gudang'] = DB::table('peran')->insertGetId([
                'instansi_id' => $instansiId,
                'kode' => 'kepala_gudang',
                'nama' => 'Kepala Gudang',
                'deskripsi' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);
            $peran['petugas_gudang'] = DB::table('peran')->insertGetId([
                'instansi_id' => $instansiId,
                'kode' => 'petugas_gudang',
                'nama' => 'Petugas Gudang',
                'deskripsi' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);
            $peran['pemohon'] = DB::table('peran')->insertGetId([
                'instansi_id' => $instansiId,
                'kode' => 'pemohon',
                'nama' => 'Pemohon',
                'deskripsi' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);
            $peran['pejabat_pengadaan'] = DB::table('peran')->insertGetId([
                'instansi_id' => $instansiId,
                'kode' => 'pejabat_pengadaan',
                'nama' => 'Pejabat Pengadaan',
                'deskripsi' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);
            $peran['keuangan'] = DB::table('peran')->insertGetId([
                'instansi_id' => $instansiId,
                'kode' => 'keuangan',
                'nama' => 'Keuangan',
                'deskripsi' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);
            $peran['auditor_aset'] = DB::table('peran')->insertGetId([
                'instansi_id' => $instansiId,
                'kode' => 'auditor_aset',
                'nama' => 'Auditor Aset',
                'deskripsi' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);
            $peran['teknisi'] = DB::table('peran')->insertGetId([
                'instansi_id' => $instansiId,
                'kode' => 'teknisi',
                'nama' => 'Teknisi/Perawatan',
                'deskripsi' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);

            $grant = [
                'superadmin' => array_keys($izinMap),
                'kepala_unit' => ['master.lihat', 'stok.lihat', 'permintaan.setujui', 'approval.proses', 'notif.lihat', 'aset.lihat', 'permintaan_persetujuan.kelola'],
                'kepala_gudang' => ['master.lihat', 'stok.lihat', 'pesanan_pembelian.kelola', 'permintaan.setujui', 'stok.posting', 'qc.proses', 'opname.kelola', 'audit.lihat', 'notif.lihat', 'aset.lihat', 'aset.kelola', 'aset.hapus', 'perawatan.kelola', 'approval.proses', 'permintaan_persetujuan.kelola'],
                'petugas_gudang' => ['master.lihat', 'stok.lihat', 'pesanan_pembelian.lihat', 'pesanan_pembelian.kelola', 'penerimaan.buat', 'pengeluaran.buat', 'qc.proses', 'stok.posting', 'opname.kelola', 'notif.lihat', 'aset.lihat', 'aset.kelola', 'approval.proses', 'permintaan_persetujuan.kelola'],
                'pemohon' => ['permintaan.buat', 'permintaan.ajukan', 'stok.lihat', 'aset.lihat', 'notif.lihat'],
                'pejabat_pengadaan' => ['pesanan_pembelian.kelola', 'approval.proses', 'audit.lihat', 'notif.lihat', 'permintaan_persetujuan.kelola'],
                'keuangan' => ['pesanan_pembelian.kelola', 'aset.hapus', 'approval.proses', 'audit.lihat', 'notif.lihat', 'permintaan_persetujuan.kelola'],
                'auditor_aset' => ['audit.lihat', 'aset.lihat', 'aset.hapus', 'approval.proses', 'notif.lihat', 'permintaan_persetujuan.kelola'],
                'teknisi' => ['perawatan.kelola', 'aset.lihat', 'notif.lihat'],
            ];

            foreach ($grant as $kodePeran => $izinKodeList) {
                $pid = $peran[$kodePeran];
                foreach ($izinKodeList as $ik) {
                    if (!isset($izinMap[$ik])) continue;
                    DB::table('peran_izin')->insert([
                        'peran_id' => $pid,
                        'izin_id' => $izinMap[$ik],
                        'dibuat_pada' => $now,
                    ]);
                }
            }

            $pengguna = [];
            $pengguna['superadmin'] = DB::table('pengguna')->insertGetId([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $mabesId,
                'username' => 'superadmin',
                'email' => 'superadmin@example.go.id',
                'telepon' => '081200000001',
                'hash_password' => Hash::make('password'),
                'nama_lengkap' => 'Super Admin',
                'nip_nrk' => '00000001',
                'pangkat' => 'Kombes',
                'jabatan' => 'Admin Sistem',
                'status' => 'aktif',
                'login_terakhir_pada' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);

            $pengguna['kepala_unit'] = DB::table('pengguna')->insertGetId([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $cabangId,
                'username' => 'kepala.unit',
                'email' => 'kepalaunit@example.go.id',
                'telepon' => '081200000010',
                'hash_password' => Hash::make('password'),
                'nama_lengkap' => 'Kepala Unit Cabang',
                'nip_nrk' => '00000010',
                'pangkat' => 'AKP',
                'jabatan' => 'Kepala Unit',
                'status' => 'aktif',
                'login_terakhir_pada' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);

            $pengguna['kepala_gudang'] = DB::table('pengguna')->insertGetId([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $cabangId,
                'username' => 'kepala.gudang',
                'email' => 'kepalagudang@example.go.id',
                'telepon' => '081200000002',
                'hash_password' => Hash::make('password'),
                'nama_lengkap' => 'Kepala Gudang Cabang',
                'nip_nrk' => '00000002',
                'pangkat' => 'AKP',
                'jabatan' => 'Kepala Gudang',
                'status' => 'aktif',
                'login_terakhir_pada' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);

            $pengguna['petugas_gudang'] = DB::table('pengguna')->insertGetId([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $cabangId,
                'username' => 'petugas.gudang',
                'email' => 'petugasgudang@example.go.id',
                'telepon' => '081200000003',
                'hash_password' => Hash::make('password'),
                'nama_lengkap' => 'Petugas Gudang Cabang',
                'nip_nrk' => '00000003',
                'pangkat' => 'AIPTU',
                'jabatan' => 'Petugas Gudang',
                'status' => 'aktif',
                'login_terakhir_pada' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);

            $pengguna['pemohon'] = DB::table('pengguna')->insertGetId([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $cabangId,
                'username' => 'pemohon.unit',
                'email' => 'pemohon@example.go.id',
                'telepon' => '081200000004',
                'hash_password' => Hash::make('password'),
                'nama_lengkap' => 'Pemohon Unit',
                'nip_nrk' => '00000004',
                'pangkat' => 'BRIPKA',
                'jabatan' => 'Staf Unit',
                'status' => 'aktif',
                'login_terakhir_pada' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);

            $pengguna['pejabat_pengadaan'] = DB::table('pengguna')->insertGetId([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $mabesId,
                'username' => 'pengadaan',
                'email' => 'pengadaan@example.go.id',
                'telepon' => '081200000020',
                'hash_password' => Hash::make('password'),
                'nama_lengkap' => 'Pejabat Pengadaan',
                'nip_nrk' => '00000020',
                'pangkat' => 'KOMPOL',
                'jabatan' => 'Pejabat Pengadaan',
                'status' => 'aktif',
                'login_terakhir_pada' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);

            $pengguna['keuangan'] = DB::table('pengguna')->insertGetId([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $mabesId,
                'username' => 'keuangan',
                'email' => 'keuangan@example.go.id',
                'telepon' => '081200000021',
                'hash_password' => Hash::make('password'),
                'nama_lengkap' => 'Bagian Keuangan',
                'nip_nrk' => '00000021',
                'pangkat' => 'KOMPOL',
                'jabatan' => 'Keuangan',
                'status' => 'aktif',
                'login_terakhir_pada' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);

            $pengguna['auditor_aset'] = DB::table('pengguna')->insertGetId([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $mabesId,
                'username' => 'auditor',
                'email' => 'auditor@example.go.id',
                'telepon' => '081200000022',
                'hash_password' => Hash::make('password'),
                'nama_lengkap' => 'Auditor Aset',
                'nip_nrk' => '00000022',
                'pangkat' => 'IPTU',
                'jabatan' => 'Auditor',
                'status' => 'aktif',
                'login_terakhir_pada' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);

            $pengguna['teknisi'] = DB::table('pengguna')->insertGetId([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $cabangId,
                'username' => 'teknisi',
                'email' => 'teknisi@example.go.id',
                'telepon' => '081200000030',
                'hash_password' => Hash::make('password'),
                'nama_lengkap' => 'Teknisi Perawatan',
                'nip_nrk' => '00000030',
                'pangkat' => 'BRIPTU',
                'jabatan' => 'Teknisi',
                'status' => 'aktif',
                'login_terakhir_pada' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now
            ]);

            $penggunaPeranRows = [
                ['pengguna_id' => $pengguna['superadmin'], 'peran_id' => $peran['superadmin']],
                ['pengguna_id' => $pengguna['kepala_unit'], 'peran_id' => $peran['kepala_unit']],
                ['pengguna_id' => $pengguna['kepala_gudang'], 'peran_id' => $peran['kepala_gudang']],
                ['pengguna_id' => $pengguna['petugas_gudang'], 'peran_id' => $peran['petugas_gudang']],
                ['pengguna_id' => $pengguna['pemohon'], 'peran_id' => $peran['pemohon']],
                ['pengguna_id' => $pengguna['pejabat_pengadaan'], 'peran_id' => $peran['pejabat_pengadaan']],
                ['pengguna_id' => $pengguna['keuangan'], 'peran_id' => $peran['keuangan']],
                ['pengguna_id' => $pengguna['auditor_aset'], 'peran_id' => $peran['auditor_aset']],
                ['pengguna_id' => $pengguna['teknisi'], 'peran_id' => $peran['teknisi']],
            ];

            foreach ($penggunaPeranRows as $r) {
                DB::table('pengguna_peran')->insert([
                    'pengguna_id' => $r['pengguna_id'],
                    'peran_id' => $r['peran_id'],
                    'dibuat_pada' => $now,
                ]);
            }

            $gudangId = DB::table('gudang')->insertGetId([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $cabangId,
                'kode' => 'GDG-01',
                'nama' => 'Gudang Cabang 01',
                'alamat' => 'Jawa Barat',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            for ($i = 2; $i <= 20; $i++) {
                DB::table('gudang')->insert([
                    'instansi_id' => $instansiId,
                    'unit_organisasi_id' => $cabangId,
                    'kode' => 'GDG-' . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                    'nama' => 'Gudang Cabang ' . $i,
                    'alamat' => 'Jawa Barat',
                    'status' => 'aktif',
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);
            }

            $zonaId = DB::table('lokasi_gudang')->insertGetId([
                'gudang_id' => $gudangId,
                'induk_id' => null,
                'tipe_lokasi' => 'zona',
                'kode' => 'ZONA-A',
                'nama' => 'Zona A',
                'jalur' => 'ZONA-A',
                'bisa_picking' => true,
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $rakId = DB::table('lokasi_gudang')->insertGetId([
                'gudang_id' => $gudangId,
                'induk_id' => $zonaId,
                'tipe_lokasi' => 'rak',
                'kode' => 'RAK-A1',
                'nama' => 'Rak A1',
                'jalur' => 'ZONA-A/RAK-A1',
                'bisa_picking' => true,
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $binId = DB::table('lokasi_gudang')->insertGetId([
                'gudang_id' => $gudangId,
                'induk_id' => $rakId,
                'tipe_lokasi' => 'bin',
                'kode' => 'BIN-A1-01',
                'nama' => 'Bin A1-01',
                'jalur' => 'ZONA-A/RAK-A1/BIN-A1-01',
                'bisa_picking' => true,
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $satuanPcsId = DB::table('satuan_barang')->insertGetId([
                'kode' => 'PCS',
                'nama' => 'Pcs',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $satuanBoxId = DB::table('satuan_barang')->insertGetId([
                'kode' => 'BOX',
                'nama' => 'Box',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $satuanPakId = DB::table('satuan_barang')->insertGetId([
                'kode' => 'PAK',
                'nama' => 'Pak',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $katElektronikId = DB::table('kategori_barang')->insertGetId([
                'instansi_id' => $instansiId,
                'induk_id' => null,
                'kode' => 'ELK',
                'nama' => 'Elektronik',
                'default_aset' => true,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $katATKId = DB::table('kategori_barang')->insertGetId([
                'instansi_id' => $instansiId,
                'induk_id' => null,
                'kode' => 'ATK',
                'nama' => 'ATK',
                'default_aset' => false,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $pemasokId = DB::table('pemasok')->insertGetId([
                'instansi_id' => $instansiId,
                'kode' => 'VENDOR-01',
                'nama' => 'PT Vendor Demo',
                'npwp' => null,
                'alamat' => 'Jakarta',
                'nama_kontak' => 'Admin Vendor',
                'telepon' => '021-999999',
                'email' => 'vendor@example.co.id',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            for ($i = 2; $i <= 20; $i++) {
                DB::table('pemasok')->insert([
                    'instansi_id' => $instansiId,
                    'kode' => 'VENDOR-' . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                    'nama' => 'PT Vendor Demo ' . $i,
                    'npwp' => null,
                    'alamat' => 'Jakarta',
                    'nama_kontak' => 'Kontak Vendor ' . $i,
                    'telepon' => '021-99' . str_pad((string)$i, 4, '0', STR_PAD_LEFT),
                    'email' => 'vendor' . $i . '@example.co.id',
                    'status' => 'aktif',
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);
            }

            $barangLaptopId = DB::table('barang')->insertGetId([
                'instansi_id' => $instansiId,
                'kategori_id' => $katElektronikId,
                'satuan_id' => $satuanPcsId,
                'sku' => 'LPT-001',
                'nama' => 'Laptop Operasional',
                'merek' => 'DemoBrand',
                'model' => 'D-14',
                'spesifikasi' => json_encode(['ram' => '16GB', 'storage' => '512GB']),
                'tipe_barang' => 'aset',
                'metode_pelacakan' => 'serial',
                'stok_minimum' => 0,
                'titik_pesan_ulang' => 0,
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $barangKertasId = DB::table('barang')->insertGetId([
                'instansi_id' => $instansiId,
                'kategori_id' => $katATKId,
                'satuan_id' => $satuanBoxId,
                'sku' => 'ATK-A4',
                'nama' => 'Kertas A4',
                'merek' => 'DemoPaper',
                'model' => null,
                'spesifikasi' => null,
                'tipe_barang' => 'habis_pakai',
                'metode_pelacakan' => 'tanpa',
                'stok_minimum' => 5,
                'titik_pesan_ulang' => 10,
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $barangAset = [
                ['sku' => 'AST-LPT-002', 'nama' => 'Laptop Operasional Cadangan', 'merek' => 'DemoBrand', 'model' => 'D-14', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'serial'],
                ['sku' => 'AST-PRN-001', 'nama' => 'Printer Laser', 'merek' => 'DemoPrint', 'model' => 'LP-01', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'serial'],
                ['sku' => 'AST-SCN-001', 'nama' => 'Scanner Dokumen', 'merek' => 'DemoScan', 'model' => 'SC-10', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'serial'],
                ['sku' => 'AST-PRO-001', 'nama' => 'Proyektor Meeting', 'merek' => 'DemoProject', 'model' => 'PJ-01', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'serial'],
                ['sku' => 'AST-UPS-001', 'nama' => 'UPS 1200VA', 'merek' => 'DemoUPS', 'model' => 'UPS-12', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'serial'],
                ['sku' => 'AST-RTR-001', 'nama' => 'Router Kantor', 'merek' => 'DemoNet', 'model' => 'RT-01', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'serial'],
                ['sku' => 'AST-SWT-001', 'nama' => 'Switch 24 Port', 'merek' => 'DemoNet', 'model' => 'SW-24', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'serial'],
                ['sku' => 'AST-CCTV-001', 'nama' => 'Kamera CCTV Indoor', 'merek' => 'DemoCam', 'model' => 'CV-01', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'serial'],
                ['sku' => 'AST-CCTV-002', 'nama' => 'Kamera CCTV Outdoor', 'merek' => 'DemoCam', 'model' => 'CV-02', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'serial'],
                ['sku' => 'AST-ABS-001', 'nama' => 'Mesin Absensi Fingerprint', 'merek' => 'DemoOffice', 'model' => 'FP-01', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'serial'],
                ['sku' => 'AST-SRV-001', 'nama' => 'Server Mini', 'merek' => 'DemoServer', 'model' => 'SV-01', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'serial'],
                ['sku' => 'AST-MON-001', 'nama' => 'Monitor 24 inch', 'merek' => 'DemoDisplay', 'model' => 'MN-24', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'tanpa'],
                ['sku' => 'AST-MON-002', 'nama' => 'Monitor 27 inch', 'merek' => 'DemoDisplay', 'model' => 'MN-27', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'tanpa'],
                ['sku' => 'AST-TBL-001', 'nama' => 'Tablet Operasional', 'merek' => 'DemoTab', 'model' => 'TB-10', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'serial'],
                ['sku' => 'AST-HP-001', 'nama' => 'Smartphone Operasional', 'merek' => 'DemoPhone', 'model' => 'PH-01', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'serial'],
                ['sku' => 'AST-LEM-001', 'nama' => 'Lemari Arsip Besi', 'merek' => 'DemoFurn', 'model' => 'LM-01', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'tanpa'],
                ['sku' => 'AST-MEJ-001', 'nama' => 'Meja Kerja', 'merek' => 'DemoFurn', 'model' => 'MJ-01', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'tanpa'],
                ['sku' => 'AST-KRS-001', 'nama' => 'Kursi Kantor', 'merek' => 'DemoFurn', 'model' => 'KS-01', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'tanpa'],
                ['sku' => 'AST-AC-001', 'nama' => 'AC Split 1 PK', 'merek' => 'DemoCool', 'model' => 'AC-1', 'kat' => $katElektronikId, 'sat' => $satuanPcsId, 'track' => 'serial'],
            ];

            $barangHabis = [
                ['sku' => 'ATK-PEN-001', 'nama' => 'Pulpen Hitam', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-PEN-002', 'nama' => 'Pulpen Biru', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-SPD-001', 'nama' => 'Spidol Board', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-MAP-001', 'nama' => 'Map Folder', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-AMP-001', 'nama' => 'Amplop Coklat', 'sat' => $satuanBoxId],
                ['sku' => 'ATK-LAK-001', 'nama' => 'Lakban', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-KBL-001', 'nama' => 'Kabel LAN 10m', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-MSE-001', 'nama' => 'Mouse USB', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-KBD-001', 'nama' => 'Keyboard USB', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-FLD-001', 'nama' => 'Flashdisk 32GB', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-BTR-001', 'nama' => 'Baterai AA', 'sat' => $satuanPakId],
                ['sku' => 'ATK-BTR-002', 'nama' => 'Baterai AAA', 'sat' => $satuanPakId],
                ['sku' => 'ATK-TNR-001', 'nama' => 'Toner Printer', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-INK-001', 'nama' => 'Tinta Printer', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-GLV-001', 'nama' => 'Sarung Tangan', 'sat' => $satuanPakId],
                ['sku' => 'ATK-MSK-001', 'nama' => 'Masker', 'sat' => $satuanPakId],
                ['sku' => 'ATK-BLT-001', 'nama' => 'Baut 10mm', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-MUR-001', 'nama' => 'Mur 10mm', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-OBG-001', 'nama' => 'Obeng Set', 'sat' => $satuanPakId],
                ['sku' => 'ATK-CTN-001', 'nama' => 'Cotton Bud', 'sat' => $satuanBoxId],
                ['sku' => 'ATK-FRM-001', 'nama' => 'Formulir Blanko', 'sat' => $satuanBoxId],
                ['sku' => 'ATK-BKM-001', 'nama' => 'Buku Tulis', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-PLS-001', 'nama' => 'Plastik Map', 'sat' => $satuanPakId],
                ['sku' => 'ATK-STR-001', 'nama' => 'Stapler', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-ISI-001', 'nama' => 'Isi Stapler', 'sat' => $satuanBoxId],
                ['sku' => 'ATK-GLU-001', 'nama' => 'Lem Kertas', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-SCI-001', 'nama' => 'Gunting', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-RLR-001', 'nama' => 'Penggaris', 'sat' => $satuanPcsId],
                ['sku' => 'ATK-PPR-001', 'nama' => 'Kertas F4', 'sat' => $satuanBoxId],
                ['sku' => 'ATK-CLN-001', 'nama' => 'Pembersih Ruangan', 'sat' => $satuanPcsId],
            ];

            $barangIdsAset = [$barangLaptopId];
            foreach ($barangAset as $b) {
                $barangIdsAset[] = DB::table('barang')->insertGetId([
                    'instansi_id' => $instansiId,
                    'kategori_id' => $b['kat'],
                    'satuan_id' => $b['sat'],
                    'sku' => $b['sku'],
                    'nama' => $b['nama'],
                    'merek' => $b['merek'],
                    'model' => $b['model'],
                    'spesifikasi' => null,
                    'tipe_barang' => 'aset',
                    'metode_pelacakan' => $b['track'],
                    'stok_minimum' => 0,
                    'titik_pesan_ulang' => 0,
                    'status' => 'aktif',
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);
            }

            $barangIdsHabis = [$barangKertasId];
            foreach ($barangHabis as $b) {
                $barangIdsHabis[] = DB::table('barang')->insertGetId([
                    'instansi_id' => $instansiId,
                    'kategori_id' => $katATKId,
                    'satuan_id' => $b['sat'],
                    'sku' => $b['sku'],
                    'nama' => $b['nama'],
                    'merek' => null,
                    'model' => null,
                    'spesifikasi' => null,
                    'tipe_barang' => 'habis_pakai',
                    'metode_pelacakan' => 'tanpa',
                    'stok_minimum' => 5,
                    'titik_pesan_ulang' => 10,
                    'status' => 'aktif',
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);
            }

            $saldoMap = [];
            foreach ($barangIdsAset as $bid) {
                $qty = rand(1, 3);
                $saldoMap[$bid] = DB::table('saldo_stok')->insertGetId([
                    'instansi_id' => $instansiId,
                    'gudang_id' => $gudangId,
                    'lokasi_id' => $binId,
                    'barang_id' => $bid,
                    'no_lot' => null,
                    'tanggal_kedaluwarsa' => null,
                    'qty_tersedia' => $qty,
                    'qty_dipesan' => 0,
                    'qty_bisa_dipakai' => $qty,
                    'pergerakan_terakhir_pada' => $now,
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);
            }

            foreach ($barangIdsHabis as $bid) {
                $qty = rand(50, 250);
                $saldoMap[$bid] = DB::table('saldo_stok')->insertGetId([
                    'instansi_id' => $instansiId,
                    'gudang_id' => $gudangId,
                    'lokasi_id' => $binId,
                    'barang_id' => $bid,
                    'no_lot' => null,
                    'tanggal_kedaluwarsa' => null,
                    'qty_tersedia' => $qty,
                    'qty_dipesan' => 0,
                    'qty_bisa_dipakai' => $qty,
                    'pergerakan_terakhir_pada' => $now,
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);
            }

            $pergerakanStokAwalId = DB::table('pergerakan_stok')->insertGetId([
                'instansi_id' => $instansiId,
                'nomor_pergerakan' => 'MOVE-STOKAWAL-2026-0001',
                'jenis_pergerakan' => 'penerimaan',
                'tipe_referensi' => 'stok_awal',
                'id_referensi' => null,
                'tanggal_pergerakan' => $now,
                'gudang_id' => $gudangId,
                'catatan' => 'Stok awal sistem',
                'diposting_oleh' => $pengguna['kepala_gudang'],
                'status' => 'diposting',
                'dibuat_oleh' => $pengguna['superadmin'],
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $detailRows = [];
            foreach ($saldoMap as $barangId => $saldoId) {
                $qty = DB::table('saldo_stok')->where('id', $saldoId)->value('qty_tersedia');
                $detailRows[] = [
                    'pergerakan_stok_id' => $pergerakanStokAwalId,
                    'barang_id' => $barangId,
                    'dari_gudang_id' => null,
                    'dari_lokasi_id' => null,
                    'ke_gudang_id' => $gudangId,
                    'ke_lokasi_id' => $binId,
                    'no_lot' => null,
                    'tanggal_kedaluwarsa' => null,
                    'qty' => $qty,
                    'biaya_satuan' => 0,
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ];
            }
            DB::table('detail_pergerakan_stok')->insert($detailRows);

            // $kontrakId = DB::table('kontrak')->insertGetId([
            //     'instansi_id' => $instansiId,
            //     'unit_organisasi_id' => $cabangId,
            //     'pemasok_id' => $pemasokId,
            //     'nomor_kontrak' => 'KONTRAK-2026-001',
            //     'tanggal_kontrak' => $now->toDateString(),
            //     'mulai_tanggal' => $now->toDateString(),
            //     'selesai_tanggal' => $now->copy()->addMonths(6)->toDateString(),
            //     'nilai_total' => 25000000,
            //     'mata_uang' => 'IDR',
            //     'status' => 'aktif',
            //     'catatan' => null,
            //     'dibuat_oleh' => $pengguna['petugas_gudang'],
            //     'dibuat_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);

            // $poId = DB::table('pesanan_pembelian')->insertGetId([
            //     'instansi_id' => $instansiId,
            //     'unit_organisasi_id' => $cabangId,
            //     'pemasok_id' => $pemasokId,
            //     'kontrak_id' => $kontrakId,
            //     'nomor_po' => 'PO-2026-0001',
            //     'tanggal_po' => $now->toDateString(),
            //     'tanggal_estimasi' => $now->copy()->addDays(7)->toDateString(),
            //     'mata_uang' => 'IDR',
            //     'subtotal' => 0,
            //     'pajak' => 0,
            //     'total' => 0,
            //     'status' => 'disetujui',
            //     'catatan' => null,
            //     'dibuat_oleh' => $pengguna['petugas_gudang'],
            //     'dibuat_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);

            // $poLaptopDetailId = DB::table('pesanan_pembelian_detail')->insertGetId([
            //     'pesanan_pembelian_id' => $poId,
            //     'barang_id' => $barangLaptopId,
            //     'deskripsi' => 'Laptop untuk operasional',
            //     'qty' => 2,
            //     'harga_satuan' => 9000000,
            //     'tarif_pajak' => 0,
            //     'nilai_pajak' => 0,
            //     'total_baris' => 18000000,
            //     'dibuat_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);

            // $poKertasDetailId = DB::table('pesanan_pembelian_detail')->insertGetId([
            //     'pesanan_pembelian_id' => $poId,
            //     'barang_id' => $barangKertasId,
            //     'deskripsi' => 'Kertas A4',
            //     'qty' => 20,
            //     'harga_satuan' => 60000,
            //     'tarif_pajak' => 0,
            //     'nilai_pajak' => 0,
            //     'total_baris' => 1200000,
            //     'dibuat_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);

            // $subtotal = 18000000 + 1200000;
            // DB::table('pesanan_pembelian')->where('id', $poId)->update([
            //     'subtotal' => $subtotal,
            //     'pajak' => 0,
            //     'total' => $subtotal,
            //     'diubah_pada' => $now,
            // ]);

            // $penerimaanId = DB::table('penerimaan')->insertGetId([
            //     'instansi_id' => $instansiId,
            //     'gudang_id' => $gudangId,
            //     'pemasok_id' => $pemasokId,
            //     'pesanan_pembelian_id' => $poId,
            //     'nomor_penerimaan' => 'GR-2026-0001',
            //     'tanggal_penerimaan' => $now->toDateString(),
            //     'diterima_oleh' => $pengguna['petugas_gudang'],
            //     'status' => 'diposting',
            //     'catatan' => null,
            //     'dibuat_oleh' => $pengguna['petugas_gudang'],
            //     'dibuat_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);

            // $penerimaanLaptopId = DB::table('penerimaan_detail')->insertGetId([
            //     'penerimaan_id' => $penerimaanId,
            //     'barang_id' => $barangLaptopId,
            //     'po_detail_id' => $poLaptopDetailId,
            //     'qty_diterima' => 2,
            //     'no_lot' => null,
            //     'tanggal_kedaluwarsa' => null,
            //     'biaya_satuan' => 9000000,
            //     'lokasi_id' => $binId,
            //     'catatan' => null,
            //     'dibuat_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);

            // $penerimaanKertasId = DB::table('penerimaan_detail')->insertGetId([
            //     'penerimaan_id' => $penerimaanId,
            //     'barang_id' => $barangKertasId,
            //     'po_detail_id' => $poKertasDetailId,
            //     'qty_diterima' => 20,
            //     'no_lot' => null,
            //     'tanggal_kedaluwarsa' => null,
            //     'biaya_satuan' => 60000,
            //     'lokasi_id' => $binId,
            //     'catatan' => null,
            //     'dibuat_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);

            // $qcId = DB::table('inspeksi_qc')->insertGetId([
            //     'penerimaan_id' => $penerimaanId,
            //     'nomor_qc' => 'QC-2026-0001',
            //     'tanggal_qc' => $now->toDateString(),
            //     'pemeriksa_id' => $pengguna['kepala_gudang'],
            //     'status' => 'lulus',
            //     'ringkasan' => 'QC lulus',
            //     'dibuat_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);

            // DB::table('inspeksi_qc_detail')->insert([
            //     [
            //         'inspeksi_qc_id' => $qcId,
            //         'penerimaan_detail_id' => $penerimaanLaptopId,
            //         'hasil' => 'lulus',
            //         'catatan_cacat' => null,
            //         'qty_diterima' => 2,
            //         'qty_ditolak' => 0,
            //         'dibuat_pada' => $now,
            //         'diubah_pada' => $now,
            //     ],
            //     [
            //         'inspeksi_qc_id' => $qcId,
            //         'penerimaan_detail_id' => $penerimaanKertasId,
            //         'hasil' => 'lulus',
            //         'catatan_cacat' => null,
            //         'qty_diterima' => 20,
            //         'qty_ditolak' => 0,
            //         'dibuat_pada' => $now,
            //         'diubah_pada' => $now,
            //     ],
            // ]);

            // $pergerakanTerimaId = DB::table('pergerakan_stok')->insertGetId([
            //     'instansi_id' => $instansiId,
            //     'nomor_pergerakan' => 'MOVE-2026-0001',
            //     'jenis_pergerakan' => 'penerimaan',
            //     'tipe_referensi' => 'penerimaan',
            //     'id_referensi' => $penerimaanId,
            //     'tanggal_pergerakan' => $now,
            //     'gudang_id' => $gudangId,
            //     'catatan' => null,
            //     'diposting_oleh' => $pengguna['petugas_gudang'],
            //     'status' => 'diposting',
            //     'dibuat_oleh' => $pengguna['petugas_gudang'],
            //     'dibuat_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);

            // DB::table('detail_pergerakan_stok')->insert([
            //     [
            //         'pergerakan_stok_id' => $pergerakanTerimaId,
            //         'barang_id' => $barangKertasId,
            //         'dari_gudang_id' => null,
            //         'dari_lokasi_id' => null,
            //         'ke_gudang_id' => $gudangId,
            //         'ke_lokasi_id' => $binId,
            //         'no_lot' => null,
            //         'tanggal_kedaluwarsa' => null,
            //         'qty' => 20,
            //         'biaya_satuan' => 60000,
            //         'dibuat_pada' => $now,
            //         'diubah_pada' => $now,
            //     ],
            //     [
            //         'pergerakan_stok_id' => $pergerakanTerimaId,
            //         'barang_id' => $barangKertasId,
            //         'dari_gudang_id' => null,
            //         'dari_lokasi_id' => null,
            //         'ke_gudang_id' => $gudangId,
            //         'ke_lokasi_id' => $binId,
            //         'no_lot' => null,
            //         'tanggal_kedaluwarsa' => null,
            //         'qty' => 20,
            //         'biaya_satuan' => 60000,
            //         'dibuat_pada' => $now,
            //         'diubah_pada' => $now,
            //     ],
            // ]);

            // DB::table('saldo_stok')->where('gudang_id', $gudangId)->where('lokasi_id', $binId)->where('barang_id', $barangKertasId)->update([
            //     'qty_tersedia' => DB::raw('qty_tersedia + 20'),
            //     'qty_bisa_dipakai' => DB::raw('qty_bisa_dipakai + 20'),
            //     'pergerakan_terakhir_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);
            $kontrakId = DB::table('kontrak')->insertGetId([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $cabangId,
                'pemasok_id' => $pemasokId,
                'nomor_kontrak' => 'KONTRAK-2026-001',
                'tanggal_kontrak' => $now->toDateString(),
                'mulai_tanggal' => $now->toDateString(),
                'selesai_tanggal' => $now->copy()->addMonths(6)->toDateString(),
                'nilai_total' => 25000000,
                'mata_uang' => 'IDR',
                'status' => 'aktif',
                'catatan' => null,
                'dibuat_oleh' => $pengguna['petugas_gudang'],
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $barangDbMap = [];
            $barangRows = DB::table('barang')->where('instansi_id', $instansiId)->get();
            foreach ($barangRows as $br) {
                $barangDbMap[$br->id] = $br;
            }

            $ensureSaldo = function (int $barangId) use ($instansiId, $gudangId, $binId, $now) {
                $saldo = DB::table('saldo_stok')
                    ->where('instansi_id', $instansiId)
                    ->where('gudang_id', $gudangId)
                    ->where('lokasi_id', $binId)
                    ->where('barang_id', $barangId)
                    ->first();

                if ($saldo) return $saldo->id;

                return DB::table('saldo_stok')->insertGetId([
                    'instansi_id' => $instansiId,
                    'gudang_id' => $gudangId,
                    'lokasi_id' => $binId,
                    'barang_id' => $barangId,
                    'no_lot' => null,
                    'tanggal_kedaluwarsa' => null,
                    'qty_tersedia' => 0,
                    'qty_dipesan' => 0,
                    'qty_bisa_dipakai' => 0,
                    'pergerakan_terakhir_pada' => $now,
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);
            };

            $incSaldo = function (int $barangId, int $qty) use ($instansiId, $gudangId, $binId, $now, $ensureSaldo) {
                $ensureSaldo($barangId);

                DB::table('saldo_stok')
                    ->where('instansi_id', $instansiId)
                    ->where('gudang_id', $gudangId)
                    ->where('lokasi_id', $binId)
                    ->where('barang_id', $barangId)
                    ->update([
                        'qty_tersedia' => DB::raw('qty_tersedia + ' . (int)$qty),
                        'qty_bisa_dipakai' => DB::raw('qty_bisa_dipakai + ' . (int)$qty),
                        'pergerakan_terakhir_pada' => $now,
                        'diubah_pada' => $now,
                    ]);
            };

            $decSaldo = function (int $barangId, int $qty) use ($instansiId, $gudangId, $binId, $now, $ensureSaldo) {
                $ensureSaldo($barangId);

                $row = DB::table('saldo_stok')
                    ->where('instansi_id', $instansiId)
                    ->where('gudang_id', $gudangId)
                    ->where('lokasi_id', $binId)
                    ->where('barang_id', $barangId)
                    ->first();

                $available = (int)($row->qty_tersedia ?? 0);
                $take = min($available, (int)$qty);

                if ($take <= 0) return 0;

                DB::table('saldo_stok')
                    ->where('id', $row->id)
                    ->update([
                        'qty_tersedia' => DB::raw('GREATEST(qty_tersedia - ' . $take . ', 0)'),
                        'qty_bisa_dipakai' => DB::raw('GREATEST(qty_bisa_dipakai - ' . $take . ', 0)'),
                        'pergerakan_terakhir_pada' => $now,
                        'diubah_pada' => $now,
                    ]);

                return $take;
            };

            $makeNomor = function (string $prefix, int $n, int $pad = 4) {
                return $prefix . '-' . date('Y') . '-' . str_pad((string)$n, $pad, '0', STR_PAD_LEFT);
            };

            $barangSemua = array_values(array_unique(array_merge($barangIdsAset, $barangIdsHabis)));

            for ($t = 1; $t <= 10; $t++) {

                $poId = DB::table('pesanan_pembelian')->insertGetId([
                    'instansi_id' => $instansiId,
                    'unit_organisasi_id' => $cabangId,
                    'pemasok_id' => $pemasokId,
                    'kontrak_id' => $kontrakId,
                    'nomor_po' => $makeNomor('PO', $t),
                    'tanggal_po' => $now->copy()->addDays($t)->toDateString(),
                    'tanggal_estimasi' => $now->copy()->addDays($t + 7)->toDateString(),
                    'mata_uang' => 'IDR',
                    'subtotal' => 0,
                    'pajak' => 0,
                    'total' => 0,
                    'status' => 'disetujui',
                    'catatan' => null,
                    'dibuat_oleh' => $pengguna['petugas_gudang'],
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);

                $nDetail = rand(2, 5);
                $picked = [];
                while (count($picked) < $nDetail) {
                    $bid = $barangSemua[array_rand($barangSemua)];
                    $picked[$bid] = true;
                }
                $picked = array_keys($picked);

                $subtotal = 0;
                $poDetailMap = [];

                foreach ($picked as $bid) {
                    $br = $barangDbMap[$bid] ?? null;
                    if (!$br) continue;

                    $qty = ($br->tipe_barang === 'aset') ? rand(1, 3) : rand(10, 50);
                    $harga = ($br->tipe_barang === 'aset') ? rand(1500000, 12000000) : rand(10000, 150000);
                    $totalBaris = $qty * $harga;
                    $subtotal += $totalBaris;

                    $poDetailId = DB::table('pesanan_pembelian_detail')->insertGetId([
                        'pesanan_pembelian_id' => $poId,
                        'barang_id' => $bid,
                        'deskripsi' => $br->nama,
                        'qty' => $qty,
                        'harga_satuan' => $harga,
                        'tarif_pajak' => 0,
                        'nilai_pajak' => 0,
                        'total_baris' => $totalBaris,
                        'dibuat_pada' => $now,
                        'diubah_pada' => $now,
                    ]);

                    $poDetailMap[] = [
                        'id' => $poDetailId,
                        'barang_id' => $bid,
                        'qty' => $qty,
                        'harga' => $harga,
                    ];
                }

                DB::table('pesanan_pembelian')->where('id', $poId)->update([
                    'subtotal' => $subtotal,
                    'pajak' => 0,
                    'total' => $subtotal,
                    'diubah_pada' => $now,
                ]);

                $penerimaanId = DB::table('penerimaan')->insertGetId([
                    'instansi_id' => $instansiId,
                    'gudang_id' => $gudangId,
                    'pemasok_id' => $pemasokId,
                    'pesanan_pembelian_id' => $poId,
                    'nomor_penerimaan' => $makeNomor('GR', $t),
                    'tanggal_penerimaan' => $now->copy()->addDays($t)->toDateString(),
                    'diterima_oleh' => $pengguna['petugas_gudang'],
                    'status' => 'diposting',
                    'catatan' => null,
                    'dibuat_oleh' => $pengguna['petugas_gudang'],
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);

                $penerimaanDetailIds = [];
                foreach ($poDetailMap as $d) {
                    $pdId = DB::table('penerimaan_detail')->insertGetId([
                        'penerimaan_id' => $penerimaanId,
                        'barang_id' => $d['barang_id'],
                        'po_detail_id' => $d['id'],
                        'qty_diterima' => $d['qty'],
                        'no_lot' => null,
                        'tanggal_kedaluwarsa' => null,
                        'biaya_satuan' => $d['harga'],
                        'lokasi_id' => $binId,
                        'catatan' => null,
                        'dibuat_pada' => $now,
                        'diubah_pada' => $now,
                    ]);

                    $penerimaanDetailIds[] = [
                        'penerimaan_detail_id' => $pdId,
                        'barang_id' => $d['barang_id'],
                        'qty' => $d['qty'],
                        'harga' => $d['harga'],
                    ];
                }

                $qcId = DB::table('inspeksi_qc')->insertGetId([
                    'penerimaan_id' => $penerimaanId,
                    'nomor_qc' => $makeNomor('QC', $t),
                    'tanggal_qc' => $now->copy()->addDays($t)->toDateString(),
                    'pemeriksa_id' => $pengguna['kepala_gudang'],
                    'status' => 'lulus',
                    'ringkasan' => 'QC lulus',
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);

                $qcDetailRows = [];
                foreach ($penerimaanDetailIds as $pd) {
                    $qcDetailRows[] = [
                        'inspeksi_qc_id' => $qcId,
                        'inspeksi_qc_detail_id' => null,
                        'penerimaan_detail_id' => $pd['penerimaan_detail_id'],
                        'hasil' => 'lulus',
                        'catatan_cacat' => null,
                        'qty_diterima' => $pd['qty'],
                        'qty_ditolak' => 0,
                        'dibuat_pada' => $now,
                        'diubah_pada' => $now,
                    ];
                }
                foreach ($qcDetailRows as &$r) unset($r['inspeksi_qc_detail_id']);
                DB::table('inspeksi_qc_detail')->insert($qcDetailRows);

                $pergerakanTerimaId = DB::table('pergerakan_stok')->insertGetId([
                    'instansi_id' => $instansiId,
                    'nomor_pergerakan' => $makeNomor('MOVE-IN', $t),
                    'jenis_pergerakan' => 'penerimaan',
                    'tipe_referensi' => 'penerimaan',
                    'id_referensi' => $penerimaanId,
                    'tanggal_pergerakan' => $now,
                    'gudang_id' => $gudangId,
                    'catatan' => null,
                    'diposting_oleh' => $pengguna['petugas_gudang'],
                    'status' => 'diposting',
                    'dibuat_oleh' => $pengguna['petugas_gudang'],
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);

                $detailInRows = [];
                foreach ($penerimaanDetailIds as $pd) {
                    $detailInRows[] = [
                        'pergerakan_stok_id' => $pergerakanTerimaId,
                        'barang_id' => $pd['barang_id'],
                        'dari_gudang_id' => null,
                        'dari_lokasi_id' => null,
                        'ke_gudang_id' => $gudangId,
                        'ke_lokasi_id' => $binId,
                        'no_lot' => null,
                        'tanggal_kedaluwarsa' => null,
                        'qty' => $pd['qty'],
                        'biaya_satuan' => $pd['harga'],
                        'dibuat_pada' => $now,
                        'diubah_pada' => $now,
                    ];

                    $incSaldo($pd['barang_id'], $pd['qty']);
                }
                DB::table('detail_pergerakan_stok')->insert($detailInRows);
            }

            for ($t = 1; $t <= 10; $t++) {

                $permintaanId = DB::table('permintaan')->insertGetId([
                    'instansi_id' => $instansiId,
                    'unit_organisasi_id' => $cabangId,
                    'nomor_permintaan' => $makeNomor('REQ', $t),
                    'tanggal_permintaan' => $now->copy()->addDays($t)->toDateString(),
                    'pemohon_id' => $pengguna['pemohon'],
                    'tipe_permintaan' => 'habis_pakai',
                    'prioritas' => 'normal',
                    'status' => 'disetujui',
                    'tujuan' => 'Kebutuhan operasional unit',
                    'dibutuhkan_pada' => $now->copy()->addDays($t + 2)->toDateString(),
                    'catatan_persetujuan' => null,
                    'dibuat_oleh' => $pengguna['pemohon'],
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);

                $nDetail = rand(2, 5);
                $picked = [];
                while (count($picked) < $nDetail) {
                    $bid = $barangIdsHabis[array_rand($barangIdsHabis)];
                    $picked[$bid] = true;
                }
                $picked = array_keys($picked);

                $permintaanDetailRows = [];
                $pengeluaranDetailRows = [];

                foreach ($picked as $bid) {
                    $reqQty = rand(1, 10);
                    $take = $decSaldo($bid, $reqQty);
                    if ($take <= 0) continue;

                    $permintaanDetailRows[] = [
                        'permintaan_id' => $permintaanId,
                        'barang_id' => $bid,
                        'qty_diminta' => $reqQty,
                        'qty_disetujui' => $take,
                        'qty_dipenuhi' => 0,
                        'catatan' => null,
                        'dibuat_pada' => $now,
                        'diubah_pada' => $now,
                    ];

                    $pengeluaranDetailRows[] = [
                        'barang_id' => $bid,
                        'qty' => $take,
                    ];
                }

                if (!empty($permintaanDetailRows)) {
                    DB::table('permintaan_detail')->insert($permintaanDetailRows);
                }

                $pengeluaranId = DB::table('pengeluaran')->insertGetId([
                    'instansi_id' => $instansiId,
                    'gudang_id' => $gudangId,
                    'unit_organisasi_id' => $cabangId,
                    'permintaan_id' => $permintaanId,
                    'nomor_pengeluaran' => $makeNomor('OUT', $t),
                    'tanggal_pengeluaran' => $now,
                    'diserahkan_ke_pengguna_id' => $pengguna['pemohon'],
                    'diserahkan_ke_unit_id' => $cabangId,
                    'status' => 'dikeluarkan',
                    'catatan' => null,
                    'dibuat_oleh' => $pengguna['petugas_gudang'],
                    'diposting_oleh' => $pengguna['petugas_gudang'],
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);

                $pergerakanOutId = DB::table('pergerakan_stok')->insertGetId([
                    'instansi_id' => $instansiId,
                    'nomor_pergerakan' => $makeNomor('MOVE-OUT', $t),
                    'jenis_pergerakan' => 'pengeluaran',
                    'tipe_referensi' => 'pengeluaran',
                    'id_referensi' => $pengeluaranId,
                    'tanggal_pergerakan' => $now,
                    'gudang_id' => $gudangId,
                    'catatan' => null,
                    'diposting_oleh' => $pengguna['petugas_gudang'],
                    'status' => 'diposting',
                    'dibuat_oleh' => $pengguna['petugas_gudang'],
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);

                $detailOutRows = [];
                foreach ($pengeluaranDetailRows as $od) {

                    DB::table('pengeluaran_detail')->insert([
                        'pengeluaran_id' => $pengeluaranId,
                        'barang_id' => $od['barang_id'],
                        'lokasi_id' => $binId,
                        'no_lot' => null,
                        'tanggal_kedaluwarsa' => null,
                        'qty' => $od['qty'],
                        'biaya_satuan' => 0,
                        'dibuat_pada' => $now,
                        'diubah_pada' => $now,
                    ]);

                    $detailOutRows[] = [
                        'pergerakan_stok_id' => $pergerakanOutId,
                        'barang_id' => $od['barang_id'],
                        'dari_gudang_id' => $gudangId,
                        'dari_lokasi_id' => $binId,
                        'ke_gudang_id' => null,
                        'ke_lokasi_id' => null,
                        'no_lot' => null,
                        'tanggal_kedaluwarsa' => null,
                        'qty' => $od['qty'],
                        'biaya_satuan' => 0,
                        'dibuat_pada' => $now,
                        'diubah_pada' => $now,
                    ];
                }

                if (!empty($detailOutRows)) {
                    DB::table('detail_pergerakan_stok')->insert($detailOutRows);
                }
            }


            $alurPermintaanId = $this->buatAlur($now, $instansiId, 'APR-REQ', 'Alur Persetujuan Permintaan', 'permintaan', $pengguna['superadmin'], [
                ['no' => 1, 'nama' => 'Persetujuan Kepala Unit', 'tipe' => 'peran', 'peran_id' => $peran['kepala_unit'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
                ['no' => 2, 'nama' => 'Persetujuan Kepala Gudang', 'tipe' => 'peran', 'peran_id' => $peran['kepala_gudang'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
                ['no' => 3, 'nama' => 'Persetujuan Keuangan', 'tipe' => 'peran', 'peran_id' => $peran['keuangan'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
            ]);

            $alurPOId = $this->buatAlur($now, $instansiId, 'APR-PO', 'Alur Persetujuan PO', 'pesanan_pembelian', $pengguna['superadmin'], [
                ['no' => 1, 'nama' => 'Persetujuan Pejabat Pengadaan', 'tipe' => 'peran', 'peran_id' => $peran['pejabat_pengadaan'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
                ['no' => 2, 'nama' => 'Persetujuan Kepala Gudang', 'tipe' => 'peran', 'peran_id' => $peran['kepala_gudang'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
                ['no' => 3, 'nama' => 'Persetujuan Keuangan', 'tipe' => 'peran', 'peran_id' => $peran['keuangan'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
            ]);

            $alurHapusAsetId = $this->buatAlur($now, $instansiId, 'APR-DEL-AST', 'Alur Persetujuan Penghapusan Aset', 'penghapusan_aset', $pengguna['superadmin'], [
                ['no' => 1, 'nama' => 'Verifikasi Kepala Gudang', 'tipe' => 'peran', 'peran_id' => $peran['kepala_gudang'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
                ['no' => 2, 'nama' => 'Review Auditor Aset', 'tipe' => 'peran', 'peran_id' => $peran['auditor_aset'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
                ['no' => 3, 'nama' => 'Final Keuangan', 'tipe' => 'peran', 'peran_id' => $peran['keuangan'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
            ]);

            $alurOpnameId = $this->buatAlur($now, $instansiId, 'APR-OPN', 'Alur Persetujuan Posting Opname', 'stok_opname', $pengguna['superadmin'], [
                ['no' => 1, 'nama' => 'Validasi Petugas Gudang', 'tipe' => 'peran', 'peran_id' => $peran['petugas_gudang'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
                ['no' => 2, 'nama' => 'Persetujuan Kepala Gudang', 'tipe' => 'peran', 'peran_id' => $peran['kepala_gudang'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
                ['no' => 3, 'nama' => 'Audit Final Auditor Aset', 'tipe' => 'peran', 'peran_id' => $peran['auditor_aset'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
            ]);

            $alurPKId = $this->buatAlur($now, $instansiId, 'APR-PK', 'Alur Persetujuan Perintah Kerja', 'perintah_kerja', $pengguna['superadmin'], [
                ['no' => 1, 'nama' => 'Persetujuan Kepala Unit', 'tipe' => 'peran', 'peran_id' => $peran['kepala_unit'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
                ['no' => 2, 'nama' => 'Persetujuan Kepala Gudang', 'tipe' => 'peran', 'peran_id' => $peran['kepala_gudang'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
                ['no' => 3, 'nama' => 'Persetujuan Keuangan', 'tipe' => 'peran', 'peran_id' => $peran['keuangan'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
            ]);

            $alurPeminjamanId = $this->buatAlur($now, $instansiId, 'APR-LOAN', 'Alur Persetujuan Peminjaman Aset', 'peminjaman_aset', $pengguna['superadmin'], [
                ['no' => 1, 'nama' => 'Persetujuan Kepala Unit', 'tipe' => 'peran', 'peran_id' => $peran['kepala_unit'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
                ['no' => 2, 'nama' => 'Persetujuan Kepala Gudang', 'tipe' => 'peran', 'peran_id' => $peran['kepala_gudang'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
            ]);

            $alurPenugasanId = $this->buatAlur($now, $instansiId, 'APR-ASSIGN', 'Alur Persetujuan Penugasan Aset', 'penugasan_aset', $pengguna['superadmin'], [
                ['no' => 1, 'nama' => 'Persetujuan Kepala Unit', 'tipe' => 'peran', 'peran_id' => $peran['kepala_unit'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
                ['no' => 2, 'nama' => 'Persetujuan Kepala Gudang', 'tipe' => 'peran', 'peran_id' => $peran['kepala_gudang'], 'pengguna_id' => null, 'unit_id' => null, 'harus_semua' => false],
            ]);

            $asetIds = [];
            $idxAset = 1;
            foreach ($barangIdsAset as $bid) {
                $qtyAset = (int)DB::table('saldo_stok')->where('gudang_id', $gudangId)->where('lokasi_id', $binId)->where('barang_id', $bid)->sum('qty_tersedia');
                $qtyAset = max(1, $qtyAset);
                for ($i = 0; $i < $qtyAset; $i++) {
                    $asetIds[] = DB::table('aset')->insertGetId([
                        'instansi_id' => $instansiId,
                        'barang_id' => $bid,
                        'tag_aset' => 'AST-' . str_pad((string)$idxAset, 5, '0', STR_PAD_LEFT),
                        'no_serial' => 'SN-' . str_pad((string)$idxAset, 6, '0', STR_PAD_LEFT),
                        'imei' => null,
                        'no_mesin' => null,
                        'no_rangka' => null,
                        'no_polisi' => null,
                        'tanggal_beli' => $now->toDateString(),
                        'penerimaan_id' => $penerimaanId,
                        'unit_organisasi_saat_ini_id' => $cabangId,
                        'gudang_saat_ini_id' => $gudangId,
                        'lokasi_saat_ini_id' => $binId,
                        'pemegang_pengguna_id' => null,
                        'status_kondisi' => 'baik',
                        'status_siklus' => 'disimpan',
                        'biaya_perolehan' => rand(1500000, 12000000),
                        'mata_uang' => 'IDR',
                        'extra' => null,
                        'dibuat_pada' => $now,
                        'diubah_pada' => $now,
                    ]);
                    $idxAset++;
                }
            }

            $asetPenugasan = array_slice($asetIds, 0, 5);
            $asetPeminjaman = array_slice($asetIds, 5, 3);
            $asetPenghapusan = array_slice($asetIds, 8, 2);

            foreach ($asetPenugasan as $aid) {
                $penugasanId = DB::table('penugasan_aset')->insertGetId([
                    'instansi_id' => $instansiId,
                    'aset_id' => $aid,
                    'ditugaskan_ke_pengguna_id' => $pengguna['pemohon'],
                    'ditugaskan_ke_unit_id' => $cabangId,
                    'tanggal_tugas' => $now->toDateString(),
                    'tanggal_kembali' => null,
                    'status' => 'sedang ditugaskan',
                    'catatan' => 'Penugasan demo',
                    'dibuat_oleh' => $pengguna['kepala_gudang'],
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);

                DB::table('aset')->where('id', $aid)->update([
                    'pemegang_pengguna_id' => $pengguna['pemohon'],
                    'status_siklus' => 'ditugaskan',
                    'diubah_pada' => $now,
                ]);
            }

            foreach ($asetPeminjaman as $aid) {
                $pinjamId = DB::table('peminjaman_aset')->insertGetId([
                    'instansi_id' => $instansiId,
                    'aset_id' => $aid,
                    'peminjam_pengguna_id' => $pengguna['pemohon'],
                    'peminjam_unit_id' => $cabangId,
                    'tanggal_mulai' => $now->toDateString(),
                    'jatuh_tempo' => $now->copy()->addDays(14)->toDateString(),
                    'tanggal_kembali' => null,
                    'status' => 'aktif',
                    'catatan' => 'Peminjaman demo',
                    'dibuat_oleh' => $pengguna['kepala_unit'],
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);

                DB::table('aset')->where('id', $aid)->update([
                    'pemegang_pengguna_id' => $pengguna['pemohon'],
                    'status_siklus' => 'dipinjam',
                    'diubah_pada' => $now,
                ]);

                $this->buatPermintaanPersetujuan($now, $instansiId, $alurPermintaanId, 'peminjaman_aset', $pinjamId, 'APR-PJM-2026-' . str_pad((string)$pinjamId, 4, '0', STR_PAD_LEFT), $pengguna['kepala_unit'], 'disetujui', 2, 'Peminjaman disetujui', [
                    ['no' => 1, 'nama' => 'Persetujuan Kepala Unit', 'status' => 'disetujui', 'oleh' => $pengguna['kepala_unit'], 'catatan' => 'OK'],
                    ['no' => 2, 'nama' => 'Persetujuan Kepala Gudang', 'status' => 'disetujui', 'oleh' => $pengguna['kepala_gudang'], 'catatan' => 'OK'],
                ]);
            }

            foreach ($asetPenghapusan as $aid) {
                $hapusId = DB::table('penghapusan_aset')->insertGetId([
                    'instansi_id' => $instansiId,
                    'aset_id' => $aid,
                    'nomor_penghapusan' => 'DEL-2026-' . str_pad((string)$aid, 4, '0', STR_PAD_LEFT),
                    'tanggal_penghapusan' => $now->toDateString(),
                    'metode' => 'hapus',
                    'alasan' => 'Rusak berat',
                    'disetujui_oleh' => $pengguna['keuangan'],
                    'status' => 'disetujui',
                    'dibuat_oleh' => $pengguna['kepala_gudang'],
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);

                DB::table('aset')->where('id', $aid)->update([
                    'status_siklus' => 'dihapus',
                    'pemegang_pengguna_id' => null,
                    'diubah_pada' => $now,
                ]);

                $this->buatPermintaanPersetujuan($now, $instansiId, $alurHapusAsetId, 'penghapusan_aset', $hapusId, 'APR-DEL-2026-' . str_pad((string)$hapusId, 4, '0', STR_PAD_LEFT), $pengguna['kepala_gudang'], 'disetujui', 3, 'Penghapusan aset disetujui', [
                    ['no' => 1, 'nama' => 'Verifikasi Kepala Gudang', 'status' => 'disetujui', 'oleh' => $pengguna['kepala_gudang'], 'catatan' => 'Layak dihapus'],
                    ['no' => 2, 'nama' => 'Review Auditor Aset', 'status' => 'disetujui', 'oleh' => $pengguna['auditor_aset'], 'catatan' => 'OK'],
                    ['no' => 3, 'nama' => 'Final Keuangan', 'status' => 'disetujui', 'oleh' => $pengguna['keuangan'], 'catatan' => 'OK'],
                ]);
            }

            // $permintaanId = DB::table('permintaan')->insertGetId([
            //     'instansi_id' => $instansiId,
            //     'unit_organisasi_id' => $cabangId,
            //     'nomor_permintaan' => 'REQ-2026-0001',
            //     'tanggal_permintaan' => $now,
            //     'pemohon_id' => $pengguna['pemohon'],
            //     'tipe_permintaan' => 'habis_pakai',
            //     'prioritas' => 'normal',
            //     'status' => 'disetujui',
            //     'tujuan' => 'Kebutuhan operasional unit',
            //     'dibutuhkan_pada' => $now->copy()->addDays(2)->toDateString(),
            //     'catatan_persetujuan' => null,
            //     'dibuat_oleh' => $pengguna['pemohon'],
            //     'dibuat_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);

            // DB::table('permintaan_detail')->insert([
            //     'permintaan_id' => $permintaanId,
            //     'barang_id' => $barangKertasId,
            //     'qty_diminta' => 2,
            //     'qty_disetujui' => 2,
            //     'qty_dipenuhi' => 0,
            //     'catatan' => null,
            //     'dibuat_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);

            // $pengeluaranId = DB::table('pengeluaran')->insertGetId([
            //     'instansi_id' => $instansiId,
            //     'gudang_id' => $gudangId,
            //     'unit_organisasi_id' => $cabangId,
            //     'permintaan_id' => $permintaanId,
            //     'nomor_pengeluaran' => 'OUT-2026-0001',
            //     'tanggal_pengeluaran' => $now,
            //     'diserahkan_ke_pengguna_id' => $pengguna['pemohon'],
            //     'diserahkan_ke_unit_id' => $cabangId,
            //     'status' => 'dikeluarkan',
            //     'catatan' => null,
            //     'dibuat_oleh' => $pengguna['petugas_gudang'],
            //     'diposting_oleh' => $pengguna['petugas_gudang'],
            //     'dibuat_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);

            // DB::table('pengeluaran_detail')->insert([
            //     'pengeluaran_id' => $pengeluaranId,
            //     'barang_id' => $barangKertasId,
            //     'lokasi_id' => $binId,
            //     'no_lot' => null,
            //     'tanggal_kedaluwarsa' => null,
            //     'qty' => 2,
            //     'biaya_satuan' => 60000,
            //     'dibuat_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);

            // $pergerakanKeluarId = DB::table('pergerakan_stok')->insertGetId([
            //     'instansi_id' => $instansiId,
            //     'nomor_pergerakan' => 'MOVE-OUT-2026-0001',
            //     'jenis_pergerakan' => 'pengeluaran',
            //     'tipe_referensi' => 'pengeluaran',
            //     'id_referensi' => $pengeluaranId,
            //     'tanggal_pergerakan' => $now,
            //     'gudang_id' => $gudangId,
            //     'catatan' => 'Pengeluaran dari permintaan',
            //     'diposting_oleh' => $pengguna['petugas_gudang'],
            //     'status' => 'diposting',
            //     'dibuat_oleh' => $pengguna['petugas_gudang'],
            //     'dibuat_pada' => $now,
            //     'diubah_pada' => $now,
            // ]);

            // DB::table('detail_pergerakan_stok')->insert([
            //     [
            //         'pergerakan_stok_id' => $pergerakanKeluarId,
            //         'barang_id' => $barangKertasId,
            //         'dari_gudang_id' => $gudangId,
            //         'dari_lokasi_id' => $binId,
            //         'ke_gudang_id' => null,
            //         'ke_lokasi_id' => null,
            //         'no_lot' => null,
            //         'tanggal_kedaluwarsa' => null,
            //         'qty' => 2,
            //         'biaya_satuan' => 60000,
            //         'dibuat_pada' => $now,
            //         'diubah_pada' => $now,
            //     ],
            // ]);

            DB::table('saldo_stok')->where('gudang_id', $gudangId)->where('lokasi_id', $binId)->where('barang_id', $barangKertasId)->update([
                'qty_tersedia' => DB::raw('qty_tersedia - 2'),
                'qty_bisa_dipakai' => DB::raw('qty_bisa_dipakai - 2'),
                'pergerakan_terakhir_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $ppPermintaanId = $this->buatPermintaanPersetujuan($now, $instansiId, $alurPermintaanId, 'permintaan', $permintaanId, 'APR-REQ-2026-0001', $pengguna['pemohon'], 'disetujui', 3, 'Permintaan disetujui', [
                ['no' => 1, 'nama' => 'Persetujuan Kepala Unit', 'status' => 'disetujui', 'oleh' => $pengguna['kepala_unit'], 'catatan' => 'Setuju'],
                ['no' => 2, 'nama' => 'Persetujuan Kepala Gudang', 'status' => 'disetujui', 'oleh' => $pengguna['kepala_gudang'], 'catatan' => 'Setuju'],
                ['no' => 3, 'nama' => 'Persetujuan Keuangan', 'status' => 'disetujui', 'oleh' => $pengguna['keuangan'], 'catatan' => 'OK'],
            ]);

            $ppPOId = $this->buatPermintaanPersetujuan($now, $instansiId, $alurPOId, 'pesanan_pembelian', $poId, 'APR-PO-2026-0001', $pengguna['petugas_gudang'], 'disetujui', 3, 'PO disetujui', [
                ['no' => 1, 'nama' => 'Persetujuan Pejabat Pengadaan', 'status' => 'disetujui', 'oleh' => $pengguna['pejabat_pengadaan'], 'catatan' => 'Disetujui'],
                ['no' => 2, 'nama' => 'Persetujuan Kepala Gudang', 'status' => 'disetujui', 'oleh' => $pengguna['kepala_gudang'], 'catatan' => 'OK'],
                ['no' => 3, 'nama' => 'Persetujuan Keuangan', 'status' => 'disetujui', 'oleh' => $pengguna['keuangan'], 'catatan' => 'OK'],
            ]);

            $opnameId = DB::table('stok_opname')->insertGetId([
                'instansi_id' => $instansiId,
                'gudang_id' => $gudangId,
                'nomor_opname' => 'OPN-2026-0001',
                'tanggal_opname' => $now->toDateString(),
                'tipe' => 'siklus',
                'status' => 'diposting',
                'catatan' => null,
                'dibuat_oleh' => $pengguna['petugas_gudang'],
                'diposting_oleh' => $pengguna['kepala_gudang'],
                'diposting_pada' => $now,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $qtyKertas = (int)DB::table('saldo_stok')->where('gudang_id', $gudangId)->where('lokasi_id', $binId)->where('barang_id', $barangKertasId)->value('qty_tersedia');

            DB::table('stok_opname_detail')->insert([
                'stok_opname_id' => $opnameId,
                'lokasi_id' => $binId,
                'barang_id' => $barangKertasId,
                'no_lot' => null,
                'tanggal_kedaluwarsa' => null,
                'qty_sistem' => $qtyKertas,
                'qty_hitung' => $qtyKertas,
                'qty_selisih' => 0,
                'alasan_selisih' => null,
                'dihitung_oleh' => $pengguna['petugas_gudang'],
                'dihitung_pada' => $now,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $ppOpnameId = $this->buatPermintaanPersetujuan($now, $instansiId, $alurOpnameId, 'stok_opname', $opnameId, 'APR-OPN-2026-0001', $pengguna['petugas_gudang'], 'disetujui', 3, 'Opname diposting', [
                ['no' => 1, 'nama' => 'Validasi Petugas Gudang', 'status' => 'disetujui', 'oleh' => $pengguna['petugas_gudang'], 'catatan' => 'Valid'],
                ['no' => 2, 'nama' => 'Persetujuan Kepala Gudang', 'status' => 'disetujui', 'oleh' => $pengguna['kepala_gudang'], 'catatan' => 'OK'],
                ['no' => 3, 'nama' => 'Audit Final Auditor Aset', 'status' => 'disetujui', 'oleh' => $pengguna['auditor_aset'], 'catatan' => 'OK'],
            ]);

            $rencanaPerawatanId = DB::table('rencana_perawatan')->insertGetId([
                'instansi_id' => $instansiId,
                'kode' => 'MTN-001',
                'nama' => 'Perawatan Laptop Berkala',
                'tipe' => 'berdasarkan_waktu',
                'interval_hari' => 90,
                'interval_pemakaian' => null,
                'checklist' => json_encode(['cek_baterai', 'cek_keyboard', 'cek_storage']),
                'status' => 'aktif',
                'dibuat_oleh' => $pengguna['teknisi'],
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $asetRencana = $asetIds[0] ?? null;
            if ($asetRencana) {
                DB::table('rencana_perawatan_aset')->insert([
                    'rencana_perawatan_id' => $rencanaPerawatanId,
                    'aset_id' => $asetRencana,
                    'terakhir_selesai_pada' => null,
                    'jatuh_tempo_pada' => $now->copy()->addDays(30),
                    'nilai_pemakaian_terakhir' => null,
                    'nilai_pemakaian_jatuh_tempo' => null,
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ]);
            }

            $pkId = DB::table('perintah_kerja')->insertGetId([
                'instansi_id' => $instansiId,
                'nomor_pk' => 'PK-2026-0001',
                'tanggal_pk' => $now,
                'aset_id' => $asetRencana,
                'rencana_perawatan_id' => $rencanaPerawatanId,
                'tipe' => 'preventif',
                'prioritas' => 'normal',
                'status' => 'dibuka',
                'masalah_dilaporkan' => 'Perawatan berkala',
                'penyelesaian' => null,
                'nama_vendor' => null,
                'biaya' => 250000,
                'mata_uang' => 'IDR',
                'dibuka_oleh' => $pengguna['teknisi'],
                'ditutup_oleh' => null,
                'dibuka_pada' => $now,
                'ditutup_pada' => null,
                'dibuat_oleh' => $pengguna['teknisi'],
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $ppPKId = $this->buatPermintaanPersetujuan($now, $instansiId, $alurPKId, 'perintah_kerja', $pkId, 'APR-PK-2026-0001', $pengguna['teknisi'], 'disetujui', 3, 'Perintah kerja disetujui', [
                ['no' => 1, 'nama' => 'Persetujuan Kepala Unit', 'status' => 'disetujui', 'oleh' => $pengguna['kepala_unit'], 'catatan' => 'OK'],
                ['no' => 2, 'nama' => 'Persetujuan Kepala Gudang', 'status' => 'disetujui', 'oleh' => $pengguna['kepala_gudang'], 'catatan' => 'OK'],
                ['no' => 3, 'nama' => 'Persetujuan Keuangan', 'status' => 'disetujui', 'oleh' => $pengguna['keuangan'], 'catatan' => 'OK'],
            ]);
            // $pppenugasanId = $this->buatPermintaanPersetujuan($now, $instansiId, $alurPenugasanId, 'penugasan_aset', $penugasanId, 'APR-ASSIGN-2026-0001', $pengguna['pemohon'], 'disetujui', 2, 'Penugasan disetujui', [
            //     ['no' => 1, 'nama' => 'Persetujuan Kepala Unit', 'status' => 'disetujui', 'oleh' => $pengguna['kepala_unit'], 'catatan' => 'OK'],
            //     ['no' => 2, 'nama' => 'Persetujuan Kepala Gudang', 'status' => 'disetujui', 'oleh' => $pengguna['kepala_gudang'], 'catatan' => 'OK'],
            // ]);
            // $pppeminjamanId = $this->buatPermintaanPersetujuan($now, $instansiId, $alurPenugasanId, 'penugasan_aset', $pinjamId, 'APR-ASSIGN-2026-' . str_pad((string)$i, 4, '0', STR_PAD_LEFT), $pengguna['pemohon'], 'disetujui', 2, 'Penugasan disetujui ' . $i, [
            //     ['no' => 1, 'nama' => 'Persetujuan Kepala Unit', 'status' => 'disetujui', 'oleh' => $pengguna['kepala_unit'], 'catatan' => 'OK'],
            //     ['no' => 2, 'nama' => 'Persetujuan Kepala Gudang', 'status' => 'disetujui', 'oleh' => $pengguna['kepala_gudang'], 'catatan' => 'OK'],
            // ]);

            $this->notif($now, $instansiId, $pengguna['pemohon'], 'Permintaan disetujui', 'Permintaan REQ-2026-0001 selesai disetujui (3 langkah).', 'permintaan', $permintaanId);
            $this->notif($now, $instansiId, $pengguna['petugas_gudang'], 'PO disetujui', 'PO PO-2026-0001 selesai disetujui (3 langkah).', 'pesanan_pembelian', $poId);
            $this->notif($now, $instansiId, $pengguna['petugas_gudang'], 'Stok awal', 'Stok awal berhasil diposting.', 'pergerakan_stok', $pergerakanStokAwalId);
            $this->notif($now, $instansiId, $pengguna['kepala_gudang'], 'Opname diposting', 'OPN-2026-0001 selesai disetujui (3 langkah).', 'stok_opname', $opnameId);
            $this->notif($now, $instansiId, $pengguna['teknisi'], 'Perintah kerja disetujui', 'PK-2026-0001 selesai disetujui (3 langkah).', 'perintah_kerja', $pkId);

            $this->audit($now, $instansiId, $pengguna['keuangan'], 'setujui', 'permintaan', $permintaanId, 'permintaan_persetujuan', $ppPermintaanId, ['status' => 'diajukan'], ['status' => 'disetujui']);
            $this->audit($now, $instansiId, $pengguna['keuangan'], 'setujui', 'pesanan_pembelian', $poId, 'permintaan_persetujuan', $ppPOId, ['status' => 'diajukan'], ['status' => 'disetujui']);
            $this->audit($now, $instansiId, $pengguna['auditor_aset'], 'setujui', 'stok_opname', $opnameId, 'permintaan_persetujuan', $ppOpnameId, ['status' => 'rekonsiliasi'], ['status' => 'diposting']);
            $this->audit($now, $instansiId, $pengguna['keuangan'], 'setujui', 'perintah_kerja', $pkId, 'permintaan_persetujuan', $ppPKId, ['status' => 'draft'], ['status' => 'dibuka']);
        });
    }

    private function buatAlur(Carbon $now, int $instansiId, string $kode, string $nama, string $berlakuUntuk, int $dibuatOleh, array $steps): int
    {
        $alurId = DB::table('alur_persetujuan')->insertGetId([
            'instansi_id' => $instansiId,
            'kode' => $kode,
            'nama' => $nama,
            'berlaku_untuk' => $berlakuUntuk,
            'status' => 'aktif',
            'aturan' => null,
            'dibuat_oleh' => $dibuatOleh,
            'dibuat_pada' => $now,
            'diubah_pada' => $now,
        ]);

        foreach ($steps as $s) {
            DB::table('langkah_alur_persetujuan')->insert([
                'alur_persetujuan_id' => $alurId,
                'no_langkah' => $s['no'],
                'nama_langkah' => $s['nama'],
                'tipe_penyetuju' => $s['tipe'],
                'peran_id' => $s['peran_id'],
                'pengguna_id' => $s['pengguna_id'],
                'unit_organisasi_id' => $s['unit_id'],
                'harus_semua' => $s['harus_semua'],
                'kondisi' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);
        }

        return $alurId;
    }

    private function buatPermintaanPersetujuan(Carbon $now, int $instansiId, int $alurId, string $tipeEntitas, int $idEntitas, string $nomor, int $dimintaOleh, string $status, int $langkahSaatIni, ?string $ringkasan, array $steps): int
    {
        $ppId = DB::table('permintaan_persetujuan')->insertGetId([
            'instansi_id' => $instansiId,
            'alur_persetujuan_id' => $alurId,
            'tipe_entitas' => $tipeEntitas,
            'id_entitas' => $idEntitas,
            'nomor_persetujuan' => $nomor,
            'diminta_oleh' => $dimintaOleh,
            'diminta_pada' => $now,
            'status' => $status,
            'langkah_saat_ini' => $langkahSaatIni,
            'ringkasan' => $ringkasan,
            'dibuat_pada' => $now,
            'diubah_pada' => $now,
        ]);

        foreach ($steps as $s) {
            DB::table('langkah_permintaan_persetujuan')->insert([
                'permintaan_persetujuan_id' => $ppId,
                'no_langkah' => $s['no'],
                'nama_langkah' => $s['nama'],
                'status' => $s['status'],
                'diputuskan_oleh' => $s['oleh'],
                'diputuskan_pada' => $now,
                'catatan_keputusan' => $s['catatan'],
                'snapshot' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);
        }

        return $ppId;
    }

    private function notif(Carbon $now, int $instansiId, int $penggunaId, string $judul, string $isi, ?string $tipe, ?int $id): void
    {
        DB::table('notifikasi')->insert([
            'instansi_id' => $instansiId,
            'pengguna_id' => $penggunaId,
            'kanal' => 'aplikasi',
            'judul' => $judul,
            'isi' => $isi,
            'tipe_entitas' => $tipe,
            'id_entitas' => $id,
            'sudah_dibaca' => false,
            'dibaca_pada' => null,
            'dibuat_pada' => $now,
        ]);
    }

    private function audit(Carbon $now, int $instansiId, int $penggunaId, string $aksi, ?string $namaTabel, ?int $idRekaman, ?string $tipeRef, ?int $idRef, $lama, $baru): void
    {
        DB::table('log_audit')->insert([
            'instansi_id' => $instansiId,
            'pengguna_id' => $penggunaId,
            'aksi' => $aksi,
            'nama_tabel' => $namaTabel,
            'id_rekaman' => $idRekaman,
            'tipe_referensi' => $tipeRef,
            'id_referensi' => $idRef,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'seed',
            'data_lama' => $lama ? json_encode($lama) : null,
            'data_baru' => $baru ? json_encode($baru) : null,
            'dibuat_pada' => $now,
        ]);
    }
}
