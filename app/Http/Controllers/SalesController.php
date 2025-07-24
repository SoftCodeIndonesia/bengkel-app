<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SalesItem;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SalesController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sales = Sales::with('customer');

            return DataTables::of($sales)
                ->addIndexColumn()
                ->addColumn('action', function ($sale) {
                    return '';
                })
                ->editColumn('sales_date', function ($sale) {
                    return $sale->sales_date->format('d M Y H:i');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('sales.index');
    }


    public function create()
    {
        $customers = Customer::all();
        $products = Product::where('tipe', '!=', 'jasa')->get();

        return view('sales.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {


        // dd($request->all());

        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'customer_id' => 'required_without:customer_name|exists:customers,id',
                'customer_name' => 'required_without:customer_id',
                'customer_phone' => 'required_without:customer_id',
                'sales_date' => 'required|date',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|numeric|min:0.01',
                'items.*.dicount_percentage' => 'required|numeric|min:0.01',
                'subtotal' => 'required|numeric',
                'total' => 'required|numeric',
            ]);





            // Jika ada customer baru
            if ($request->filled('customer_name')) {
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone' => $request->customer_phone,
                    'address' => $request->customer_address,
                ]);
                $validated['customer_id'] = $customer->id;
            }

            $sale = Sales::create([
                'customer_id' => $validated['customer_id'],
                'customer_name' => Customer::find($validated['customer_id'])->name,
                'customer_address' => Customer::find($validated['customer_id'])->address,
                'sales_date' => $validated['sales_date'],
                'subtotal' => $validated['subtotal'],
                'diskon_unit' => $validated['diskon_unit'] ?? null,
                'diskon_value' => $validated['diskon_value'] ?? 0,
                'total' => $validated['total'],
                'diskon_unit' => 'nominal',
                'diskon_value' => $request->total_discount,
            ]);

            foreach ($validated['items'] as $item) {

                $product = Product::find($item['product_id']);
                $total = $product->unit_price * $item['quantity'];
                SalesItem::create([
                    'sales_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->unit_price,
                    'total_price' => $total,
                    'discount_percentage' => $item['dicount_percentage'],
                    'discount_nominal' => $total * ($item['dicount_percentage'] / 100),
                    'price_after_discount' => $total * (1 - ($item['dicount_percentage'] / 100)),
                ]);

                // Update stok jika produk adalah barang
                if ($product->tipe === 'barang') {
                    $product->stok -= $item['quantity'];
                    $product->save();
                }
            }

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat penjualan: ' . $e->getMessage());
        }
    }

    public function show(Sales $sale)
    {
        return view('sales.show', compact('sale'));
    }

    public function edit(Sales $sale)
    {
        $customers = Customer::all();
        $products = Product::where('tipe', '!=', 'jasa')->get();

        return view('sales.edit', compact('sale', 'customers', 'products'));
    }

    public function update(Request $request, Sales $sale)
    {

        // dd($request->all());
        DB::beginTransaction();

        try {
            // Validasi input
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'sales_date' => 'required|date',
                'items' => 'required|array|min:1',
                'items.*.id' => 'nullable', // Bisa null untuk item baru atau 'delete_xxx' untuk yang akan dihapus
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|numeric|min:0.01',
                'items.*.dicount_percentage' => 'required',
                'subtotal' => 'required|numeric',
                'total' => 'required|numeric',
            ]);

            // Update informasi dasar penjualan
            $customer = Customer::find($validated['customer_id']);
            $sale->update([
                'customer_id' => $validated['customer_id'],
                'customer_name' => $customer->name,
                'customer_address' => $customer->address,
                'sales_date' => $validated['sales_date'],
                'diskon_unit' => $validated['diskon_unit'] ?? null,
                'diskon_value' => $validated['diskon_value'] ?? 0,
                'subtotal' => $validated['subtotal'],
                'total' => $validated['total'],
                'diskon_unit' => 'nominal',
                'diskon_value' => $request->total_discount,
            ]);

            // Proses items
            $existingItemIds = [];

            foreach ($validated['items'] as $itemData) {
                // Handle item yang akan dihapus (diawali 'delete_')
                if (isset($itemData['id']) && str_starts_with($itemData['id'], 'delete_')) {
                    $itemId = str_replace('delete_', '', $itemData['id']);
                    $item = SalesItem::where('id', $itemId)
                        ->where('sales_id', $sale->id)
                        ->first();

                    if ($item) {
                        // Kembalikan stok jika produk barang
                        if ($item->product->tipe === 'barang') {
                            $product = $item->product;
                            $product->stok += $item->quantity;
                            $product->save();
                        }
                        $item->delete();
                    }
                    continue;
                }

                // Handle item yang sudah ada (update)
                if (!empty($itemData['id'])) {
                    $item = SalesItem::where('id', $itemData['id'])
                        ->where('sales_id', $sale->id)
                        ->firstOrFail();

                    $product = Product::find($itemData['product_id']);
                    $total = $product->unit_price * $item['quantity'];
                    // Kembalikan stok sebelumnya jika produk barang
                    if ($item->product->tipe === 'barang') {
                        $item->product->stok += $item->quantity;
                        $item->product->save();
                    }

                    $dataUpdate = [
                        'product_id' => $product->id,
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $product->unit_price,
                        'total_price' => $product->unit_price * $itemData['quantity'],
                        'discount_percentage' => $itemData['dicount_percentage'],
                        'discount_nominal' => $total * ($itemData['dicount_percentage'] / 100),
                        'price_after_discount' => $total * (1 - ($itemData['dicount_percentage'] / 100)),
                    ];

                    // dump($item);
                    // dd($dataUpdate);
                    $item->update($dataUpdate);

                    // Kurangi stok baru jika produk barang
                    if ($product->tipe === 'barang') {
                        $product->stok -= $itemData['quantity'];
                        $product->save();
                    }

                    $existingItemIds[] = $item->id;
                }
                // Handle item baru (create)
                else {
                    $product = Product::find($itemData['product_id']);
                    $total = $product->unit_price * $item['quantity'];
                    $item = $sale->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $product->unit_price,
                        'total_price' => $product->unit_price * $itemData['quantity'],
                        'discount_percentage' => $item['dicount_percentage'],
                        'discount_nominal' => $total * ($item['dicount_percentage'] / 100),
                        'price_after_discount' => $total * (1 - ($item['dicount_percentage'] / 100)),
                    ]);

                    // Kurangi stok jika produk barang
                    if ($product->tipe === 'barang') {
                        $product->stok -= $itemData['quantity'];
                        $product->save();
                    }

                    $existingItemIds[] = $item->id;
                }
            }

            // Hapus item yang tidak ada dalam request dan belum ditandai untuk dihapus
            $sale->items()
                ->whereNotIn('id', $existingItemIds)
                ->each(function ($item) {
                    // Kembalikan stok jika produk barang
                    if ($item->product->tipe === 'barang') {
                        $product = $item->product;
                        $product->stok += $item->quantity;
                        $product->save();
                    }
                    $item->delete();
                });

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'Penjualan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui penjualan: ' . $e->getMessage());
        }
    }

    public function delete_item($id, $sale_id)
    {
        SalesItem::destroy($id);
        return redirect('/sales' . '/' . $sale_id . '/edit');
    }

    public function destroyItem(SalesItem $item)
    {
        DB::beginTransaction();

        try {
            // Kembalikan stok jika produk adalah barang
            if ($item->product->tipe === 'barang') {
                $product = $item->product;
                $product->stok += $item->quantity;
                $product->save();
            }

            $item->delete();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false], 500);
        }
    }

    public function destroy(Sales $sale)
    {
        DB::beginTransaction();

        try {
            // Kembalikan stok produk jika dibatalkan
            foreach ($sale->items as $item) {
                if ($item->product->tipe === 'barang') {
                    $product = $item->product;
                    $product->stok += $item->quantity;
                    $product->save();
                }
            }

            $sale->delete();

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus penjualan: ' . $e->getMessage());
        }
    }
}
