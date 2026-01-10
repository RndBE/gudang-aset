<?php

return [
    [
        'label' => 'Dashboard',
        'route' => 'dashboard',
        'izin'  => [],
    ],

    [
        'label' => 'Master',
        'izin'  => [],
        'children' => [
            [
                'label' => 'Instansi',
                'route' => 'instansi.index',
                'izin'  => ['instansi.lihat', 'instansi.kelola'],
            ],
            [
                'label' => 'Unit Organisasi',
                'route' => 'unit-organisasi.index',
                'izin'  => ['unit_org.lihat', 'unit_org.kelola'],
            ],
            [
                'label' => 'Gudang',
                'route' => 'gudang.index',
                'izin'  => ['gudang.lihat', 'gudang.kelola'],
            ],
            [
                'label' => 'Lokasi Gudang',
                'route' => 'lokasi-gudang.index',
                'izin'  => ['lokasi_gudang.lihat', 'lokasi_gudang.kelola'],
            ],
            [
                'label' => 'Barang',
                'route' => 'barang.index',
                'izin'  => ['barang.lihat', 'barang.kelola'],
            ],
            [
                'label' => 'Kategori Barang',
                'route' => 'kategori-barang.index',
                'izin'  => ['kategori_barang.lihat', 'kategori_barang.kelola'],
            ],
            [
                'label' => 'Satuan Barang',
                'route' => 'satuan-barang.index',
                'izin'  => ['satuan_barang.lihat', 'satuan_barang.kelola'],
            ],
            [
                'label' => 'Pemasok',
                'route' => 'pemasok.index',
                'izin'  => ['pemasok.lihat', 'pemasok.kelola'],
            ],
        ],
    ],

    // [
    //     'label' => 'Transaksi',
    //     'izin'  => [],
    //     'children' => [
    //         [
    //             'label' => 'Pesanan Pembelian',
    //             'route' => 'pesanan-pembelian.index',
    //             'izin'  => ['po.lihat', 'po.kelola'],
    //         ],
    //         [
    //             'label' => 'Penerimaan',
    //             'route' => 'penerimaan.index',
    //             'izin'  => ['penerimaan.lihat', 'penerimaan.kelola'],
    //         ],
    //         [
    //             'label' => 'QC',
    //             'route' => 'inspeksi-qc.index',
    //             'izin'  => ['qc.lihat', 'qc.kelola'],
    //         ],
    //         [
    //             'label' => 'Permintaan',
    //             'route' => 'permintaan.index',
    //             'izin'  => ['permintaan.lihat', 'permintaan.kelola'],
    //         ],
    //         [
    //             'label' => 'Pengeluaran',
    //             'route' => 'pengeluaran.index',
    //             'izin'  => ['pengeluaran.lihat', 'pengeluaran.kelola'],
    //         ],
    //         [
    //             'label' => 'Transfer',
    //             'route' => 'transfer.index',
    //             'izin'  => ['transfer.lihat', 'transfer.kelola'],
    //         ],
    //         [
    //             'label' => 'Stok Opname',
    //             'route' => 'stok-opname.index',
    //             'izin'  => ['opname.lihat', 'opname.kelola'],
    //         ],
    //     ],
    // ],

    [
        'label' => 'Aset',
        'izin'  => [],
        'children' => [
            [
                'label' => 'Daftar Aset',
                'route' => 'aset.index',
                'izin'  => ['aset.lihat', 'aset.kelola'],
            ],
            [
                'label' => 'Penugasan Aset',
                'route' => 'penugasan-aset.index',
                'izin'  => ['penugasan_aset.lihat', 'penugasan_aset.kelola'],
            ],
            [
                'label' => 'Peminjaman Aset',
                'route' => 'peminjaman-aset.index',
                'izin'  => ['peminjaman_aset.lihat', 'peminjaman_aset.kelola'],
            ],
            [
                'label' => 'Penghapusan Aset',
                'route' => 'penghapusan-aset.index',
                'izin'  => ['penghapusan_aset.lihat', 'penghapusan_aset.kelola'],
            ],
            // [
            //     'label' => 'Perawatan',
            //     'route' => 'rencana-perawatan.index',
            //     'izin'  => ['perawatan.lihat', 'perawatan.kelola'],
            // ],
            // [
            //     'label' => 'Perintah Kerja',
            //     'route' => 'perintah-kerja.index',
            //     'izin'  => ['pk.lihat', 'pk.kelola'],
            // ],
        ],
    ],

    // [
    //     'label' => 'Persetujuan',
    //     'izin'  => [],
    //     'children' => [
    //         [
    //             'label' => 'Alur Persetujuan',
    //             'route' => 'alur-persetujuan.index',
    //             'izin'  => ['alur_persetujuan.lihat', 'alur_persetujuan.kelola'],
    //         ],
    //         [
    //             'label' => 'Permintaan Persetujuan',
    //             'route' => 'permintaan-persetujuan.index',
    //             'izin'  => ['permintaan_persetujuan.lihat', 'permintaan_persetujuan.kelola'],
    //         ],
    //     ],
    // ],

    [
        'label' => 'Admin',
        'izin'  => [],
        'children' => [
            [
                'label' => 'Pengguna',
                'route' => 'pengguna.index',
                'izin'  => ['pengguna.lihat', 'pengguna.kelola'],
            ],
            [
                'label' => 'Peran',
                'route' => 'peran.index',
                'izin'  => ['peran.lihat', 'peran.kelola'],
            ],
            [
                'label' => 'Izin',
                'route' => 'izin.index',
                'izin'  => ['izin.lihat', 'izin.kelola'],
            ],
            // [
            //     'label' => 'Log Audit',
            //     'route' => 'log-audit.index',
            //     'izin'  => ['audit.lihat'],
            // ],
        ],
    ],
];
