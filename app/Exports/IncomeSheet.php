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
            $jasaList = $wo->service;
            $partList = $wo->sparepart;

            $maxCount = max(count($jasaList), count($partList));

            for ($i = 0; $i < $maxCount; $i++) {
                $jasa = $jasaList[$i] ?? null;
                $part = $partList[$i] ?? null;

                $marginNominal = 0;
                $margin = 0;

                if ($part) {
                    $marginNominal = $part->price_after_diskon - ($part->product->buying_price * $part->quantity);
                    $margin = $part->price_after_diskon > 0
                        ? ($marginNominal / $part->price_after_diskon)
                        : 0;

                    $totalQtyPart += $part->quantity;
                    $totalHargaPart += $part->unit_price * $part->quantity;
                    $totalHargaBeliPart += $part->product->buying_price * $part->quantity;
                    $totalPart += $part->price_after_diskon;
                    $totalMarginNominal += $marginNominal;
                }

                if ($jasa) {
                    $totalJasa += $jasa->total_price;
                }

                $rows[] = [
                    $i == 0 ? 'WO' : '', // sumber hanya di baris pertama
                    $i == 0 ? $wo->unique_id : '', // nomor WO hanya di baris pertama
                    $i == 0 ? $wo->service_at->format('d-m-Y') : '',
                    $jasa ? $jasa->product->name : '',
                    $jasa ? $jasa->quantity : '',
                    $jasa ? (100000 * $jasa->quantity * ($jasa->diskon_value / 100)) : '',
                    $jasa ? $jasa->price_after_diskon : '',
                    $part ? $part->product->name : '',
                    $part ? $part->quantity : '',
                    $part ? $part->product->buying_price : '',
                    $part ? $part->unit_price : '',
                    $part ? $part->price_after_diskon : '',
                    $part ? round($margin, 2) : '',
                    $part ? $marginNominal : '',
                ];
            }
        }

        // Sales Orders
        foreach ($this->salesIncome as $key => $so) {
            foreach ($so->items as $keyPart => $part) {
                $marginNominal = $part->price_after_discount - ($part->product->buying_price * $part->quantity);
                $margin = $part->price_after_discount > 0
                    ? ($marginNominal / $part->price_after_discount)
                    : 0;

                $rows[] = [
                    $keyPart == 0 ? 'SO' : '',
                    $keyPart == 0 ? $so->unique_id : '',
                    $so->created_at->format('d-m-Y'),
                    null,
                    null,
                    null,
                    null,
                    $part->product->name,
                    $part->quantity,
                    $part->product->buying_price,
                    $part->unit_price,
                    $part->price_after_discount,
                    round($margin, 2),
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
            '',
            '',
            '',
            '=SUM(G2:G' . (count($rows) + 1) . ')', // total jasa
            '',
            '=SUM(I2:I' . (count($rows) + 1) . ')', // total qty
            '=SUM(J2:J' . (count($rows) + 1) . ')', // total harga beli
            '=SUM(K2:K' . (count($rows) + 1) . ')', // total harga jual
            '=SUM(L2:L' . (count($rows) + 1) . ')', // total part
            '=AVERAGE(M2:M' . (count($rows)) . ')', // rata2 margin %
            '=SUM(N2:N' . (count($rows) + 1) . ')', // total margin nominal
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

        $sheet->getStyle('E2:E' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');        // FRT
        $sheet->getStyle('F2:F' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');        // Diskon Jasa
        $sheet->getStyle('G2:G' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');        // Total Jasa
        $sheet->getStyle('I2:I' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');        // Qty
        $sheet->getStyle('J2:J' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');        // Harga Beli
        $sheet->getStyle('K2:K' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');        // Harga Jual
        $sheet->getStyle('L2:L' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');        // Total Setelah Diskon
        $sheet->getStyle('M2:M' . $highestRow)->getNumberFormat()->setFormatCode('0.00%');        // Margin % → otomatis jadi persen
        $sheet->getStyle('N2:N' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');        // Margin Rp


        // Rata kanan untuk kolom angka (Qty, Harga, Diskon, Total, Margin)
        $sheet->getStyle('E2:E' . $highestRow)->getAlignment()->setHorizontal('right'); // FRT
        $sheet->getStyle('F2:F' . $highestRow)->getAlignment()->setHorizontal('right'); // Diskon Jasa
        $sheet->getStyle('G2:G' . $highestRow)->getAlignment()->setHorizontal('right'); // Total Jasa
        $sheet->getStyle('I2:I' . $highestRow)->getAlignment()->setHorizontal('right'); // Qty
        $sheet->getStyle('J2:J' . $highestRow)->getAlignment()->setHorizontal('right'); // Harga Beli
        $sheet->getStyle('K2:K' . $highestRow)->getAlignment()->setHorizontal('right'); // Harga Jual
        $sheet->getStyle('L2:L' . $highestRow)->getAlignment()->setHorizontal('right'); // Total Setelah Diskon
        $sheet->getStyle('M2:M' . $highestRow)->getAlignment()->setHorizontal('right'); // Margin %
        $sheet->getStyle('N2:N' . $highestRow)->getAlignment()->setHorizontal('right'); // Margin Rp


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
