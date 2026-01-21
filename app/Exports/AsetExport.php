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

class AsetExport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents
{
    public function __construct(private int $instansiId) {}

    public function collection(): Collection
    {
        $rows = DB::table('aset')
            ->leftJoin('barang', 'barang.id', '=', 'aset.barang_id')
            ->leftJoin('gudang', 'gudang.id', '=', 'aset.gudang_saat_ini_id')
            ->where('aset.instansi_id', $this->instansiId)
            ->orderBy('aset.id', 'desc')
            ->get([
                'aset.tag_aset',
                'aset.status_kondisi',
                'aset.status_siklus as status',
                'barang.sku as barang_sku',
                'barang.nama as barang_nama',
                'gudang.nama as gudang_nama',
            ]);
        $no = 1;
        return $rows->map(function ($a) use (&$no) {
            return [
                'No' => $no++,
                'Tag Aset' => $a->tag_aset ?? '',
                'Barang (SKU)' => $a->barang_sku ?? '',
                'Barang (Nama)' => $a->barang_nama ?? '',
                'Gudang' => $a->gudang_nama ?? '',
                'Status Kondisi' => $a->status_kondisi ?? '',
                'Status Aset' => $a->status ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Tag Aset', 'Barang (SKU)', 'Aset (Nama)', 'Gudang', 'Status Kondisi', 'Status Aset'];
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

                $sheet->setCellValue('A1', 'DATA ASET');
                $sheet->mergeCells('A1:H1');

                $sheet->setCellValue('A2', 'Nama Instansi: ' . $instansi);
                $sheet->mergeCells('A2:H2');

                $sheet->setCellValue('A3', 'Tanggal Export: ' . Carbon::now()->format('d M Y H:i'));
                $sheet->mergeCells('A3:H3');

                $sheet->getStyle('A1:A3')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getFont()->setSize(14);

                $sheet->getStyle('A5:H5')->getFont()->setBold(true);

                $sheet->freezePane('A6');

                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("B5:H{$highestRow}")->applyFromArray([
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

                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
