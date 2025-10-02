<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchaseSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected $purchases;

    public function __construct($purchases)
    {
        $this->purchases = $purchases;
    }

    public function array(): array
    {
        $rows = [];
        $counter = 1;

        foreach ($this->purchases as $purchase) {
            $rowFirst = true;

            foreach ($purchase->items as $item) {
                $rows[] = [
                    $rowFirst ? ($purchase->supplier->name ?? '-') : '', // Supplier hanya sekali
                    $rowFirst ? ($purchase->purchase_date ? Carbon::parse($purchase->purchase_date)->translatedFormat('d F Y') : '') : '', // Tanggal sekali
                    $item->product->name ?? '-',     // Nama Part
                    $item->product->grade ?? '-',    // Grade
                    $item->quantity,                   // Qty
                    $item->unit_price,                 // Harga Beli
                    $item->quantity * $item->unit_price,    // Total
                ];

                $rowFirst = false;
            }

            $counter++;
        }

        // Tambahkan total row
        $rows[] = [
            'TOTAL',
            '',
            '',
            '',
            '=SUM(E2:E' . (count($rows) + 1) . ')', // total qty
            '',
            '=SUM(G2:G' . (count($rows) + 1) . ')', // total pembelian
        ];

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Supplier',
            'Tanggal',
            'Nama Part',
            'Grade',
            'QTY',
            'Harga Beli',
            'Total',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();

        // Heading style
        $sheet->getStyle('A1:' . $highestCol . '1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'D9D9D9'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Border semua cell
        $sheet->getStyle('A1:' . $highestCol . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Format angka & alignment
        $sheet->getStyle('E2:E' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');  // Qty
        $sheet->getStyle('F2:F' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');  // Harga Beli
        $sheet->getStyle('G2:G' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');  // Total

        $sheet->getStyle('E2:G' . $highestRow)->getAlignment()->setHorizontal('right'); // angka rata kanan

        // Bold row terakhir (TOTAL)
        $sheet->getStyle('A' . $highestRow . ':' . $highestCol . $highestRow)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        return [];
    }

    public function title(): string
    {
        return 'Pembelian';
    }
}
