<?php

return [
    [
        'label' => 'Dashboard',
        'route' => 'dashboard',
        'logo' => 'dashboard',
        'izin'  => [],
    ],

    [
        'label' => 'Master',
        'izin'  => [],
        'children' => [
            [
                'label' => 'Instansi',
                'route' => 'instansi.index',
                'logo' => 'instansi',
                'izin'  => ['instansi.lihat', 'instansi.kelola'],
            ],
            [
                'label' => 'Unit Organisasi',
                'route' => 'unit-organisasi.index',
                'logo' => 'unit_organisasi',
                'izin'  => ['unit_org.lihat', 'unit_org.kelola'],
            ],
            [
                'label' => 'Gudang',
                'route' => 'gudang.index',
                'logo' => 'gudang',
                'izin'  => ['gudang.lihat', 'gudang.kelola'],
            ],
            [
                'label' => 'Lokasi Gudang',
                'route' => 'lokasi-gudang.index',
                'logo' => 'lokasi_gudang',
                'izin'  => ['lokasi_gudang.lihat', 'lokasi_gudang.kelola'],
            ],
            [
                'label' => 'Barang',
                'route' => 'barang.index',
                'logo' => 'barang',
                'izin'  => ['barang.lihat', 'barang.kelola'],
            ],
            [
                'label' => 'Kategori Barang',
                'route' => 'kategori-barang.index',
                'logo' => 'kategori_barang',
                'izin'  => ['kategori_barang.lihat', 'kategori_barang.kelola'],
            ],
            [
                'label' => 'Satuan Barang',
                'route' => 'satuan-barang.index',
                'logo' => 'satuan_barang',
                'izin'  => ['satuan_barang.lihat', 'satuan_barang.kelola'],
            ],
            [
                'label' => 'Pemasok',
                'route' => 'pemasok.index',
                'logo' => 'pemasok',
                'izin'  => ['pemasok.lihat', 'pemasok.kelola'],
            ],
        ],
    ],

    [
        'label' => 'Transaksi',
        'izin'  => [],
        'children' => [
            [
                'label' => 'Pesanan Pembelian',
                'route' => 'pesanan-pembelian.index',
                'logo' => 'pesanan_pembelian',
                'izin'  => ['pesanan_pembelian.lihat', 'pesanan_pembelian.kelola'],
            ],
            [
                'label' => 'Penerimaan',
                'route' => 'penerimaan.index',
                'logo' => 'penerimaan',
                'izin'  => ['penerimaan.lihat', 'penerimaan.kelola'],
            ],
            [
                'label' => 'QC',
                'route' => 'inspeksi-qc.index',
                'logo' => 'QC',
                'izin'  => ['qc.lihat', 'qc.kelola'],
            ],
            [
                'label' => 'Permintaan',
                'route' => 'permintaan.index',
                'logo' => 'permintaan',
                'izin'  => ['permintaan.lihat', 'permintaan.kelola'],
            ],
            [
                'label' => 'Pengeluaran',
                'route' => 'pengeluaran.index',
                'logo' => 'pengeluaran',
                'izin'  => ['pengeluaran.lihat', 'pengeluaran.kelola'],
            ]
        ],
    ],

    [
        'label' => 'Aset',
        'izin'  => [],
        'children' => [
            [
                'label' => 'Daftar Aset',
                'route' => 'aset.index',
                'logo' => 'daftar_aset',
                'izin'  => ['aset.lihat', 'aset.kelola'],
            ],
            [
                'label' => 'Penugasan Aset',
                'route' => 'penugasan-aset.index',
                'logo' => 'penugasan_aset',
                'izin'  => ['penugasan_aset.lihat', 'penugasan_aset.kelola'],
            ],
            [
                'label' => 'Peminjaman Aset',
                'route' => 'peminjaman-aset.index',
                'logo' => 'peminjaman_aset',
                'izin'  => ['peminjaman_aset.lihat', 'peminjaman_aset.kelola'],
            ],
            [
                'label' => 'Penghapusan Aset',
                'route' => 'penghapusan-aset.index',
                'logo' => 'penghapusan_aset',
                'izin'  => ['penghapusan_aset.lihat', 'penghapusan_aset.kelola'],
            ],
        ],
    ],
    [
        'label' => 'Stok',
        'izin'  => [],
        'children' => [
            [
                'label' => 'Saldo Stok',
                'route' => 'saldo-stok.index',
                'logo' => 'penghapusan_aset',
                'izin'  => ['saldo_stok.lihat'],
            ],
            [
                'label' => 'Pergerakan Stok',
                'route' => 'pergerakan-stok.index',
                'logo' => 'penghapusan_aset',
                'izin'  => ['pergerakan_stok.lihat'],
            ],
        ],
    ],

    [
        'label' => 'Persetujuan',
        'izin'  => [],
        'children' => [
            [
                'label' => 'Alur Persetujuan',
                'route' => 'alur-persetujuan.index',
                'logo' => 'penghapusan_aset',
                'izin'  => ['alur_persetujuan.lihat', 'alur_persetujuan.kelola'],
            ],
            [
                'label' => 'Permintaan Persetujuan',
                'route' => 'permintaan-persetujuan.index',
                'logo' => 'penghapusan_aset',
                'izin'  => ['permintaan_persetujuan.lihat', 'permintaan_persetujuan.kelola'],
            ],
        ],
    ],

    [
        'label' => 'Admin',
        'izin'  => [],
        'children' => [
            [
                'label' => 'Pengguna',
                'route' => 'pengguna.index',
                'logo' => 'penghapusan_aset',
                'izin'  => ['pengguna.lihat', 'pengguna.kelola'],
            ],
            [
                'label' => 'Peran',
                'route' => 'peran.index',
                'logo' => 'penghapusan_aset',
                'izin'  => ['peran.lihat', 'peran.kelola'],
            ],
            [
                'label' => 'Izin',
                'route' => 'izin.index',
                'logo' => 'penghapusan_aset',
                'izin'  => ['izin.lihat', 'izin.kelola'],
            ],
            [
                'label' => 'Log Audit',
                'logo' => 'penghapusan_aset',
                'route' => 'log-audit.index',
                'izin'  => ['audit.lihat'],
            ],
        ],
    ],
];
