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
                'kode' => 'POLRI',
                'nama' => 'Kepolisian Republik Indonesia',
                'alamat' => 'Jakarta',
                'telepon' => '021-000000',
                'email' => 'polri@example.go.id',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $mabesId = DB::table('unit_organisasi')->insertGetId([
                'instansi_id' => $instansiId,
                'induk_id' => null,
                'tipe_unit' => 'mabes',
                'kode' => 'MABES-01',
                'nama' => 'Mabes POLRI',
                'alamat' => 'Jakarta',
                'telepon' => '021-111111',
                'email' => 'mabes@example.go.id',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $cabangId = DB::table('unit_organisasi')->insertGetId([
                'instansi_id' => $instansiId,
                'induk_id' => $mabesId,
                'tipe_unit' => 'polres',
                'kode' => 'POLRES-01',
                'nama' => 'POLRES Demo 01',
                'alamat' => 'Jawa Barat',
                'telepon' => '022-222222',
                'email' => 'polres01@example.go.id',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $izinRows = [
                ['kode' => 'master.lihat', 'nama' => 'Lihat master data'],
                ['kode' => 'master.kelola', 'nama' => 'Kelola master data'],
                ['kode' => 'rbac.lihat', 'nama' => 'Lihat pengguna & peran'],
                ['kode' => 'rbac.kelola', 'nama' => 'Kelola pengguna & peran'],
                ['kode' => 'po.buat', 'nama' => 'Buat pesanan pembelian'],
                ['kode' => 'po.ajukan', 'nama' => 'Ajukan pesanan pembelian'],
                ['kode' => 'po.setujui', 'nama' => 'Setujui pesanan pembelian'],
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

                ['kode' => 'permintaan.lihat', 'nama' => 'Lihat permintaan'],
                ['kode' => 'permintaan.kelola', 'nama' => 'Kelola permintaan'],

                ['kode' => 'pengeluaran.lihat', 'nama' => 'Lihat pengeluaran'],
                ['kode' => 'pengeluaran.kelola', 'nama' => 'Kelola pengeluaran'],

                ['kode' => 'pergerakan_stok.lihat', 'nama' => 'Melihat Pergerakan Stok'],
                ['kode' => 'saldo_stok.kelola', 'nama' => 'Izin Melihat Stok'],

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
                'kepala_unit' => ['master.lihat', 'stok.lihat', 'permintaan.setujui', 'approval.proses', 'notif.lihat', 'aset.lihat'],
                'kepala_gudang' => ['master.lihat', 'stok.lihat', 'po.setujui', 'permintaan.setujui', 'stok.posting', 'qc.proses', 'opname.kelola', 'audit.lihat', 'notif.lihat', 'aset.lihat', 'aset.kelola', 'aset.hapus', 'perawatan.kelola', 'approval.proses'],
                'petugas_gudang' => ['master.lihat', 'stok.lihat', 'po.buat', 'po.ajukan', 'penerimaan.buat', 'pengeluaran.buat', 'qc.proses', 'stok.posting', 'opname.kelola', 'notif.lihat', 'aset.lihat', 'aset.kelola', 'approval.proses'],
                'pemohon' => ['permintaan.buat', 'permintaan.ajukan', 'stok.lihat', 'aset.lihat', 'notif.lihat'],
                'pejabat_pengadaan' => ['po.setujui', 'approval.proses', 'audit.lihat', 'notif.lihat'],
                'keuangan' => ['po.setujui', 'aset.hapus', 'approval.proses', 'audit.lihat', 'notif.lihat'],
                'auditor_aset' => ['audit.lihat', 'aset.lihat', 'aset.hapus', 'approval.proses', 'notif.lihat'],
                'teknisi' => ['perawatan.kelola', 'aset.lihat', 'notif.lihat'],
            ];

            foreach ($grant as $kodePeran => $izinKodeList) {
                $pid = $peran[$kodePeran];
                foreach ($izinKodeList as $ik) {
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

            $poId = DB::table('pesanan_pembelian')->insertGetId([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $cabangId,
                'pemasok_id' => $pemasokId,
                'kontrak_id' => $kontrakId,
                'nomor_po' => 'PO-2026-0001',
                'tanggal_po' => $now->toDateString(),
                'tanggal_estimasi' => $now->copy()->addDays(7)->toDateString(),
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

            $poLaptopDetailId = DB::table('pesanan_pembelian_detail')->insertGetId([
                'pesanan_pembelian_id' => $poId,
                'barang_id' => $barangLaptopId,
                'deskripsi' => 'Laptop untuk operasional',
                'qty' => 2,
                'harga_satuan' => 9000000,
                'tarif_pajak' => 0,
                'nilai_pajak' => 0,
                'total_baris' => 18000000,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $poKertasDetailId = DB::table('pesanan_pembelian_detail')->insertGetId([
                'pesanan_pembelian_id' => $poId,
                'barang_id' => $barangKertasId,
                'deskripsi' => 'Kertas A4',
                'qty' => 20,
                'harga_satuan' => 60000,
                'tarif_pajak' => 0,
                'nilai_pajak' => 0,
                'total_baris' => 1200000,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $subtotal = 18000000 + 1200000;
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
                'nomor_penerimaan' => 'GR-2026-0001',
                'tanggal_penerimaan' => $now->toDateString(),
                'diterima_oleh' => $pengguna['petugas_gudang'],
                'status' => 'diposting',
                'catatan' => null,
                'dibuat_oleh' => $pengguna['petugas_gudang'],
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $penerimaanLaptopId = DB::table('penerimaan_detail')->insertGetId([
                'penerimaan_id' => $penerimaanId,
                'barang_id' => $barangLaptopId,
                'po_detail_id' => $poLaptopDetailId,
                'qty_diterima' => 2,
                'no_lot' => null,
                'tanggal_kedaluwarsa' => null,
                'biaya_satuan' => 9000000,
                'lokasi_id' => $binId,
                'catatan' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $penerimaanKertasId = DB::table('penerimaan_detail')->insertGetId([
                'penerimaan_id' => $penerimaanId,
                'barang_id' => $barangKertasId,
                'po_detail_id' => $poKertasDetailId,
                'qty_diterima' => 20,
                'no_lot' => null,
                'tanggal_kedaluwarsa' => null,
                'biaya_satuan' => 60000,
                'lokasi_id' => $binId,
                'catatan' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $qcId = DB::table('inspeksi_qc')->insertGetId([
                'penerimaan_id' => $penerimaanId,
                'nomor_qc' => 'QC-2026-0001',
                'tanggal_qc' => $now->toDateString(),
                'pemeriksa_id' => $pengguna['kepala_gudang'],
                'status' => 'lulus',
                'ringkasan' => 'QC lulus',
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            DB::table('inspeksi_qc_detail')->insert([
                [
                    'inspeksi_qc_id' => $qcId,
                    'penerimaan_detail_id' => $penerimaanLaptopId,
                    'hasil' => 'lulus',
                    'catatan_cacat' => null,
                    'qty_diterima' => 2,
                    'qty_ditolak' => 0,
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ],
                [
                    'inspeksi_qc_id' => $qcId,
                    'penerimaan_detail_id' => $penerimaanKertasId,
                    'hasil' => 'lulus',
                    'catatan_cacat' => null,
                    'qty_diterima' => 20,
                    'qty_ditolak' => 0,
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ],
            ]);

            $saldoKertasId = DB::table('saldo_stok')->insertGetId([
                'instansi_id' => $instansiId,
                'gudang_id' => $gudangId,
                'lokasi_id' => $binId,
                'barang_id' => $barangKertasId,
                'no_lot' => null,
                'tanggal_kedaluwarsa' => null,
                'qty_tersedia' => 20,
                'qty_dipesan' => 0,
                'qty_bisa_dipakai' => 20,
                'pergerakan_terakhir_pada' => $now,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $pergerakanTerimaId = DB::table('pergerakan_stok')->insertGetId([
                'instansi_id' => $instansiId,
                'nomor_pergerakan' => 'MOVE-2026-0001',
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

            DB::table('detail_pergerakan_stok')->insert([
                [
                    'pergerakan_stok_id' => $pergerakanTerimaId,
                    'barang_id' => $barangKertasId,
                    'dari_gudang_id' => null,
                    'dari_lokasi_id' => null,
                    'ke_gudang_id' => $gudangId,
                    'ke_lokasi_id' => $binId,
                    'no_lot' => null,
                    'tanggal_kedaluwarsa' => null,
                    'qty' => 20,
                    'biaya_satuan' => 60000,
                    'dibuat_pada' => $now,
                    'diubah_pada' => $now,
                ],
            ]);

            $aset1Id = DB::table('aset')->insertGetId([
                'instansi_id' => $instansiId,
                'barang_id' => $barangLaptopId,
                'tag_aset' => 'AST-LPT-0001',
                'no_serial' => 'SN-LPT-0001',
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
                'biaya_perolehan' => 9000000,
                'mata_uang' => 'IDR',
                'extra' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $permintaanId = DB::table('permintaan')->insertGetId([
                'instansi_id' => $instansiId,
                'unit_organisasi_id' => $cabangId,
                'nomor_permintaan' => 'REQ-2026-0001',
                'tanggal_permintaan' => $now,
                'pemohon_id' => $pengguna['pemohon'],
                'tipe_permintaan' => 'habis_pakai',
                'prioritas' => 'normal',
                'status' => 'disetujui',
                'tujuan' => 'Kebutuhan operasional unit',
                'dibutuhkan_pada' => $now->copy()->addDays(2)->toDateString(),
                'catatan_persetujuan' => null,
                'dibuat_oleh' => $pengguna['pemohon'],
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            DB::table('permintaan_detail')->insert([
                'permintaan_id' => $permintaanId,
                'barang_id' => $barangKertasId,
                'qty_diminta' => 2,
                'qty_disetujui' => 2,
                'qty_dipenuhi' => 0,
                'catatan' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $pengeluaranId = DB::table('pengeluaran')->insertGetId([
                'instansi_id' => $instansiId,
                'gudang_id' => $gudangId,
                'unit_organisasi_id' => $cabangId,
                'permintaan_id' => $permintaanId,
                'nomor_pengeluaran' => 'OUT-2026-0001',
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

            DB::table('pengeluaran_detail')->insert([
                'pengeluaran_id' => $pengeluaranId,
                'barang_id' => $barangKertasId,
                'lokasi_id' => $binId,
                'no_lot' => null,
                'tanggal_kedaluwarsa' => null,
                'qty' => 2,
                'biaya_satuan' => 60000,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            DB::table('saldo_stok')->where('id', $saldoKertasId)->update([
                'qty_tersedia' => 18,
                'qty_bisa_dipakai' => 18,
                'pergerakan_terakhir_pada' => $now,
                'diubah_pada' => $now,
            ]);

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

            DB::table('stok_opname_detail')->insert([
                'stok_opname_id' => $opnameId,
                'lokasi_id' => $binId,
                'barang_id' => $barangKertasId,
                'no_lot' => null,
                'tanggal_kedaluwarsa' => null,
                'qty_sistem' => 18,
                'qty_hitung' => 18,
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

            $penghapusanId = DB::table('penghapusan_aset')->insertGetId([
                'instansi_id' => $instansiId,
                'aset_id' => $aset1Id,
                'nomor_penghapusan' => 'DEL-2026-0001',
                'tanggal_penghapusan' => $now->toDateString(),
                'metode' => 'hapus',
                'alasan' => 'Rusak total',
                'disetujui_oleh' => $pengguna['keuangan'],
                'status' => 'disetujui',
                'dibuat_oleh' => $pengguna['kepala_gudang'],
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $ppHapusId = $this->buatPermintaanPersetujuan($now, $instansiId, $alurHapusAsetId, 'penghapusan_aset', $penghapusanId, 'APR-DEL-2026-0001', $pengguna['kepala_gudang'], 'disetujui', 3, 'Penghapusan aset disetujui', [
                ['no' => 1, 'nama' => 'Verifikasi Kepala Gudang', 'status' => 'disetujui', 'oleh' => $pengguna['kepala_gudang'], 'catatan' => 'Layak dihapus'],
                ['no' => 2, 'nama' => 'Review Auditor Aset', 'status' => 'disetujui', 'oleh' => $pengguna['auditor_aset'], 'catatan' => 'OK'],
                ['no' => 3, 'nama' => 'Final Keuangan', 'status' => 'disetujui', 'oleh' => $pengguna['keuangan'], 'catatan' => 'OK'],
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

            DB::table('rencana_perawatan_aset')->insert([
                'rencana_perawatan_id' => $rencanaPerawatanId,
                'aset_id' => $aset1Id,
                'terakhir_selesai_pada' => null,
                'jatuh_tempo_pada' => $now->copy()->addDays(30),
                'nilai_pemakaian_terakhir' => null,
                'nilai_pemakaian_jatuh_tempo' => null,
                'dibuat_pada' => $now,
                'diubah_pada' => $now,
            ]);

            $pkId = DB::table('perintah_kerja')->insertGetId([
                'instansi_id' => $instansiId,
                'nomor_pk' => 'PK-2026-0001',
                'tanggal_pk' => $now,
                'aset_id' => $aset1Id,
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

            $this->notif($now, $instansiId, $pengguna['pemohon'], 'Permintaan disetujui', 'Permintaan REQ-2026-0001 selesai disetujui (3 langkah).', 'permintaan', $permintaanId);
            $this->notif($now, $instansiId, $pengguna['petugas_gudang'], 'PO disetujui', 'PO PO-2026-0001 selesai disetujui (3 langkah).', 'pesanan_pembelian', $poId);
            $this->notif($now, $instansiId, $pengguna['kepala_gudang'], 'Penghapusan aset disetujui', 'DEL-2026-0001 selesai disetujui (3 langkah).', 'penghapusan_aset', $penghapusanId);
            $this->notif($now, $instansiId, $pengguna['petugas_gudang'], 'Opname diposting', 'OPN-2026-0001 selesai disetujui (3 langkah).', 'stok_opname', $opnameId);
            $this->notif($now, $instansiId, $pengguna['teknisi'], 'Perintah kerja disetujui', 'PK-2026-0001 selesai disetujui (3 langkah).', 'perintah_kerja', $pkId);

            $this->audit($now, $instansiId, $pengguna['keuangan'], 'setujui', 'permintaan', $permintaanId, 'permintaan_persetujuan', $ppPermintaanId, ['status' => 'diajukan'], ['status' => 'disetujui']);
            $this->audit($now, $instansiId, $pengguna['keuangan'], 'setujui', 'pesanan_pembelian', $poId, 'permintaan_persetujuan', $ppPOId, ['status' => 'diajukan'], ['status' => 'disetujui']);
            $this->audit($now, $instansiId, $pengguna['keuangan'], 'setujui', 'penghapusan_aset', $penghapusanId, 'permintaan_persetujuan', $ppHapusId, ['status' => 'draft'], ['status' => 'disetujui']);
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
