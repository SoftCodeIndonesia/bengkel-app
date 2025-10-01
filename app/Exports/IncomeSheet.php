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

class IncomeSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected $jobOrderIncome;
    protected $salesIncome;

    public function __construct($jobOrderIncome, $salesIncome)
    {
        $this->jobOrderIncome = $jobOrderIncome;
        $this->salesIncome = $salesIncome;
    }

    public function array(): array
    {
        $rows = [];
        $totalJasa = 0;
        $totalQtyPart = 0;
        $totalHargaPart = 0;
        $totalHargaBeliPart = 0;
        $totalPart = 0;
        $totalMarginNominal = 0;

        // Work Orders
        foreach ($this->jobOrderIncome as $wo) {
            foreach ($wo->service as $keyJasa => $jasa) {
                foreach ($wo->sparepart as $part) {
                    $marginNominal = $part->price_after_diskon - ($part->product->buying_price * $part->quantity);
                    $margin = $part->price_after_diskon > 0
                        ? ($marginNominal / $part->price_after_diskon) * 100
                        : 0;

                    $rows[] = [
                        'WO',
                        $keyJasa == 0 ? $wo->unique_id : '',
                        $wo->service_at->format('d-m-Y'),
                        $jasa->product->name,
                        $jasa->quantity,
                        100000 * $jasa->quantity * ($jasa->diskon_value / 100),
                        $jasa->price_after_diskon,
                        $part->product->name,
                        $part->quantity,
                        $part->product->buying_price,
                        $part->unit_price,
                        $part->price_after_diskon,
                        round($margin, 2) . '%',
                        $marginNominal,
                    ];

                    $totalJasa += $jasa->total_price;
                    $totalQtyPart += $part->quantity;
                    $totalHargaPart += $part->unit_price * $part->quantity;
                    $totalHargaBeliPart += $part->product->buying_price * $part->quantity;
                    $totalPart += $part->price_after_diskon;
                    $totalMarginNominal += $marginNominal;
                }
            }
        }

        // Sales Orders
        foreach ($this->salesIncome as $key => $so) {
            foreach ($so->items as $keyPart => $part) {
                $marginNominal = $part->price_after_discount - ($part->product->buying_price * $part->quantity);
                $margin = $part->price_after_discount > 0
                    ? ($marginNominal / $part->price_after_discount) * 100
                    : 0;

                $rows[] = [
                    'SO',
                    $keyPart == 0 ? $so->unique_id : '',
                    $so->created_at->format('d-m-Y'),
                    '-',
                    '-',
                    '-',
                    '-',
                    $part->product->name,
                    $part->quantity,
                    $part->product->buying_price,
                    $part->unit_price,
                    $part->price_after_discount,
                    round($margin, 2) . '%',
                    $marginNominal,
                ];

                $totalQtyPart += $part->quantity;
                $totalHargaPart += $part->unit_price * $part->quantity;
                $totalHargaBeliPart += $part->product->buying_price * $part->quantity;
                $totalPart += $part->price_after_discount;
                $totalMarginNominal += $marginNominal;
            }
        }

        // TOTAL row
        $rows[] = [
            'TOTAL',
            '',
            '',
            'TOTAL JASA',
            '',
            '',
            $totalJasa,
            '',
            $totalQtyPart,
            $totalHargaBeliPart,
            $totalHargaPart,
            $totalPart,
            '',
            $totalMarginNominal,
        ];

        return $rows;
    }

    public function headings(): array
    {
        return [
            'SUMBER',
            'NOMOR DOKUMEN',
            'TANGGAL',
            'NAMA JASA',
            'FRT',
            'DISKON JASA',
            'TOTAL JASA',
            'NAMA PART',
            'QTY',
            'HARGA BELI SATUAN',
            'HARGA JUAL SATUAN',
            'TOTAL SETELAH DISKON',
            'MARGIN PART (%)',
            'MARGIN PART (RP)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();

        // Header styling
        $sheet->getStyle('A1:' . $highestCol . '1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'D9D9D9'], // abu-abu terang
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Apply border ke semua cell
        $sheet->getStyle('A1:' . $highestCol . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Rata kanan untuk kolom angka (Qty, Harga, Diskon, Total, Margin)
        $sheet->getStyle('D2:D' . $highestRow)->getAlignment()->setHorizontal('right'); // Harga Jasa
        $sheet->getStyle('F2:F' . $highestRow)->getAlignment()->setHorizontal('right'); // Qty Part
        $sheet->getStyle('G2:G' . $highestRow)->getAlignment()->setHorizontal('right'); // Harga Part
        $sheet->getStyle('H2:H' . $highestRow)->getAlignment()->setHorizontal('right'); // Diskon
        $sheet->getStyle('I2:I' . $highestRow)->getAlignment()->setHorizontal('right'); // Total

        // Baris terakhir (Total keseluruhan) dibuat bold
        $sheet->getStyle('A' . $highestRow . ':' . $highestCol . $highestRow)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        return [];
    }


    public function title(): string
    {
        return 'Pemasukan';
    }
}
