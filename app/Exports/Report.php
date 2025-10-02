<?php

namespace App\Exports;

use App\Exports\IncomeSheet;
use App\Exports\ExpensesSheet;
use App\Exports\PurchaseSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Report implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $jobOrderIncome;
    protected $salesIncome;
    protected $purchase;
    protected $expenses;

    public function __construct($jobOrderIncome, $salesIncome, $purchase, $expenses)
    {
        $this->jobOrderIncome = $jobOrderIncome;
        $this->salesIncome = $salesIncome;
        $this->purchase = $purchase;
        $this->expenses = $expenses;
    }

    public function sheets(): array
    {
        return [
            'Pemasukan'      => new IncomeSheet($this->jobOrderIncome, $this->salesIncome),
            'Pembelian Part' => new PurchaseSheet($this->purchase),
            'Pengeluaran'    => new ExpensesSheet($this->expenses),
        ];
    }
}
