<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\JobOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::today();

        // Job Order Stats
        $todayJobOrders = JobOrder::whereDate('service_at', $today)->count();
        $todayJobOrderIncome = Invoice::where('tipe', 'services')
            ->whereDate('created_at', $today)
            ->sum('total');

        // Sales Stats
        $todaySales = Sales::whereDate('sales_date', $today)->count();
        $todaySalesIncome = Invoice::where('tipe', 'sales')
            ->whereDate('created_at', $today)
            ->sum('total');

        // Fast Moving Products (non-jasa)
        $fastMovingProducts = Product::where('tipe', '!=', 'jasa')
            ->withCount(['orderItems as total_sold' => function ($query) {
                $query->whereHas('jobOrder', function ($q) {
                    $q->where('status', 'completed')
                        ->whereMonth('service_at', now()->month);
                });
            }])
            ->withSum([
                'orderItems as total_revenue' => function ($query) {
                    $query->whereHas('jobOrder', function ($q) {
                        $q->where('status', 'completed')
                            ->whereMonth('service_at', now()->month);
                    });
                }
            ], DB::raw('quantity * unit_price'))
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Slow Moving Products (non-jasa)
        $slowMovingProducts = Product::where('tipe', 'barang')
            ->withCount([
                'orderItems as total_sold' => function ($query) {
                    $query->whereHas('jobOrder', function ($q) {
                        $q->where('status', 'completed')
                            ->whereMonth('service_at', now()->month);
                    });
                }
            ])
            ->orderBy('total_sold')
            ->take(5)
            ->get();
        return view('dashboard', compact(
            'todayJobOrders',
            'todayJobOrderIncome',
            'todaySales',
            'todaySalesIncome',
            'fastMovingProducts',
            'slowMovingProducts'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
