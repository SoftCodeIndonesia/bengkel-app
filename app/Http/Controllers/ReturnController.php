<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\Product;
use App\Models\ReturnItem;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $returns = ReturnItem::with(['supply', 'product'])
                ->select('return_items.*');

            return DataTables::of($returns)
                ->addIndexColumn()
                ->addColumn('supply_id', function ($return) {
                    return $return->supply_id;
                })
                ->addColumn('product_name', function ($return) {
                    return $return->product->name;
                })
                ->addColumn('status', function ($return) {
                    return $return->status;
                })
                ->addColumn('action', function ($return) {
                    return '
                    <div class="flex justify-end space-x-2">
                        <a href="' . route('returns.show', $return->id) . '" class="text-blue-400 hover:text-blue-300 p-1" title="Detail">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="' . route('returns.edit', $return->id) . '" class="text-green-600 hover:text-green-300 p-1" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <button type="button" data-id="' . $return->id . '" class="text-red-400 hover:text-red-300 p-1 delete-retur" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('retur.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('retur.create', [
            'supplies' => Supply::with(['jobOrder.customerVehicle.customer', 'jobOrder.customerVehicle.vehicle'])
                ->whereIn('status', ['processed', 'completed'])
                ->get(),
            'products' => Product::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supply_id' => 'required|exists:supplies,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.order_item_id' => 'nullable|exists:order_items,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.reason' => 'required|string|max:255'
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['items'] as $item) {
                ReturnItem::create([
                    'supply_id' => $validated['supply_id'],
                    'product_id' => $item['product_id'],
                    'order_item_id' => $item['order_item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'reason' => $item['reason'],
                    'status' => 'pending'
                ]);

                // Update stok produk
                $product = Product::find($item['product_id']);
                $product->stok += $item['quantity'];
                $product->save();
            }
        });

        return redirect()->route('returns.index')->with('success', 'Retur berhasil diajukan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $returnItem = ReturnItem::with('supply', 'product', 'orderItem', 'processor')->find($id);
        return view('retur.show', compact('returnItem'));
    }

    public function updateStatus(Request $request, ReturnItem $returnItem)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string'
        ]);

        $returnItem->update([
            'status' => $request->status,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'reason' => $request->notes ? $returnItem->reason . "\nCatatan: " . $request->notes : $returnItem->reason
        ]);

        return back()->with('success', 'Status retur berhasil diperbarui');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $retur = ReturnItem::with('supply', 'product')->find($id);
        $supplies = Supply::with(['jobOrder.customerVehicle.customer', 'jobOrder.customerVehicle.vehicle'])
            ->whereIn('status', ['processed', 'completed'])
            ->get();
        return view('retur.edit', compact('retur', 'supplies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        // dd($request->all());
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'reason' => 'nullable|string|max:255'
        ]);

        DB::transaction(function () use ($validated, $id) {
            $retur = ReturnItem::find($id);
            $retur->reason = $validated['reason'];

            $quantityDiff = $validated['quantity'] - $retur->quantity;

            $retur->quantity = $validated['quantity'];


            if ($retur->status == 'approved') {
                // Update stok produk
                $product = Product::find($validated['product_id']);
                $product->stok += $quantityDiff;

                $product->save();
            }

            $retur->save();
        });

        return redirect()->route('returns.index')->with('success', 'Retur berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $retur = ReturnItem::find($id);

        if ($retur) {
            $retur->delete();
            return redirect()->route('returns.index')->with('success', 'Retur berhasil dihapus!');
        } else {
            return redirect()->route('returns.index')->with('error', 'Data Retur Tidak Di Temukan!');
        }
    }
}
