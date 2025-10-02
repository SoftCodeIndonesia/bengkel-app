<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Exports\Report;
use App\Models\Expense;
use App\Models\JobOrder;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function profitLoss(Request $request)
    {

        if ($request->has('export')) {
            return $this->previewExportExcel($request);
        }
        // Default periode bulan berjalan
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Hitung pendapatan
        $jobOrderIncome = JobOrder::where('status', 'completed')
            ->whereDate('service_at', '>=', $startDate)
            ->whereDate('service_at', '<=', $endDate)
            ->sum('total');



        $salesIncome = Sales::whereDate('sales_date', '>=', $startDate)
            ->whereDate('sales_date', '<=', $endDate)
            ->sum('total');

        $totalIncome = $jobOrderIncome + $salesIncome;

        // Hitung pengeluaran
        $purchaseExpenses = Purchase::where('status', 'paid')->whereBetween('purchase_date', [$startDate, $endDate])->whereNull('deleted_at')
            ->sum('total');

        $operationalExpenses = Expense::whereBetween('date', [$startDate, $endDate])->whereNull('deleted_at')
            ->sum('amount');


        $totalExpenses = $purchaseExpenses + $operationalExpenses;

        // Hitung laba rugi
        $profitLoss = $totalIncome - $totalExpenses;

        $chartStartDate = Carbon::now()->subDays(30);
        $chartEndDate = Carbon::now();

        $chartLabels = [];
        $incomeData = [];
        $expenseData = [];
        $profitData = [];

        for ($date = $chartStartDate; $date <= $chartEndDate; $date->addDay()) {
            $chartLabels[] = $date->format('d M');

            $dailyJobOrder = JobOrder::where('status', 'completed')
                ->whereDate('service_at', $date)
                ->sum('total');

            $dailySales = Sales::whereDate('sales_date', $date)
                ->sum('total');

            $dailyIncome = $dailyJobOrder + $dailySales;
            $incomeData[] = $dailyIncome;

            $dailyPurchases = Purchase::whereDate('purchase_date', $date)
                ->sum('total');

            $dailyExpenses = Expense::whereDate('date', $date)
                ->sum('amount');

            $dailyExpense = $dailyPurchases + $dailyExpenses;
            $expenseData[] = $dailyExpense;

            $profitData[] = $dailyIncome - $dailyExpense;
        }

        return view('report.profit-loss', compact(
            'startDate',
            'endDate',
            'jobOrderIncome',
            'salesIncome',
            'totalIncome',
            'purchaseExpenses',
            'operationalExpenses',
            'totalExpenses',
            'profitLoss',
            'chartLabels',
            'incomeData',
            'expenseData',
            'profitData'
        ));
    }

    public function previewExportExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $jobOrderIncome = JobOrder::with('service', 'sparepart')->where('status', 'completed')
            ->whereBetween('service_at', [$startDate, $endDate])->get();

        $salesIncome = Sales::whereBetween('sales_date', [$startDate, $endDate])->get();
        return view('report.preview-excel', compact('jobOrderIncome', 'salesIncome'));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $jobOrderIncome = JobOrder::with('service.product', 'sparepart.product')->where('status', 'completed');

        if ($startDate) {
            $jobOrderIncome->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('service_at', '>=', $startDate);
            });
        }
        if ($endDate) {
            $jobOrderIncome->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('service_at', '<=', $endDate);
            });
        }

        $jobOrderIncome = $jobOrderIncome->get();


        $salesIncome = Sales::with('items', 'items.product');

        if ($startDate) {
            $salesIncome->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('sales_date', '>=', $startDate);
            });
        }
        if ($endDate) {
            $salesIncome->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('sales_date', '<=', $endDate);
            });
        }

        $salesIncome = $salesIncome->get();

        $purchaseExpenses = Purchase::with('items', 'items.product')->where('status', 'paid')->whereBetween('purchase_date', [$startDate, $endDate])->whereNull('deleted_at')->get();

        $operationalExpenses = Expense::whereBetween('date', [$startDate, $endDate])->whereNull('deleted_at')->get();

        return Excel::download(new Report($jobOrderIncome, $salesIncome, $purchaseExpenses, $operationalExpenses), 'laporan-' . $startDate . '-' . $endDate . '.xlsx');
    }
}
