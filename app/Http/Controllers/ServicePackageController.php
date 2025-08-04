<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ServicePackage;
use Yajra\DataTables\DataTables;
use App\Models\ServicePackageItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ServicePackageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $packages = ServicePackage::withCount('items')->get();

            return DataTables::of($packages)
                ->addIndexColumn()
                ->addColumn('subtotal', function ($package) {
                    return 'Rp ' . number_format($package->subtotal, 0, ',', '.');
                })
                ->addColumn('total_discount_formatted', function ($package) {
                    return 'Rp ' . number_format($package->total_discount, 0, ',', '.');
                })
                ->addColumn('total', function ($package) {
                    return 'Rp ' . number_format($package->total, 0, ',', '.');
                })
                ->addColumn('action', function ($package) {
                    $actionBtn = '
                    <div class="flex justify-end space-x-2">
                        <a href="' . route('service-packages.show', $package->id) . '" class="text-blue-500 hover:text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="' . route('service-packages.edit', $package->id) . '" class="text-yellow-500 hover:text-yellow-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <button type="button" data-id="' . $package->id . '" data-name="' . $package->name . '" class="delete-package text-red-500 hover:text-red-600" >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                        </button>
                    </div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('package.index');
    }

    public function create()
    {
        $products = Product::all();
        return view('package.create', compact('products'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.diskon_value' => 'required|numeric|min:0',
        ]);


        $package = ServicePackage::create([
            'name' => $request->name,
            'description' => $request->description,
            'total_discount' => $request->total_diskon_item,
            'discount_unit' => 'nominal',
            'subtotal' => $request->total_sparepart + $request->total_jasa,
            'total' => $request->total,
        ]);

        foreach ($request->items as $item) {
            $data_item = json_decode($item['product_id']);
            $product = Product::find($data_item->id);

            if ($product->tipe == 'jasa') {
                $subtotaljasa = 100000 * $item['quantity'];
                $potongan = ($item['diskon_value'] / 100) * $subtotaljasa;
                $data_input = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'discount' => $item['diskon_value'],
                    'discount_unit' => 'percentage',
                    'subtotal' => $subtotaljasa,
                    'total' => $subtotaljasa - $potongan,
                ];
            } else {
                $subtotal = $product->unit_price * $item['quantity'];
                $potongan = ($item['diskon_value'] / 100) * $subtotal;
                $data_input = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'discount' => $item['diskon_value'],
                    'discount_unit' => 'percentage',
                    'subtotal' => $subtotal,
                    'total' => $subtotal - $potongan,
                ];
            }


            $package->items()->create($data_input);
        }

        return redirect()->route('service-packages.index')
            ->with('success', 'Paket service berhasil dibuat');
    }

    public function show(ServicePackage $servicePackage)
    {
        return view('package.show', compact('servicePackage'));
    }

    public function edit(ServicePackage $servicePackage)
    {
        $products = Product::all();
        $servicePackage->load(['items', 'items.product']);
        return view('package.edit', compact('servicePackage', 'products'));
    }

    public function update(Request $request, ServicePackage $servicePackage)
    {

        // dd($request->all());
        DB::transaction(function () use ($request, $servicePackage) {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'items' => 'required|array',
                'items.*.product_id' => 'required',
                'items.*.quantity' => 'required|numeric|min:0.01',
                'items.*.diskon_value' => 'required|numeric|min:0',
            ]);



            $servicePackage->update([
                'name' => $request->name,
                'description' => $request->description,
                'total_discount' => $request->total_diskon_item,
                'discount_unit' => 'nominal',
                'subtotal' => $request->total_sparepart + $request->total_jasa,
                'total' => $request->total,
            ]);

            foreach ($request->items as $item) {
                if (str_starts_with($item['id'] ?? '', 'delete_')) {
                    // Delete marked items
                    $id = str_replace('delete_', '', $item['id']);
                    ServicePackageItem::find($id)->delete();
                } elseif (empty($item['id'])) {
                    $data_item = json_decode($item['product_id']);
                    $product = Product::find($data_item->id);

                    $subtotal = $product->unit_price * $item['quantity'];
                    $potongan = ($item['diskon_value'] / 100) * $subtotal;


                    if ($product->tipe == 'jasa') {
                        $subtotaljasa = 100000 * $item['quantity'];
                        $data_input = [
                            'product_id' => $product->id,
                            'quantity' => $item['quantity'],
                            'discount' => $item['diskon_value'],
                            'discount_unit' => 'percentage',
                            'subtotal' => $subtotaljasa,
                            'total' => $subtotaljasa - $potongan,
                        ];
                    } else {
                        $data_input = [
                            'product_id' => $product->id,
                            'quantity' => $item['quantity'],
                            'discount' => $item['diskon_value'],
                            'discount_unit' => 'percentage',
                            'subtotal' => $subtotal,
                            'total' => $subtotal - $potongan,
                        ];
                    }

                    // dd($data_input);
                    $servicePackage->items()->create($data_input);
                } else {
                    $orderItem = ServicePackageItem::find($item['id']);

                    if ($orderItem->product->tipe == 'jasa') {
                        $subtotal = 100000 * $item['quantity'];
                    } else {
                        $subtotal = $orderItem->product->unit_price * $item['quantity'];
                    }

                    $potongan = ($item['diskon_value'] / 100) * $subtotal;

                    $orderItem->quantity = $item['quantity'];
                    $orderItem->subtotal = $subtotal;
                    $orderItem->discount = $item['diskon_value'];
                    $orderItem->total = $subtotal - $potongan;

                    $orderItem->save();
                }
            }
        });


        return redirect()->route('service-packages.index')
            ->with('success', 'Paket service berhasil diperbarui');
    }

    public function destroy(ServicePackage $servicePackage)
    {
        $servicePackage->delete();
        return redirect()->route('service-packages.index')
            ->with('success', 'Paket service berhasil dihapus');
    }
}
