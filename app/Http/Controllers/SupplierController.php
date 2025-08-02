<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        return view('suppliers.index');
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Supplier::create($validator->validated());

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }
    public function quickCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal Menyimpan Data!'
            ]);
        }

        $data = Supplier::create($validator->validated());

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $supplier->update($validator->validated());

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil dihapus'
        ]);
    }

    public function data()
    {
        $suppliers = Supplier::query();

        return datatables()->of($suppliers)
            ->addIndexColumn()
            ->addColumn('action', function ($supplier) {
                return '
                    <div class="flex justify-end space-x-2">
                        <a href="' . route('suppliers.edit', $supplier->id) . '" class="text-yellow-500 hover:text-yellow-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <button class="text-red-500 hover:text-red-600 delete-btn" 
                                data-id="' . $supplier->id . '" 
                                data-name="' . htmlspecialchars($supplier->name) . '">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $suppliers = Supplier::where('name', 'like', '%' . $query . '%')
            ->orWhere('phone', 'like', '%' . $query . '%')
            ->limit(10)
            ->get()
            ->map(function ($supplier) {
                return [
                    'id' => $supplier->id,
                    'text' => $supplier->name . ' - (' . $supplier->phone . ') - ' . $supplier->address,
                    'phone' => $supplier->phone,
                    'address' => $supplier->address
                ];
            });

        return response()->json([
            'data' => $suppliers
        ]);
    }

    public function getSupplier(Supplier $supplier)
    {
        return response()->json([
            'data' => [
                'id' => $supplier->id,
                'text' => $supplier->name,
                'phone' => $supplier->phone,
                'address' => $supplier->address
            ]
        ]);
    }
}
