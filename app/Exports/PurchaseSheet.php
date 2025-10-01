<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class PurchaseSheet implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect();
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
}
