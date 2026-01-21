<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BarangExport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents
{
    public function __construct(private int $instansiId) {}

    public function collection(): Collection
    {
        $rows = DB::table('barang')
            ->leftJoin('kategori_barang', 'kategori_barang.id', '=', 'barang.kategori_id')
            ->leftJoin('satuan_barang', 'satuan_barang.id', '=', 'barang.satuan_id')
            ->where('barang.instansi_id', $this->instansiId)
            ->orderBy('barang.nama')
            ->get([
                'barang.sku',
                'barang.nama',
                'kategori_barang.nama as kategori_nama',
                'satuan_barang.nama as satuan_nama',
                'barang.status',
            ]);
        $no = 1;
        return $rows->map(function ($b) use (&$no) {
            return [
                'No' => $no++,
                'Kategori' => $b->kategori_nama ?? '-',
                'SKU' => $b->sku ?? '',
                'Nama Barang' => $b->nama ?? '',
                'Satuan' => $b->satuan_nama ?? '-',
                'Status' => $b->status ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Kategori', 'SKU', 'Nama Barang', 'Satuan',  'Status'];
    }

    public function startCell(): string
    {
        return 'B5';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $instansi = DB::table('instansi')->where('id', $this->instansiId)->value('nama') ?? '-';
                $sheet = $event->sheet->getDelegate();

                $highestCol = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();

                $mergeTo = $highestCol;

                $sheet->setCellValue('A1', 'DATA BARANG');
                $sheet->mergeCells("A1:{$mergeTo}1");

                $sheet->setCellValue('A2', 'Nama Instansi: ' . $instansi);
                $sheet->mergeCells("A2:{$mergeTo}2");

                $sheet->setCellValue('A3', 'Tanggal Export: ' . Carbon::now()->format('d M Y H:i'));
                $sheet->mergeCells("A3:{$mergeTo}3");

                $sheet->getStyle("A1:A3")->getFont()->setBold(true);
                $sheet->getStyle("A1")->getFont()->setSize(16);

                $sheet->getStyle("A5:{$mergeTo}5")->getFont()->setBold(true);

                $sheet->freezePane('A6');

                $sheet->getStyle("B5:{$mergeTo}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['argb' => 'FF000000'],
                        ],
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                foreach (range('A', $mergeTo) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
