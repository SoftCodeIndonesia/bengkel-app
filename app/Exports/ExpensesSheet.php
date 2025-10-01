<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExpensesSheet implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect(); // kosong dulu
    }

    public function headings(): array
    {
        return [
            'Nomor Dokumen',
            'Deskripsi',
            'Tanggal',
            'Nominal',
        ];
    }
}
