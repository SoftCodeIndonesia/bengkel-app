<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\MovementItem;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $purchases = Purchase::with(['supplier']);

            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $status = $request->status;

            if ($startDate) {
                $purchases->when($startDate, function ($query) use ($startDate) {
                    $query->whereDate('created_at', '>=', $startDate);
                });
            }
            if ($endDate) {
                $purchases->when($endDate, function ($query) use ($endDate) {
                    $query->whereDate('created_at', '<=', $endDate);
                });
            }
            if ($status) {
                $purchases->when($status, function ($query) use ($status) {
                    $query->where('status', $status);
                });
            }

            $purchases->latest();

            return datatables()->of($purchases)
                ->addIndexColumn()
                ->addColumn('action', function ($purchase) {
                    return '
                    <div class="flex justify-end space-x-2">
                        <a href="' . route('purchases.show', $purchase->id) . '" class="text-blue-500 hover:text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="' . route('purchases.edit', $purchase->id) . '" class="text-yellow-500 hover:text-yellow-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <button class="text-red-500 hover:text-red-600 delete-btn" 
                                data-id="' . $purchase->id . '" 
                                data-invoice="' . $purchase->invoice_number . '">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                        
                    </div>
                ';
                })
                ->editColumn('purchase_date', function ($purchase) {
                    return Carbon::parse($purchase->date)->translatedFormat('d F Y');
                })
                ->editColumn('status_tag', function ($row) {
                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full ' . $row->statusColor() . '">' . ucfirst($row->statusText()) . '</span>';
                })
                ->editColumn('total', function ($purchase) {
                    return 'Rp ' . number_format($purchase->total, 0, ',', '.');
                })
                ->addColumn('supplier_name', function ($purchase) {
                    return $purchase->supplier->name ?? '-';
                })
                ->rawColumns(['action', 'status_tag'])
                ->toJson();
        }
        return view('purchases.index');
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::where(['tipe' => 'barang'])->get();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {



        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string',
            'invoice_number' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // dd($request->all());


        DB::beginTransaction();

        try {
            $total = collect($request->items)->sum(function ($item) {
                return $item['quantity'] * $item['unit_price'];
            });

            $data_purchase = [
                'invoice_number' => $request->invoice_number ?? 'PUR-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'total' => $total,
                'notes' => $request->notes,
                'status' => $request->status,
                'source_documents' => null,
            ];

            if ($request->hasFile('source_document')) {
                $filePath = $request->file('source_document')->store('purchases', 'public');
                $data_purchase['source_documents'] = $filePath;
                $data_purchase['original_filename'] = $request->file('source_document')->getClientOriginalName();
            }

            $purchase = Purchase::create($data_purchase);

            foreach ($request->items as $item) {
                $product = json_decode($item['product_id']);
                $productModel = null;

                if (gettype($product) == 'object') {
                    $productModel = Product::find($product->id);
                } else {
                    $productModel = Product::find($product);
                }



                $productModel->unit_price = $item['selling_price'];
                $productModel->buying_price = $item['unit_price'];
                $productModel->margin = $item['margin'];
                $productModel->save();

                $purchaseItem = PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productModel->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                    'status' => 'draft',
                ]);

                MovementItem::create([
                    'move' => 'in',
                    'reference' => 'purchase_items',
                    'reference_id' => $purchaseItem->id,
                    'product_id' => $productModel->id,
                    'item_name' => $productModel->name, // pastikan ada 'name' di array
                    'name' => 'purchases',
                    'item_description' => $productModel->description ?? null,
                    'quantity' => 0,
                    'buying_price' => $item['unit_price'],
                    'selling_price' => $item['selling_price'],
                    'total_price' => $purchaseItem->total_price,
                    'discount' => $item['discount'] ?? 0,
                    'grand_total' => $purchaseItem->total_price,
                    'created_by' => Auth::id(),
                    'status' => 'draft',
                    'est_quantity' => $item['quantity'],
                    'note' => null,
                ]);

                // Update stok produk
                // $product = Product::find($product->id);
                // $product->stok += $item['quantity'];
                // $product->save();
            }

            DB::commit();

            return redirect()->route('purchases.show', $purchase->id)
                ->with('success', 'Pembelian berhasil dicatat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.product']);
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        $purchase->load('items.product', 'supplier');
        $suppliers = Supplier::all();
        $products = Product::where(['tipe' => 'barang'])->get();


        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'purchase_date' => 'required|date',
                'invoice_number' => 'required|unique:purchases,invoice_number,' . $purchase->id,
                'items' => 'required|array',
                'items.*.product_id' => 'required',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            // Array untuk menyimpan ID item yang akan dihapus
            $itemsToDelete = [];

            // Proses item yang dikirim dari form
            $total = 0;
            foreach ($request->items as $item) {
                // Jika item memiliki prefix 'delete_', tandakan untuk dihapus
                if (isset($item['id']) && str_starts_with($item['id'], 'delete_')) {
                    $originalId = str_replace('delete_', '', $item['id']);
                    $itemsToDelete[] = $originalId;
                    continue;
                }

                $totalItem = $item['quantity'] * $item['unit_price'];
                $total += $totalItem;


                if (isset($item['id']) && !empty($item['id'])) {

                    // Update item yang sudah ada
                    $purchaseItem = PurchaseItem::find($item['id']);

                    // Hitung selisih quantity untuk update stok
                    // $quantityDiff = $item['quantity'] - $purchaseItem->quantity;

                    $purchaseItem->update([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $totalItem,
                    ]);

                    $movementItem = MovementItem::where(['reference' => 'purchase_items', 'reference_id' => $purchaseItem->id])->get()->first();
                    $movementItem->est_quantity = $item['quantity'];
                    $movementItem->total_price = $totalItem;
                    $movementItem->grand_total = $totalItem;
                    $movementItem->save();
                } else {
                    $product_convert = json_decode($item['product_id']);

                    // Buat item baru
                    $purchaseItem = PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $product_convert->value,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $totalItem,
                    ]);

                    MovementItem::create([
                        'move' => 'in',
                        'reference' => 'purchase_items',
                        'reference_id' => $purchaseItem->id,
                        'product_id' => $product_convert->id,
                        'item_name' => $product_convert->name, // pastikan ada 'name' di array
                        'name' => 'purchases',
                        'item_description' => $product_convert->description ?? null,
                        'quantity' => 0,
                        'buying_price' => $item['unit_price'],
                        'selling_price' => $product_convert->unit_price,
                        'total_price' => $purchaseItem->total_price,
                        'discount' => $item['discount'] ?? 0,
                        'grand_total' => $purchaseItem->total_price,
                        'created_by' => Auth::id(),
                        'status' => 'draft',
                        'est_quantity' => $item['quantity'],
                        'note' => null,
                    ]);
                }
            }

            // Hapus item yang ditandai
            if (!empty($itemsToDelete)) {
                $deletedItems = PurchaseItem::whereIn('id', $itemsToDelete)->get();


                PurchaseItem::whereIn('id', $itemsToDelete)->delete();
            }



            $data_update = [
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'total' => $total,
                'notes' => $request->notes,
                'status' => $request->status,
                'source_documents' => null,
            ];

            if ($request->hasFile('source_document')) {
                $filePath = $request->file('source_document')->store('purchases', 'public');
                $data_update['source_documents'] = $filePath;
                $data_update['original_filename'] = $request->file('source_document')->getClientOriginalName();
                if ($purchase->source_document && Storage::disk('public')->exists($purchase->source_document)) {
                    Storage::disk('public')->delete($purchase->source_document);
                }
            }


            $purchase->update($data_update);

            DB::commit();

            return redirect()->route('purchases.show', $purchase->id)
                ->with('success', 'Pembelian berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui pembelian: ' . $e->getMessage());
        }
    }

    public function destroy(Purchase $purchase)
    {
        DB::beginTransaction();

        try {
            // // Kembalikan stok
            // foreach ($purchase->items as $item) {
            //     $product = Product::withTrashed()->find($item->product_id);
            //     $product->stok -= $item->quantity;
            //     $product->save();
            // }

            $purchase->items()->delete();
            $purchase->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembelian berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function print(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.product']);
        $pdf = Pdf::loadView('purchases.print', compact('purchase'));
        return $pdf->stream('purchase-' . $purchase->invoice_number . '.pdf');
    }

    public function data()
    {
        $purchases = Purchase::query();

        return datatables()->of($purchases)
            ->addIndexColumn()
            ->addColumn('action', function ($purchase) {
                return '
                    <div class="flex justify-end space-x-2">
                        <a href="' . route('purchases.show', $purchase->id) . '" class="text-blue-500 hover:text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="' . route('purchases.edit', $purchase->id) . '" class="text-yellow-500 hover:text-yellow-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <button class="text-red-500 hover:text-red-600 delete-btn" 
                                data-id="' . $purchase->id . '" 
                                data-invoice="' . $purchase->invoice_number . '">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                        <a href="' . route('purchases.print', $purchase->id) . '" target="_blank" class="text-green-500 hover:text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                        </a>
                    </div>
                ';
            })
            ->editColumn('purchase_date', function ($purchase) {
                return $purchase->purchase_date->format('d M Y');
            })
            ->editColumn('total', function ($purchase) {
                return 'Rp ' . number_format($purchase->total, 0, ',', '.');
            })
            ->addColumn('supplier_name', function ($purchase) {
                return $purchase->supplier->name ?? '-';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function delete_product($id, $purchase_id) {}


    public function download($id)
    {
        $purchase = Purchase::findOrFail($id);

        if (!$purchase->source_documents || !Storage::disk('public')->exists($purchase->source_documents)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        // Gunakan original_filename jika tersedia, fallback ke basename
        $filename = $purchase->original_filename ?? basename($purchase->source_documents);

        return Storage::disk('public')->download($purchase->source_documents, $filename);
    }


    public function deleteFile($id)
    {
        $purchase = Purchase::findOrFail($id);

        if ($purchase->source_documents && Storage::disk('public')->exists($purchase->source_documents)) {
            // Hapus file dari storage
            Storage::disk('public')->delete($purchase->source_documents);
        }

        // Kosongkan field di database
        $purchase->source_documents = null;
        $purchase->original_filename = null;
        $purchase->save();

        return redirect()->back()->with('success', 'File berhasil dihapus.');
    }
}
