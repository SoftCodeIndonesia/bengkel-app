<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {



            $data = StockOpname::with(['creator'])
                ->select('*');


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('creator', function ($row) {
                    return $row->creator->name;
                })
                ->addColumn('format_date', function ($row) {
                    return $row->opname_date->format('d-M-Y');
                })
                ->addColumn('status_badge', function ($row) {
                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full ' . $row->statusColor() . '">' . ucfirst($row->statusText()) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="flex justify-end gap-2">';
                    $btn .= '<a href="' . route('stock-opname.show', $row->id) . '" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
                    $btn .= '</a>';
                    $btn .= '<a href="' . route('stock-opname.edit', $row->id) . '" class="p-2 text-green-600 hover:bg-green-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
                    $btn .= '</a>';

                    if ($row->status != 'completed' && $row->status != 'progress') {
                        $btn .= '<button type="button" data-id="' . $row->id . '" data-name="' . $row->unique_id . '"class="delete-jo p-2 text-red-600 hover:bg-green-50 rounded-lg">';
                        $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                        $btn .= '</button>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                // ->orderColumn('service_at', 'service_at $1')
                // ->orderColumn('formatted_total', 'total $1')
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }
        return view('opnames.index');
    }

    public function create()
    {
        $totalProducts = Product::where('tipe', '!=', 'jasa')->count();
        return view('opnames.create', compact('totalProducts'));
    }

    public function edit(string $id)
    {
        $opname = StockOpname::with('items', 'items.product')->find($id);
        // dd($opname);
        return view('opnames.edit', compact('opname'));
    }

    public function store(Request $request)
    {


        $request->validate([
            'opname_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.physical_stock' => 'required|integer|min:0',
            'items.*.notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $opname = StockOpname::create([
                'opname_number' => $request->opname_number,
                'opname_date' => $request->opname_date,
                'created_by' => auth()->id(),
                'notes' => $request->notes,
                'status' => 'completed'
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                $opname->items()->create([
                    'product_id' => $item['product_id'],
                    'system_stock' => $product->stok,
                    'physical_stock' => $item['physical_stock'],
                    'difference' => $item['physical_stock'] - $product->stok,
                    'unit_price' => $product->unit_price,
                    'total_difference' => ($item['physical_stock'] - $product->stok) * $product->unit_price,
                    'notes' => $item['notes'] ?? null
                ]);

                // Update stock
                $product->update(['stok' => $item['physical_stock']]);
            }
        });

        return redirect()->route('stock-opname.index')->with('success', 'Stok opname berhasil disimpan');
    }

    public function update(Request $request, StockOpname $stockOpname)
    {
        $request->validate([
            'opname_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.physical_stock' => 'required|integer|min:0',
            'items.*.notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request, $stockOpname) {
            // Update header
            $stockOpname->update([
                'opname_date' => $request->opname_date,
                'notes' => $request->notes
            ]);

            // Update items
            foreach ($request->items as $itemData) {
                $item = $stockOpname->items()->where('product_id', $itemData['product_id'])->first();

                if ($item) {
                    $item->update([
                        'physical_stock' => $itemData['physical_stock'],
                        'difference' => $itemData['difference'],
                        'total_difference' => $item->unit_price * $itemData['difference'],
                        'notes' => $itemData['notes'] ?? null
                    ]);

                    // Update stock produk jika status completed
                    if ($stockOpname->status === 'completed') {
                        Product::where('id', $itemData['product_id'])
                            ->update(['stok' => $itemData['physical_stock']]);
                    }
                }
            }
        });

        return redirect()->route('stock-opname.show', $stockOpname->id)
            ->with('success', 'Stok opname berhasil diperbarui');
    }

    public function show(StockOpname $stockOpname)
    {
        return view('opnames.show', compact('stockOpname'));
    }

    public function getProducts(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 50; // Load 50 produk per request

        $products = Product::orderBy('name')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        return response()->json([
            'products' => $products,
            'has_more' => ($page * $perPage) < Product::count()
        ]);
    }
}
