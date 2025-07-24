<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\Product;
use App\Models\JobOrder;
use App\Models\Supplier;
use App\Models\OrderItem;
use App\Models\SupplyItem;
use App\Models\MovementItem;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SupplyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $supplies = Supply::with(['jobOrder.customerVehicle.customer', 'jobOrder.customerVehicle.vehicle', 'creator'])
                ->select('supplies.*');

            return DataTables::of($supplies)
                ->addIndexColumn()
                ->addColumn('job_order', function ($supply) {
                    return '#' . $supply->jobOrder->unique_id . '<br>' .
                        '<span class="text-gray-400">' . $supply->jobOrder->customerVehicle->vehicle->merk . ' ' . $supply->jobOrder->customerVehicle->vehicle->tipe . '</span>';
                })
                ->addColumn('count_part', function ($supply) {
                    return '<span class="text-gray-400">' . $supply->items()->count() . '</span>';
                })
                ->addColumn('created_at', function ($supply) {
                    return '<span class="text-gray-400">' . $supply->created_at->format('d-m-Y') . '</span>';
                })
                ->addColumn('created_by', function ($supply) {
                    return '<span class="text-gray-400">' . $supply->creator->name . '</span>';
                })
                ->addColumn('status_badge', function ($supply) {
                    $statusClasses = [
                        'pending' => 'bg-yellow-500 text-white',
                        'processed' => 'bg-blue-500 text-white',
                        'completed' => 'bg-green-500 text-white',
                        'cancelled' => 'bg-red-500 text-white',
                    ];
                    return '<span class="px-2 py-1 rounded-full text-xs ' . $statusClasses[$supply->status] . '">' .
                        ucfirst($supply->status) .
                        '</span>';
                })
                ->addColumn('action', function ($supply) {
                    $btn = '<div class="flex justify-end space-x-2">';
                    $btn .= '<a href="' . route('supplies.show', $supply->id) . '" class="text-blue-400 hover:text-blue-300 p-1" title="Detail">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                    $btn .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>';
                    $btn .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
                    $btn .= '</svg></a>';

                    if ($supply->status == 'pending' || $supply->status == 'processed') {
                        $btn .= '<a href="' . route('supplies.fulfill', $supply->id) . '" class="text-green-400 hover:text-green-300 p-1" title="Penuhi">';
                        $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                        $btn .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
                        $btn .= '</svg></a>';
                    }

                    $btn .= '<a href="' . route('supplies.destroy', $supply->id) . '" class="text-red-400 hover:text-red-300 p-1 delete-btn" title="Hapus">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                    $btn .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>';
                    $btn .= '</svg></a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['job_order', 'count_part', 'created_at', 'created_by', 'status_badge', 'action'])
                ->make(true);
        }

        return view('supplies.index');
    }

    public function createFromJobOrder($jobOrderId)
    {
        $jobOrder = JobOrder::with(['customerVehicle.customer', 'customerVehicle.vehicle', 'sparepart', 'sparepart.product'])
            ->findOrFail($jobOrderId);

        $suppliers = Supplier::all();

        return view('supplies.create', compact('jobOrder', 'suppliers'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'job_order_id' => 'required|exists:job_orders,id',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:order_items,id',
            'items.*.quantity_requested' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $supply = Supply::create([
            'job_order_id' => $validated['job_order_id'],
            'notes' => $validated['notes'],
            'created_by' => auth()->id(),
            'status' => 'pending',
        ]);

        foreach ($validated['items'] as $item) {
            SupplyItem::create([
                'supply_id' => $supply->id,
                'product_id' => OrderItem::find($item['item_id'])->product_id,
                'item_id' => $item['item_id'],
                'quantity_requested' => $item['quantity_requested'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity_requested'] * $item['unit_price'],
                'status' => 'pending',
            ]);
        }

        return redirect()->route('supplies.show', $supply->id)
            ->with('success', 'Permintaan supply berhasil dibuat');
    }

    public function show(Supply $supply)
    {
        $supply->load([
            'jobOrder.customerVehicle.customer',
            'jobOrder.customerVehicle.vehicle',
            'items.product',
            'items.orderItem',
            'creator'
        ]);

        return view('supplies.show', compact('supply'));
    }

    public function fulfillForm(Supply $supply)
    {
        $supply->load(['items.product']);
        $products = Product::whereIn('id', $supply->items->pluck('product_id'))->get();

        return view('supplies.fulfill', compact('supply', 'products'));
    }

    public function fulfill(Request $request, Supply $supply)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:supply_items,id,supply_id,' . $supply->id,
            'items.*.quantity_fulfilled' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $supply->update([
                'status' => 'processed',
            ]);

            $allFulfilled = true;

            foreach ($validated['items'] as $itemData) {
                $item = SupplyItem::find($itemData['id']);
                $quantityFulfilled = $itemData['quantity_fulfilled'];

                $newFulfilled = $item->quantity_fulfilled + $quantityFulfilled;
                $status = 'partial';

                if ($newFulfilled >= $item->quantity_requested) {
                    $status = 'fulfilled';
                } else {
                    $allFulfilled = false;
                }

                $item->update([
                    'quantity_fulfilled' => $newFulfilled,
                    'status' => $status,
                ]);

                // Update product stock
                if ($quantityFulfilled > 0) {
                    $product = $item->product;
                    $product->stok -= $quantityFulfilled;
                    $product->save();
                }
            }

            if ($allFulfilled) {
                $supply->update(['status' => 'completed']);
            }
            DB::commit();
            return redirect()->route('supplies.show', $supply->id)
                ->with('success', 'Pemenuhan supply berhasil diperbarui');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function destroy(Supply $supply): JsonResponse
    {
        try {
            // Hapus supply items terlebih dahulu
            $supply->items()->delete();
            $supply->delete();

            return response()->json([
                'success' => true,
                'message' => 'Permintaan supply berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permintaan supply'
            ], 500);
        }
    }

    // app/Http/Controllers/SupplyController.php
    public function selectJobOrder(Request $request)
    {

        if ($request->ajax()) {
            $data = JobOrder::with(['customerVehicle.customer', 'customerVehicle.vehicle'])
                ->select('*');


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_name', function ($row) {
                    return $row->customerVehicle->customer->name;
                })
                ->addColumn('vehicle', function ($row) {
                    return $row->customerVehicle->vehicle->merk . ' - ' . $row->customerVehicle->vehicle->no_pol;
                })
                ->addColumn('formatted_total', function ($row) {
                    return 'Rp ' . number_format($row->total, 0, ',', '.');
                })
                ->addColumn('service_at', function ($row) {
                    return $row->service_at->format('d M Y H:i');
                })
                ->addColumn('status_badge', function ($row) {
                    $statusClass = [
                        'estimation' => 'bg-yellow-100 text-yellow-800',
                        'draft' => 'bg-gray-100 text-gray-800',
                        'progress' => 'bg-blue-100 text-blue-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800'
                    ];
                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full ' . $statusClass[$row->status] . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('action', function ($jobOrder) {
                    return '<a href="' . route('supplies.create-from-job', $jobOrder->id) . '" class="text-blue-500 hover:text-blue-400">Pilih</a>';
                })
                ->orderColumn('service_at', 'service_at $1')
                ->orderColumn('formatted_total', 'total $1')
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('supplies.select-job-order');
    }

    public function createFromSelected(JobOrder $jobOrder)
    {
        $suppliers = Supplier::all();
        return view('supplies.create-from-selected', compact('jobOrder', 'suppliers'));
    }
}
