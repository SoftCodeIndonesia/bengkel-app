<?php

namespace App\Exports;


use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpensesSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected $expenses;

    public function __construct($expenses)
    {
        $this->expenses = $expenses;
    }

    public function array(): array
    {
        $rows = [];
        $grouped = $this->expenses->groupBy(fn($exp) => $exp->category->name ?? 'Tanpa Kategori');

        foreach ($grouped as $category => $items) {
            $firstRow = true;
            foreach ($items as $exp) {
                $rows[] = [
                    $firstRow ? $category : '',
                    $exp->description,
                    $exp->date ? $exp->date->translatedFormat('d F Y') : '',
                    $exp->amount,
                ];
                $firstRow = false;
            }
        }

        // Tambah row total
        $rows[] = [
            'TOTAL',
            '',
            '',
            '=SUM(D2:D' . (count($rows) + 1) . ')',
        ];

        return $rows;
    }

    public function headings(): array
    {
        return ['Kategori', 'Deskripsi', 'Tanggal', 'Nominal'];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();

        // Heading style
        $sheet->getStyle('A1:' . $highestCol . '1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'D9D9D9'],
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);

        // Border semua cell
        $sheet->getStyle('A1:' . $highestCol . $highestRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Format angka kolom D (Nominal)
        $sheet->getStyle('D2:D' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('D2:D' . $highestRow)->getAlignment()->setHorizontal('right');

        // Bold baris total
        $sheet->getStyle('A' . $highestRow . ':' . $highestCol . $highestRow)->applyFromArray([
            'font' => ['bold' => true],
        ]);

        return [];
    }

    public function title(): string
    {
        return 'Pengeluaran';
    }
}
