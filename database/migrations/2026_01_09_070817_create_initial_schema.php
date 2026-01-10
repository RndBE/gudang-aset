<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('instansi', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama', 255);
            $table->text('alamat')->nullable();
            $table->string('telepon', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->enum('status', ['aktif','nonaktif'])->default('aktif');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('unit_organisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('induk_id')->nullable()->constrained('unit_organisasi')->nullOnDelete();
            $table->enum('tipe_unit', ['mabes','polda','polres','polsek','satker','unit','unit_gudang','lainnya'])->default('satker');
            $table->string('kode', 80);
            $table->string('nama', 255);
            $table->text('alamat')->nullable();
            $table->string('telepon', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->enum('status', ['aktif','nonaktif'])->default('aktif');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','kode'], 'uq_unit_org_inst_kode');
        });

        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('unit_organisasi_id')->nullable()->constrained('unit_organisasi')->nullOnDelete();
            $table->string('username', 120);
            $table->string('email', 255)->nullable();
            $table->string('telepon', 50)->nullable();
            $table->string('hash_password', 255)->nullable();
            $table->string('nama_lengkap', 255);
            $table->string('nip_nrk', 120)->nullable();
            $table->string('pangkat', 120)->nullable();
            $table->string('jabatan', 160)->nullable();
            $table->enum('status', ['aktif','nonaktif','terkunci'])->default('aktif');
            $table->timestamp('login_terakhir_pada')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','username'], 'uq_pengguna_inst_username');
            $table->unique(['instansi_id','email'], 'uq_pengguna_inst_email');
        });

        Schema::create('peran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->string('kode', 80);
            $table->string('nama', 160);
            $table->text('deskripsi')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','kode'], 'uq_peran_inst_kode');
        });

        Schema::create('izin', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 120)->unique();
            $table->string('nama', 200);
            $table->text('deskripsi')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('pengguna_peran', function (Blueprint $table) {
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->foreignId('peran_id')->constrained('peran')->cascadeOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->primary(['pengguna_id','peran_id']);
        });

        Schema::create('peran_izin', function (Blueprint $table) {
            $table->foreignId('peran_id')->constrained('peran')->cascadeOnDelete();
            $table->foreignId('izin_id')->constrained('izin')->cascadeOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->primary(['peran_id','izin_id']);
        });

        Schema::create('urutan_nomor_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('unit_organisasi_id')->nullable()->constrained('unit_organisasi')->nullOnDelete();
            $table->string('tipe_dokumen', 80);
            $table->unsignedSmallInteger('tahun');
            $table->unsignedInteger('nomor_terakhir')->default(0);
            $table->string('awalan', 120)->nullable();
            $table->string('akhiran', 120)->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','unit_organisasi_id','tipe_dokumen','tahun'], 'uq_urutan_doc_scope');
        });

        Schema::create('gudang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('unit_organisasi_id')->nullable()->constrained('unit_organisasi')->nullOnDelete();
            $table->string('kode', 80);
            $table->string('nama', 200);
            $table->text('alamat')->nullable();
            $table->enum('status', ['aktif','nonaktif'])->default('aktif');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','kode'], 'uq_gudang_inst_kode');
        });

        Schema::create('lokasi_gudang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gudang_id')->constrained('gudang')->cascadeOnDelete();
            $table->foreignId('induk_id')->nullable()->constrained('lokasi_gudang')->nullOnDelete();
            $table->enum('tipe_lokasi', ['zona','lorong','rak','ambalan','bin','ruang','halaman','lainnya'])->default('bin');
            $table->string('kode', 120);
            $table->string('nama', 200)->nullable();
            $table->string('jalur', 600)->nullable();
            $table->boolean('bisa_picking')->default(true);
            $table->enum('status', ['aktif','nonaktif'])->default('aktif');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['gudang_id','kode'], 'uq_lokasi_gudang_kode');
        });

        Schema::create('satuan_barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique();
            $table->string('nama', 60);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('kategori_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('induk_id')->nullable()->constrained('kategori_barang')->nullOnDelete();
            $table->string('kode', 80);
            $table->string('nama', 200);
            $table->boolean('default_aset')->default(false);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','kode'], 'uq_kategori_inst_kode');
        });

        Schema::create('pemasok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->string('kode', 80);
            $table->string('nama', 255);
            $table->string('npwp', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->string('nama_kontak', 160)->nullable();
            $table->string('telepon', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->enum('status', ['aktif','nonaktif'])->default('aktif');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','kode'], 'uq_pemasok_inst_kode');
        });

        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('kategori_id')->constrained('kategori_barang');
            $table->foreignId('satuan_id')->constrained('satuan_barang');
            $table->string('sku', 120);
            $table->string('nama', 255);
            $table->string('merek', 160)->nullable();
            $table->string('model', 160)->nullable();
            $table->json('spesifikasi')->nullable();
            $table->enum('tipe_barang', ['habis_pakai','aset','keduanya'])->default('habis_pakai');
            $table->enum('metode_pelacakan', ['tanpa','lot','kedaluwarsa','serial'])->default('tanpa');
            $table->decimal('stok_minimum', 18, 4)->default(0);
            $table->decimal('titik_pesan_ulang', 18, 4)->default(0);
            $table->enum('status', ['aktif','nonaktif'])->default('aktif');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','sku'], 'uq_barang_inst_sku');
            $table->index(['nama'], 'idx_barang_nama');
        });

        Schema::create('kontrak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('unit_organisasi_id')->nullable()->constrained('unit_organisasi')->nullOnDelete();
            $table->foreignId('pemasok_id')->constrained('pemasok');
            $table->string('nomor_kontrak', 120);
            $table->date('tanggal_kontrak');
            $table->date('mulai_tanggal')->nullable();
            $table->date('selesai_tanggal')->nullable();
            $table->decimal('nilai_total', 18, 2)->default(0);
            $table->string('mata_uang', 10)->default('IDR');
            $table->enum('status', ['draft','aktif','selesai','dibatalkan'])->default('draft');
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','nomor_kontrak'], 'uq_kontrak_no');
        });

        Schema::create('pesanan_pembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('unit_organisasi_id')->nullable()->constrained('unit_organisasi')->nullOnDelete();
            $table->foreignId('pemasok_id')->constrained('pemasok');
            $table->foreignId('kontrak_id')->nullable()->constrained('kontrak')->nullOnDelete();
            $table->string('nomor_po', 120);
            $table->date('tanggal_po');
            $table->date('tanggal_estimasi')->nullable();
            $table->string('mata_uang', 10)->default('IDR');
            $table->decimal('subtotal', 18, 2)->default(0);
            $table->decimal('pajak', 18, 2)->default(0);
            $table->decimal('total', 18, 2)->default(0);
            $table->enum('status', ['draft','diajukan','disetujui','diterima_sebagian','diterima','dibatalkan'])->default('draft');
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','nomor_po'], 'uq_po_no');
        });

        Schema::create('pesanan_pembelian_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_pembelian_id')->constrained('pesanan_pembelian')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang');
            $table->string('deskripsi', 500)->nullable();
            $table->decimal('qty', 18, 4)->default(0);
            $table->decimal('harga_satuan', 18, 4)->default(0);
            $table->decimal('tarif_pajak', 9, 4)->default(0);
            $table->decimal('nilai_pajak', 18, 4)->default(0);
            $table->decimal('total_baris', 18, 4)->default(0);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['pesanan_pembelian_id','barang_id'], 'uq_po_detail');
        });

        Schema::create('penerimaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('gudang_id')->constrained('gudang');
            $table->foreignId('pemasok_id')->nullable()->constrained('pemasok')->nullOnDelete();
            $table->foreignId('pesanan_pembelian_id')->nullable()->constrained('pesanan_pembelian')->nullOnDelete();
            $table->string('nomor_penerimaan', 120);
            $table->date('tanggal_penerimaan');
            $table->foreignId('diterima_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->enum('status', ['draft','diterima','qc_menunggu','qc_selesai','diposting','dibatalkan'])->default('draft');
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','nomor_penerimaan'], 'uq_penerimaan_no');
        });

        Schema::create('penerimaan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_id')->constrained('penerimaan')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang');
            $table->foreignId('po_detail_id')->nullable()->constrained('pesanan_pembelian_detail')->nullOnDelete();
            $table->decimal('qty_diterima', 18, 4)->default(0);
            $table->string('no_lot', 120)->nullable();
            $table->date('tanggal_kedaluwarsa')->nullable();
            $table->decimal('biaya_satuan', 18, 4)->default(0);
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi_gudang')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('inspeksi_qc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_id')->constrained('penerimaan')->cascadeOnDelete();
            $table->string('nomor_qc', 120)->unique();
            $table->date('tanggal_qc');
            $table->foreignId('pemeriksa_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->enum('status', ['menunggu','lulus','gagal','sebagian'])->default('menunggu');
            $table->text('ringkasan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['penerimaan_id'], 'uq_qc_penerimaan');
        });

        Schema::create('inspeksi_qc_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspeksi_qc_id')->constrained('inspeksi_qc')->cascadeOnDelete();
            $table->foreignId('penerimaan_detail_id')->constrained('penerimaan_detail')->cascadeOnDelete();
            $table->enum('hasil', ['menunggu','lulus','gagal'])->default('menunggu');
            $table->text('catatan_cacat')->nullable();
            $table->decimal('qty_diterima', 18, 4)->default(0);
            $table->decimal('qty_ditolak', 18, 4)->default(0);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['inspeksi_qc_id','penerimaan_detail_id'], 'uq_qc_detail');
        });

        Schema::create('saldo_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('gudang_id')->constrained('gudang');
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi_gudang')->nullOnDelete();
            $table->foreignId('barang_id')->constrained('barang');
            $table->string('no_lot', 120)->nullable();
            $table->date('tanggal_kedaluwarsa')->nullable();
            $table->decimal('qty_tersedia', 18, 4)->default(0);
            $table->decimal('qty_dipesan', 18, 4)->default(0);
            $table->decimal('qty_bisa_dipakai', 18, 4)->default(0);
            $table->timestamp('pergerakan_terakhir_pada')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['gudang_id','lokasi_id','barang_id','no_lot','tanggal_kedaluwarsa'], 'uq_saldo_kunci');
        });

        Schema::create('pergerakan_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->string('nomor_pergerakan', 120);
            $table->enum('jenis_pergerakan', ['penerimaan','pengeluaran','transfer','penyesuaian','reservasi','batal_reservasi','penyesuaian_opname']);
            $table->string('tipe_referensi', 80)->nullable();
            $table->unsignedBigInteger('id_referensi')->nullable();
            $table->dateTime('tanggal_pergerakan');
            $table->foreignId('gudang_id')->nullable()->constrained('gudang')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->foreignId('diposting_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->enum('status', ['draft','diposting','dibatalkan'])->default('draft');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','nomor_pergerakan'], 'uq_pergerakan_no');
            $table->index(['tipe_referensi','id_referensi'], 'idx_pergerakan_ref');
        });

        Schema::create('detail_pergerakan_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pergerakan_stok_id')->constrained('pergerakan_stok')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang');
            $table->foreignId('dari_gudang_id')->nullable()->constrained('gudang')->nullOnDelete();
            $table->foreignId('dari_lokasi_id')->nullable()->constrained('lokasi_gudang')->nullOnDelete();
            $table->foreignId('ke_gudang_id')->nullable()->constrained('gudang')->nullOnDelete();
            $table->foreignId('ke_lokasi_id')->nullable()->constrained('lokasi_gudang')->nullOnDelete();
            $table->string('no_lot', 120)->nullable();
            $table->date('tanggal_kedaluwarsa')->nullable();
            $table->decimal('qty', 18, 4)->default(0);
            $table->decimal('biaya_satuan', 18, 4)->default(0);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('permintaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('unit_organisasi_id')->nullable()->constrained('unit_organisasi')->nullOnDelete();
            $table->string('nomor_permintaan', 120);
            $table->dateTime('tanggal_permintaan');
            $table->foreignId('pemohon_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->enum('tipe_permintaan', ['habis_pakai','penugasan_aset','peminjaman_aset'])->default('habis_pakai');
            $table->enum('prioritas', ['rendah','normal','tinggi','mendesak'])->default('normal');
            $table->enum('status', ['draft','diajukan','disetujui','ditolak','dipenuhi','dibatalkan'])->default('draft');
            $table->text('tujuan')->nullable();
            $table->date('dibutuhkan_pada')->nullable();
            $table->text('catatan_persetujuan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','nomor_permintaan'], 'uq_permintaan_no');
        });

        Schema::create('permintaan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_id')->constrained('permintaan')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang');
            $table->decimal('qty_diminta', 18, 4)->default(0);
            $table->decimal('qty_disetujui', 18, 4)->default(0);
            $table->decimal('qty_dipenuhi', 18, 4)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['permintaan_id','barang_id'], 'uq_permintaan_detail');
        });

        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('gudang_id')->constrained('gudang');
            $table->foreignId('unit_organisasi_id')->nullable()->constrained('unit_organisasi')->nullOnDelete();
            $table->foreignId('permintaan_id')->nullable()->constrained('permintaan')->nullOnDelete();
            $table->string('nomor_pengeluaran', 120);
            $table->dateTime('tanggal_pengeluaran');
            $table->foreignId('diserahkan_ke_pengguna_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->foreignId('diserahkan_ke_unit_id')->nullable()->constrained('unit_organisasi')->nullOnDelete();
            $table->enum('status', ['draft','dipicking','dikeluarkan','dibatalkan'])->default('draft');
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->foreignId('diposting_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','nomor_pengeluaran'], 'uq_pengeluaran_no');
        });

        Schema::create('pengeluaran_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengeluaran_id')->constrained('pengeluaran')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang');
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi_gudang')->nullOnDelete();
            $table->string('no_lot', 120)->nullable();
            $table->date('tanggal_kedaluwarsa')->nullable();
            $table->decimal('qty', 18, 4)->default(0);
            $table->decimal('biaya_satuan', 18, 4)->default(0);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('transfer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->string('nomor_transfer', 120);
            $table->dateTime('tanggal_transfer');
            $table->foreignId('dari_gudang_id')->constrained('gudang');
            $table->foreignId('ke_gudang_id')->constrained('gudang');
            $table->enum('status', ['draft','dikirim','diterima','dibatalkan'])->default('draft');
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->foreignId('diposting_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','nomor_transfer'], 'uq_transfer_no');
        });

        Schema::create('transfer_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_id')->constrained('transfer')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang');
            $table->foreignId('dari_lokasi_id')->nullable()->constrained('lokasi_gudang')->nullOnDelete();
            $table->foreignId('ke_lokasi_id')->nullable()->constrained('lokasi_gudang')->nullOnDelete();
            $table->string('no_lot', 120)->nullable();
            $table->date('tanggal_kedaluwarsa')->nullable();
            $table->decimal('qty', 18, 4)->default(0);
            $table->decimal('biaya_satuan', 18, 4)->default(0);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('barang_id')->constrained('barang');
            $table->string('tag_aset', 120);
            $table->string('no_serial', 160)->nullable();
            $table->string('imei', 40)->nullable();
            $table->string('no_mesin', 80)->nullable();
            $table->string('no_rangka', 80)->nullable();
            $table->string('no_polisi', 30)->nullable();
            $table->date('tanggal_beli')->nullable();
            $table->foreignId('penerimaan_id')->nullable()->constrained('penerimaan')->nullOnDelete();
            $table->foreignId('unit_organisasi_saat_ini_id')->nullable()->constrained('unit_organisasi')->nullOnDelete();
            $table->foreignId('gudang_saat_ini_id')->nullable()->constrained('gudang')->nullOnDelete();
            $table->foreignId('lokasi_saat_ini_id')->nullable()->constrained('lokasi_gudang')->nullOnDelete();
            $table->foreignId('pemegang_pengguna_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->enum('status_kondisi', ['baik','rusak_ringan','rusak_berat','hilang','dalam_perbaikan','dihapus'])->default('baik');
            $table->enum('status_siklus', ['tersedia','dipinjam','ditugaskan','disimpan','nonaktif','dihapus'])->default('tersedia');
            $table->decimal('biaya_perolehan', 18, 2)->default(0);
            $table->string('mata_uang', 10)->default('IDR');
            $table->json('extra')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','tag_aset'], 'uq_aset_tag');
            $table->unique(['instansi_id','no_serial'], 'uq_aset_serial');
        });

        Schema::create('penugasan_aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('aset_id')->constrained('aset')->cascadeOnDelete();
            $table->foreignId('ditugaskan_ke_pengguna_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->foreignId('ditugaskan_ke_unit_id')->nullable()->constrained('unit_organisasi')->nullOnDelete();
            $table->dateTime('tanggal_tugas');
            $table->dateTime('tanggal_kembali')->nullable();
            $table->enum('status', ['aktif','dikembalikan','dibatalkan'])->default('aktif');
            $table->string('nomor_dok_serah_terima', 120)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('peminjaman_aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('aset_id')->constrained('aset')->cascadeOnDelete();
            $table->foreignId('peminjam_pengguna_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->foreignId('peminjam_unit_id')->nullable()->constrained('unit_organisasi')->nullOnDelete();
            $table->dateTime('tanggal_mulai');
            $table->dateTime('jatuh_tempo')->nullable();
            $table->dateTime('tanggal_kembali')->nullable();
            $table->enum('status', ['aktif','terlambat','dikembalikan','dibatalkan'])->default('aktif');
            $table->text('tujuan')->nullable();
            $table->enum('kondisi_keluar', ['baik','rusak_ringan','rusak_berat'])->default('baik');
            $table->enum('kondisi_masuk', ['baik','rusak_ringan','rusak_berat'])->nullable();
            $table->string('nomor_dok_serah_terima', 120)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('log_kondisi_aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('aset_id')->constrained('aset')->cascadeOnDelete();
            $table->dateTime('tanggal_log');
            $table->enum('status_kondisi', ['baik','rusak_ringan','rusak_berat','hilang','dalam_perbaikan','dihapus']);
            $table->text('catatan')->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
        });

        Schema::create('penghapusan_aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('aset_id')->constrained('aset')->cascadeOnDelete();
            $table->string('nomor_penghapusan', 120);
            $table->date('tanggal_penghapusan');
            $table->enum('metode', ['hapus','hibah','lelang','rusak_total','lainnya'])->default('hapus');
            $table->text('alasan')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->enum('status', ['draft','disetujui','dieksekusi','dibatalkan'])->default('draft');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','nomor_penghapusan'], 'uq_penghapusan_no');
        });

        Schema::create('rencana_perawatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->string('kode', 80);
            $table->string('nama', 200);
            $table->enum('tipe', ['berdasarkan_waktu','berdasarkan_pemakaian'])->default('berdasarkan_waktu');
            $table->unsignedInteger('interval_hari')->nullable();
            $table->decimal('interval_pemakaian', 18, 2)->nullable();
            $table->json('checklist')->nullable();
            $table->enum('status', ['aktif','nonaktif'])->default('aktif');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','kode'], 'uq_rencana_perawatan_kode');
        });

        Schema::create('rencana_perawatan_aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rencana_perawatan_id')->constrained('rencana_perawatan')->cascadeOnDelete();
            $table->foreignId('aset_id')->constrained('aset')->cascadeOnDelete();
            $table->dateTime('terakhir_selesai_pada')->nullable();
            $table->dateTime('jatuh_tempo_pada')->nullable();
            $table->decimal('nilai_pemakaian_terakhir', 18, 2)->nullable();
            $table->decimal('nilai_pemakaian_jatuh_tempo', 18, 2)->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['rencana_perawatan_id','aset_id'], 'uq_rencana_perawatan_aset');
        });

        Schema::create('perintah_kerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->string('nomor_pk', 120);
            $table->dateTime('tanggal_pk');
            $table->foreignId('aset_id')->constrained('aset')->cascadeOnDelete();
            $table->foreignId('rencana_perawatan_id')->nullable()->constrained('rencana_perawatan')->nullOnDelete();
            $table->enum('tipe', ['preventif','korektif','inspeksi'])->default('preventif');
            $table->enum('prioritas', ['rendah','normal','tinggi','mendesak'])->default('normal');
            $table->enum('status', ['draft','dibuka','diproses','selesai','dibatalkan'])->default('draft');
            $table->text('masalah_dilaporkan')->nullable();
            $table->text('penyelesaian')->nullable();
            $table->string('nama_vendor', 255)->nullable();
            $table->decimal('biaya', 18, 2)->default(0);
            $table->string('mata_uang', 10)->default('IDR');
            $table->foreignId('dibuka_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->foreignId('ditutup_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->dateTime('dibuka_pada')->nullable();
            $table->dateTime('ditutup_pada')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','nomor_pk'], 'uq_pk_no');
        });

        Schema::create('pembaruan_perintah_kerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perintah_kerja_id')->constrained('perintah_kerja')->cascadeOnDelete();
            $table->dateTime('waktu_update');
            $table->enum('status', ['dibuka','diproses','selesai','dibatalkan']);
            $table->text('catatan')->nullable();
            $table->foreignId('diupdate_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
        });

        Schema::create('suku_cadang_perintah_kerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perintah_kerja_id')->constrained('perintah_kerja')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang');
            $table->decimal('qty', 18, 4)->default(0);
            $table->decimal('biaya_satuan', 18, 4)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
        });

        Schema::create('stok_opname', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('gudang_id')->constrained('gudang');
            $table->string('nomor_opname', 120);
            $table->date('tanggal_opname');
            $table->enum('tipe', ['siklus','penuh'])->default('siklus');
            $table->enum('status', ['draft','menghitung','rekonsiliasi','diposting','dibatalkan'])->default('draft');
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->foreignId('diposting_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->dateTime('diposting_pada')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','nomor_opname'], 'uq_opname_no');
        });

        Schema::create('stok_opname_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_opname_id')->constrained('stok_opname')->cascadeOnDelete();
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi_gudang')->nullOnDelete();
            $table->foreignId('barang_id')->constrained('barang');
            $table->string('no_lot', 120)->nullable();
            $table->date('tanggal_kedaluwarsa')->nullable();
            $table->decimal('qty_sistem', 18, 4)->default(0);
            $table->decimal('qty_hitung', 18, 4)->default(0);
            $table->decimal('qty_selisih', 18, 4)->default(0);
            $table->text('alasan_selisih')->nullable();
            $table->foreignId('dihitung_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->dateTime('dihitung_pada')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('berkas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->enum('penyedia', ['lokal','s3','gcs','azure','lainnya'])->default('lokal');
            $table->string('kunci_berkas', 600);
            $table->string('nama_berkas', 255);
            $table->string('tipe_mime', 160)->nullable();
            $table->unsignedBigInteger('ukuran_byte')->default(0);
            $table->string('checksum_sha256', 80)->nullable();
            $table->foreignId('diunggah_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->dateTime('diunggah_pada');
            $table->timestamp('dibuat_pada')->useCurrent();
        });

        Schema::create('lampiran_entitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->string('tipe_entitas', 80);
            $table->unsignedBigInteger('id_entitas');
            $table->foreignId('berkas_id')->constrained('berkas')->cascadeOnDelete();
            $table->string('judul', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->index(['tipe_entitas','id_entitas'], 'idx_lampiran_entitas');
        });

        Schema::create('tanda_tangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->string('tipe_entitas', 80);
            $table->unsignedBigInteger('id_entitas');
            $table->foreignId('penanda_tangan_id')->constrained('pengguna');
            $table->string('peran_penanda', 160)->nullable();
            $table->dateTime('ditandatangani_pada');
            $table->enum('metode', ['persetujuan','otp','sertifikat','manual'])->default('persetujuan');
            $table->json('data_tanda')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->index(['tipe_entitas','id_entitas'], 'idx_ttd_entitas');
        });

        Schema::create('alur_persetujuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->string('kode', 80);
            $table->string('nama', 200);
            $table->string('berlaku_untuk', 80);
            $table->enum('status', ['aktif','nonaktif'])->default('aktif');
            $table->json('aturan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','kode'], 'uq_alur_kode');
        });

        Schema::create('langkah_alur_persetujuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alur_persetujuan_id')->constrained('alur_persetujuan')->cascadeOnDelete();
            $table->unsignedInteger('no_langkah');
            $table->string('nama_langkah', 200);
            $table->enum('tipe_penyetuju', ['peran','pengguna','unit_peran'])->default('peran');
            $table->foreignId('peran_id')->nullable()->constrained('peran')->nullOnDelete();
            $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->foreignId('unit_organisasi_id')->nullable()->constrained('unit_organisasi')->nullOnDelete();
            $table->boolean('harus_semua')->default(false);
            $table->json('kondisi')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['alur_persetujuan_id','no_langkah'], 'uq_langkah_alur');
        });

        Schema::create('permintaan_persetujuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('alur_persetujuan_id')->constrained('alur_persetujuan');
            $table->string('tipe_entitas', 80);
            $table->unsignedBigInteger('id_entitas');
            $table->string('nomor_persetujuan', 120);
            $table->foreignId('diminta_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->dateTime('diminta_pada');
            $table->enum('status', ['menunggu','disetujui','ditolak','dibatalkan'])->default('menunggu');
            $table->unsignedInteger('langkah_saat_ini')->default(1);
            $table->text('ringkasan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['instansi_id','nomor_persetujuan'], 'uq_permintaan_persetujuan_no');
            $table->index(['tipe_entitas','id_entitas'], 'idx_permintaan_persetujuan_entitas');
        });

        Schema::create('langkah_permintaan_persetujuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_persetujuan_id')->constrained('permintaan_persetujuan')->cascadeOnDelete();
            $table->unsignedInteger('no_langkah');
            $table->string('nama_langkah', 200);
            $table->enum('status', ['menunggu','disetujui','ditolak','dilewati'])->default('menunggu');
            $table->foreignId('diputuskan_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->dateTime('diputuskan_pada')->nullable();
            $table->text('catatan_keputusan')->nullable();
            $table->json('snapshot')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['permintaan_persetujuan_id','no_langkah'], 'uq_langkah_permintaan');
        });

        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->enum('kanal', ['aplikasi','email','sms','whatsapp','lainnya'])->default('aplikasi');
            $table->string('judul', 255);
            $table->text('isi');
            $table->string('tipe_entitas', 80)->nullable();
            $table->unsignedBigInteger('id_entitas')->nullable();
            $table->boolean('sudah_dibaca')->default(false);
            $table->dateTime('dibaca_pada')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->index(['tipe_entitas','id_entitas'], 'idx_notif_entitas');
        });

        Schema::create('log_audit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi');
            $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->enum('aksi', ['tambah','ubah','hapus','login','logout','setujui','tolak','posting','batal']);
            $table->string('nama_tabel', 120)->nullable();
            $table->unsignedBigInteger('id_rekaman')->nullable();
            $table->string('tipe_referensi', 80)->nullable();
            $table->unsignedBigInteger('id_referensi')->nullable();
            $table->string('ip_address', 60)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->json('data_lama')->nullable();
            $table->json('data_baru')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->index(['nama_tabel','id_rekaman'], 'idx_audit_tabel');
            $table->index(['tipe_referensi','id_referensi'], 'idx_audit_ref');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_audit');
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('langkah_permintaan_persetujuan');
        Schema::dropIfExists('permintaan_persetujuan');
        Schema::dropIfExists('langkah_alur_persetujuan');
        Schema::dropIfExists('alur_persetujuan');
        Schema::dropIfExists('tanda_tangan');
        Schema::dropIfExists('lampiran_entitas');
        Schema::dropIfExists('berkas');
        Schema::dropIfExists('stok_opname_detail');
        Schema::dropIfExists('stok_opname');
        Schema::dropIfExists('suku_cadang_perintah_kerja');
        Schema::dropIfExists('pembaruan_perintah_kerja');
        Schema::dropIfExists('perintah_kerja');
        Schema::dropIfExists('rencana_perawatan_aset');
        Schema::dropIfExists('rencana_perawatan');
        Schema::dropIfExists('penghapusan_aset');
        Schema::dropIfExists('log_kondisi_aset');
        Schema::dropIfExists('peminjaman_aset');
        Schema::dropIfExists('penugasan_aset');
        Schema::dropIfExists('aset');
        Schema::dropIfExists('transfer_detail');
        Schema::dropIfExists('transfer');
        Schema::dropIfExists('pengeluaran_detail');
        Schema::dropIfExists('pengeluaran');
        Schema::dropIfExists('permintaan_detail');
        Schema::dropIfExists('permintaan');
        Schema::dropIfExists('detail_pergerakan_stok');
        Schema::dropIfExists('pergerakan_stok');
        Schema::dropIfExists('saldo_stok');
        Schema::dropIfExists('inspeksi_qc_detail');
        Schema::dropIfExists('inspeksi_qc');
        Schema::dropIfExists('penerimaan_detail');
        Schema::dropIfExists('penerimaan');
        Schema::dropIfExists('pesanan_pembelian_detail');
        Schema::dropIfExists('pesanan_pembelian');
        Schema::dropIfExists('kontrak');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('pemasok');
        Schema::dropIfExists('kategori_barang');
        Schema::dropIfExists('satuan_barang');
        Schema::dropIfExists('lokasi_gudang');
        Schema::dropIfExists('gudang');
        Schema::dropIfExists('urutan_nomor_dokumen');
        Schema::dropIfExists('peran_izin');
        Schema::dropIfExists('pengguna_peran');
        Schema::dropIfExists('izin');
        Schema::dropIfExists('peran');
        Schema::dropIfExists('pengguna');
        Schema::dropIfExists('unit_organisasi');
        Schema::dropIfExists('instansi');
    }
};
