<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderItem;
use App\Models\SalesItem;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class MovementController extends Controller
{



    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getMovementsData($request);
        }

        $summaryData = [
            'total_sales' => 0,
            'total_services' => 0,
            'grand_total' => 0
        ];
        return view('movements.index', compact('summaryData'));
    }

    private function getMovementsData($request)
    {
        // Query untuk order_items (services)
        $orderItemsQuery = OrderItem::with(['product'])
            ->join('job_orders', 'order_items.order_id', '=', 'job_orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('customer_vehicle', 'job_orders.customer_vehicle_id', '=', 'customer_vehicle.id')
            ->join('customers', 'customer_vehicle.customer_id', '=', 'customers.id')
            ->join('vehicles', 'customer_vehicle.vehicle_id', '=', 'vehicles.id')
            ->select(
                'order_items.id',
                'order_items.product_id',
                'order_items.quantity',
                'order_items.unit_price',
                'order_items.total_price',
                DB::raw("'services' as type"),
                'order_items.order_id as reference_id',
                DB::raw("'Job Order' as reference_type"),
                'job_orders.service_at as movement_date',
                'job_orders.unique_id as reference_number',
                'job_orders.id as reference_id',
                DB::raw("CONCAT(customers.name) as customer_info")
            )
            ->where('products.tipe', '!=', 'jasa')
            ->where('job_orders.status', 'completed')
            ->whereNull('job_orders.deleted_at');

        // Query untuk sales_items (sales)
        $salesItemsQuery = SalesItem::with(['product'])
            ->join('sales', 'sales_items.sales_id', '=', 'sales.id')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->select(
                'sales_items.id',
                'sales_items.product_id',
                'sales_items.quantity',
                'sales_items.unit_price',
                'sales_items.total_price',
                DB::raw("'sales' as type"),
                'sales_items.sales_id as reference_id',
                DB::raw("'Penjualan' as reference_type"),
                'sales.sales_date as movement_date',
                'sales.unique_id as reference_number',
                'sales.id as reference_id',
                DB::raw("CONCAT(customers.name) as customer_info")
            )
            ->whereNull('sales.deleted_at');

        // Apply filters
        if ($request->has('start_date') && $request->start_date != '') {
            $orderItemsQuery->where('job_orders.service_at', '>=', $request->start_date);
            $salesItemsQuery->where('sales.sales_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $orderItemsQuery->where('job_orders.service_at', '<=', $request->end_date);
            $salesItemsQuery->where('sales.sales_date', '<=', $request->end_date);
        }

        if ($request->has('product_ids') && !empty($request->product_ids)) {
            $productIds = is_array($request->product_ids) ? $request->product_ids : [$request->product_ids];
            $orderItemsQuery->whereIn('order_items.product_id', $productIds);
            $salesItemsQuery->whereIn('sales_items.product_id', $productIds);
        }

        if ($request->has('type') && $request->type != '') {
            if ($request->type === 'services') {
                $salesItemsQuery->whereRaw('1 = 0');
            } elseif ($request->type === 'sales') {
                $orderItemsQuery->whereRaw('1 = 0');
            }
        }

        // Get results from both queries
        $orderItems = $orderItemsQuery->get();
        $salesItems = $salesItemsQuery->get();

        // Combine results
        $movements = $orderItems->concat($salesItems);

        $totalSales = $salesItems->sum('total_price');
        $totalServices = $orderItems->sum('total_price');
        $grandTotal = $totalSales + $totalServices;

        return DataTables::of($movements)
            ->with([
                'total_sales' => $totalSales,
                'total_services' => $totalServices,
                'grand_total' => $grandTotal
            ])
            ->addIndexColumn()
            ->editColumn('movement_date', function ($movement) {
                return \Carbon\Carbon::parse($movement->movement_date)->format('d M Y');
            })
            ->editColumn('product.name', function ($movement) {
                return $movement->product->name ?? 'N/A';
            })
            ->editColumn('product.barcode', function ($movement) {
                return $movement->product->barcode ?? '-';
            })
            ->editColumn('quantity', function ($movement) {
                return number_format($movement->quantity, 2);
            })
            ->editColumn('unit_price', function ($movement) {
                return 'Rp ' . number_format($movement->unit_price, 2);
            })
            ->editColumn('total_price', function ($movement) {
                return 'Rp ' . number_format($movement->total_price, 2);
            })
            ->editColumn('type', function ($movement) {
                $color = $movement->type == 'services' ? 'blue' : 'green';
                return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-' . $color . '-900 text-' . $color . '-300">' . $movement->reference_type . '</span>';
            })
            ->editColumn('reference_code', function ($movement) {
                return $movement->reference_number;
            })
            ->editColumn('reference_number', function ($movement) {
                // Ganti dengan route yang sesuai
                $route = $movement->type == 'services' ? '/job-orders' . '/' . $movement->reference_id : '/sales' . '/' . $movement->reference_id;
                return '<a href="' . $route . '" class="text-blue-400 hover:text-blue-300 underline" target="__blank">' . $movement->reference_number . '</a>';
            })
            ->rawColumns(['type', 'reference_number'])
            ->make(true);
    }

    public function report(Request $request)
    {
        // Query untuk order_items
        $orderItems = OrderItem::join('job_orders', 'order_items.order_id', '=', 'job_orders.id')
            ->select(
                'order_items.product_id',
                DB::raw("'out' as movement_type"),
                DB::raw('SUM(order_items.quantity) as quantity'),
                DB::raw('SUM(order_items.total_price) as total_value'),
                DB::raw('COUNT(order_items.id) as transaction_count')
            )
            ->groupBy('order_items.product_id');

        // Query untuk sales_items
        $salesItems = SalesItem::join('sales', 'sales_items.sales_id', '=', 'sales.id')
            ->select(
                'sales_items.product_id',
                DB::raw("'out' as movement_type"),
                DB::raw('SUM(sales_items.quantity) as quantity'),
                DB::raw('SUM(sales_items.total_price) as total_value'),
                DB::raw('COUNT(sales_items.id) as transaction_count')
            )
            ->groupBy('sales_items.product_id');

        // Apply date filters
        if ($request->filled('start_date')) {
            $orderItems->where('job_orders.service_at', '>=', $request->start_date);
            $salesItems->where('sales.sales_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $orderItems->where('job_orders.service_at', '<=', $request->end_date);
            $salesItems->where('sales.sales_date', '<=', $request->end_date);
        }

        // Get results
        $orderItemsResults = $orderItems->get();
        $salesItemsResults = $salesItems->get();

        // Combine results
        $allMovements = $orderItemsResults->concat($salesItemsResults);

        // Group by product_id and calculate totals
        $productMovements = $allMovements->groupBy('product_id')
            ->map(function ($items) {
                return [
                    'total_quantity' => $items->sum('quantity'),
                    'total_value' => $items->sum('total_value'),
                    'transaction_count' => $items->sum('transaction_count')
                ];
            });

        // Get products with their movements
        $products = Product::all()
            ->map(function ($product) use ($productMovements) {
                $movement = $productMovements->get($product->id, [
                    'total_quantity' => 0,
                    'total_value' => 0,
                    'transaction_count' => 0
                ]);

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock' => $product->stok,
                    'min_stock' => $product->min_stock,
                    'total_quantity' => $movement['total_quantity'],
                    'total_value' => $movement['total_value'],
                    'transaction_count' => $movement['transaction_count']
                ];
            });

        return view('movements.report', compact('products'));
    }
}
