<?php

namespace App\Services;

use App\Models\Product;
use App\Models\OrderItem;
use App\Models\SalesItem;
use Illuminate\Support\Facades\DB;


class MovementService
{
    public function getMovements($filters = [])
    {
        $orderItems = OrderItem::with([
            'product',
            'order.customerVehicle.customer',
            'order.customerVehicle.vehicle'
        ])
            ->select(
                'id',
                'product_id',
                'quantity',
                'unit_price',
                'total_price',
                DB::raw("'services' as type"),
                DB::raw("order_id as reference_id"),
                DB::raw("'Job Order' as reference_type"),
                DB::raw("(SELECT job_orders.service_at FROM job_orders WHERE job_orders.id = order_items.order_id) as movement_date"),
                DB::raw("(SELECT job_orders.unique_id FROM job_orders WHERE job_orders.id = order_items.order_id) as reference_number"),
                DB::raw("(SELECT CONCAT(customers.name, ' - ', vehicles.merk, ' ', vehicles.tipe, ' (', vehicles.no_pol, ')') 
                    FROM job_orders 
                    JOIN customer_vehicle ON job_orders.customer_vehicle_id = customer_vehicle.id
                    JOIN customers ON customer_vehicle.customer_id = customers.id
                    JOIN vehicles ON customer_vehicle.vehicle_id = vehicles.id
                    WHERE job_orders.id = order_items.order_id) as customer_info")
            );

        $salesItems = SalesItem::with([
            'product',
            'sales.customer'
        ])
            ->select(
                'id',
                'product_id',
                'quantity',
                'unit_price',
                'total_price',
                DB::raw("'sales' as type"),
                DB::raw("sales_id as reference_id"),
                DB::raw("'Penjualan' as reference_type"),
                DB::raw("(SELECT sales.sales_date FROM sales WHERE sales.id = sales_items.sales_id) as movement_date"),
                DB::raw("(SELECT sales.unique_id FROM sales WHERE sales.id = sales_items.sales_id) as reference_number"),
                DB::raw("(SELECT CONCAT(customers.name, ' - ', customers.phone) 
                    FROM sales 
                    JOIN customers ON sales.customer_id = customers.id
                    WHERE sales.id = sales_items.sales_id) as customer_info")
            );

        // Apply filters
        if (!empty($filters['start_date'])) {
            $orderItems->whereHas('order', function ($q) use ($filters) {
                $q->where('service_at', '>=', $filters['start_date']);
            });
            $salesItems->whereHas('sales', function ($q) use ($filters) {
                $q->where('sales_date', '>=', $filters['start_date']);
            });
        }

        if (!empty($filters['end_date'])) {
            $orderItems->whereHas('order', function ($q) use ($filters) {
                $q->where('service_at', '<=', $filters['end_date']);
            });
            $salesItems->whereHas('sales', function ($q) use ($filters) {
                $q->where('sales_date', '<=', $filters['end_date']);
            });
        }

        if (!empty($filters['product_id'])) {
            $orderItems->where('product_id', $filters['product_id']);
            $salesItems->where('product_id', $filters['product_id']);
        }

        if (!empty($filters['type']) && in_array($filters['type'], ['services', 'sales'])) {
            if ($filters['type'] === 'services') {
                $salesItems->whereRaw('1 = 0'); // Force empty result
            } else {
                $orderItems->whereRaw('1 = 0'); // Force empty result
            }
        }

        // Combine results
        $movements = $orderItems->unionAll($salesItems)
            ->orderBy('movement_date', 'desc')
            ->paginate(25)
            ->withQueryString();

        return $movements;
    }

    public function getMovementReport($filters = [])
    {
        $orderItems = OrderItem::join('job_orders', 'order_items.order_id', '=', 'job_orders.id')
            ->select(
                'order_items.product_id',
                DB::raw("'out' as movement_type"),
                DB::raw('SUM(order_items.quantity) as quantity'),
                DB::raw('SUM(order_items.total_price) as total_value'),
                DB::raw('COUNT(order_items.id) as transaction_count')
            )
            ->groupBy('order_items.product_id');

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
        if (!empty($filters['start_date'])) {
            $orderItems->where('job_orders.service_at', '>=', $filters['start_date']);
            $salesItems->where('sales.sales_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $orderItems->where('job_orders.service_at', '<=', $filters['end_date']);
            $salesItems->where('sales.sales_date', '<=', $filters['end_date']);
        }

        // Get product movements
        $productMovements = $orderItems->unionAll($salesItems)
            ->get()
            ->groupBy('product_id')
            ->map(function ($items) {
                $totalQuantity = $items->sum('quantity');
                $totalValue = $items->sum('total_value');
                $transactionCount = $items->sum('transaction_count');

                return [
                    'total_quantity' => $totalQuantity,
                    'total_value' => $totalValue,
                    'transaction_count' => $transactionCount
                ];
            });

        // Get products with their movements
        $products = Product::with(['category'])
            ->get()
            ->map(function ($product) use ($productMovements) {
                $movement = $productMovements->get($product->id, [
                    'total_quantity' => 0,
                    'total_value' => 0,
                    'transaction_count' => 0
                ]);

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category->name ?? 'Tidak ada kategori',
                    'stock' => $product->stok,
                    'min_stock' => $product->min_stock,
                    'total_quantity' => $movement['total_quantity'],
                    'total_value' => $movement['total_value'],
                    'transaction_count' => $movement['transaction_count']
                ];
            });

        return $products;
    }
}
