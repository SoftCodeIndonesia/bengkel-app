<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::where('tipe', '!=', 'jasa')->select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('buying_price', function ($row) {
                    return 'Rp ' . number_format($row->buying_price, 0, ',', '.');
                })
                ->addColumn('formatted_price', function ($row) {
                    return 'Rp ' . number_format($row->unit_price, 0, ',', '.');
                })
                ->addColumn('margin', function ($row) {
                    return $row->margin . '%';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="flex justify-end gap-2">';
                    $btn .= '<a href="' . route('products.edit', $row->id) . '" class="p-2 text-green-600 hover:bg-green-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
                    $btn .= '</a>';
                    $btn .= '<a href="' . route('products.show', $row->id) . '" class="p-2 text-green-600 hover:bg-green-50 rounded-lg">';
                    $btn .= '<svg class="w-6 h-6 text-blue-600 dark:text-blue-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M4.998 7.78C6.729 6.345 9.198 5 12 5c2.802 0 5.27 1.345 7.002 2.78a12.713 12.713 0 0 1 2.096 2.183c.253.344.465.682.618.997.14.286.284.658.284 1.04s-.145.754-.284 1.04a6.6 6.6 0 0 1-.618.997 12.712 12.712 0 0 1-2.096 2.183C17.271 17.655 14.802 19 12 19c-2.802 0-5.27-1.345-7.002-2.78a12.712 12.712 0 0 1-2.096-2.183 6.6 6.6 0 0 1-.618-.997C2.144 12.754 2 12.382 2 12s.145-.754.284-1.04c.153-.315.365-.653.618-.997A12.714 12.714 0 0 1 4.998 7.78ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd"/>
                                </svg>
                            ';
                    $btn .= '</a>';
                    $btn .= '<form class="inline" action="' . route('products.destroy', $row->id) . '" method="POST">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '<button type="submit" onclick="return confirm(\'Apakah Anda yakin ingin menghapus sparepart ini?\')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                    $btn .= '</button>';
                    $btn .= '</form>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['checkbox', 'action'])
                ->make(true);
        }

        return view('products.index');
    }
    public function info(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::where('tipe', '!=', 'jasa')->select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('buying_price', function ($row) {
                    return 'Rp ' . number_format($row->buying_price, 0, ',', '.');
                })
                ->addColumn('formatted_price', function ($row) {
                    return 'Rp ' . number_format($row->unit_price, 0, ',', '.');
                })
                ->addColumn('margin', function ($row) {
                    return $row->margin . '%';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="flex justify-end gap-2">';
                    $btn .= '<a href="' . route('products.edit', $row->id) . '" class="p-2 text-green-600 hover:bg-green-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
                    $btn .= '</a>';
                    $btn .= '<a href="' . route('products.show', $row->id) . '" class="p-2 text-green-600 hover:bg-green-50 rounded-lg">';
                    $btn .= '<svg class="w-6 h-6 text-blue-600 dark:text-blue-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M4.998 7.78C6.729 6.345 9.198 5 12 5c2.802 0 5.27 1.345 7.002 2.78a12.713 12.713 0 0 1 2.096 2.183c.253.344.465.682.618.997.14.286.284.658.284 1.04s-.145.754-.284 1.04a6.6 6.6 0 0 1-.618.997 12.712 12.712 0 0 1-2.096 2.183C17.271 17.655 14.802 19 12 19c-2.802 0-5.27-1.345-7.002-2.78a12.712 12.712 0 0 1-2.096-2.183 6.6 6.6 0 0 1-.618-.997C2.144 12.754 2 12.382 2 12s.145-.754.284-1.04c.153-.315.365-.653.618-.997A12.714 12.714 0 0 1 4.998 7.78ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd"/>
                                </svg>
                            ';
                    $btn .= '</a>';
                    $btn .= '<form class="inline" action="' . route('products.destroy', $row->id) . '" method="POST">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '<button type="submit" onclick="return confirm(\'Apakah Anda yakin ingin menghapus sparepart ini?\')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                    $btn .= '</button>';
                    $btn .= '</form>';

                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['checkbox', 'action'])
                ->make(true);
        }

        return view('sparepart.info.index');
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'part_number' => 'nullable|string|unique:products,part_number',
            'description' => 'nullable|string',
            'margin' => 'nullable|numeric|min:0',
            'buying_price' => 'nullable|numeric|min:0',
            'tipe' => 'required|in:part,oli,material',
            'unit_price' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Nama sparepart wajib diisi',
            'unit_price.required' => 'Harga satuan wajib diisi',
            'unit_price.numeric' => 'Harga harus berupa angka',
            'buying_price.required' => 'Harga beli wajib diisi',
            'buying_price.numeric' => 'Harga beli berupa angka',
        ]);


        Product::create([
            'name' => $request->name,
            'tipe' => $request->tipe,
            'buying_price' => $request->buying_price,
            'part_number' => $request->part_number,
            'description' => $request->description ?? '-',
            'margin' => $request->margin,
            'unit_price' => $request->unit_price,
            'stok' => 0,
        ]);

        return redirect()->route('products.index')->with('success', 'Sparepart berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        // $product = Product::find($id);
        return view('sparepart.info.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'buying_price' => 'required|numeric|min:0',
            'margin' => 'required|numeric|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Sparepart berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Sparepart berhasil dihapus');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        Product::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }

    public function quickCreate(Request $request)
    {
        // var_dump($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'buying_price' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'margin' => 'required|numeric|min:0',
            'code' => 'nullable',
            'tipe' => 'required'
        ]);

        $product = Product::create([
            'name' => $validated['name'],
            'buying_price' => $validated['buying_price'],
            'unit_price' => $validated['unit_price'],
            'margin' => $validated['margin'],
            'tipe' => $validated['tipe'],
            'stock' => 0,
            'min_stock' => 1,
            'part_number' => $validated['code'],
            'created_by' => auth()->id(),
            'description' => '',
        ]);

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }
}
