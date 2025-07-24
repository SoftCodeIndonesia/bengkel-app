<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Expense;
use App\Models\JobOrder;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function profitLoss(Request $request)
    {
        // Default periode bulan berjalan
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Hitung pendapatan
        $jobOrderIncome = JobOrder::where('status', 'completed')
            ->whereBetween('service_at', [$startDate, $endDate])
            ->sum('total');

        $salesIncome = Sales::whereBetween('sales_date', [$startDate, $endDate])
            ->sum('total');

        $totalIncome = $jobOrderIncome + $salesIncome;

        // Hitung pengeluaran
        $purchaseExpenses = Purchase::where('status', 'paid')->whereBetween('purchase_date', [$startDate, $endDate])
            ->sum('total');

        $operationalExpenses = Expense::whereBetween('date', [$startDate, $endDate])
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
}
