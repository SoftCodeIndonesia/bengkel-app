<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::where('tipe', 'jasa')->select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('formatted_price', function ($row) {
                    return 'Rp ' . number_format($row->unit_price, 0, ',', '.');
                })
                ->addColumn('formatted_frt', function ($row) {
                    // if (!$row->stok) return '-';

                    // $hours = floor($row->stok / 60);
                    // $minutes = $row->stok % 60;

                    // $result = [];
                    // if ($hours > 0) $result[] = $hours . ' jam';
                    // if ($minutes > 0) $result[] = $minutes . ' menit';

                    // return implode(' ', $result);
                    return $row->stok;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="flex justify-end gap-2">';
                    $btn .= '<a href="' . route('services.edit', $row->id) . '" class="p-2 text-green-600 hover:bg-green-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
                    $btn .= '</a>';

                    $btn .= '<form class="inline" action="' . route('services.destroy', $row->id) . '" method="POST">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '<button type="submit" onclick="return confirm(\'Apakah Anda yakin ingin menghapus jasa ini?\')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                    $btn .= '</button>';
                    $btn .= '</form>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('services.index');
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Nama jasa wajib diisi',
            'unit_price.required' => 'Harga wajib diisi',
            'unit_price.numeric' => 'Harga harus berupa angka',
            'stok.numeric' => 'Harga harus berupa angka'
        ]);

        Product::create([
            'name' => $request->name,
            'tipe' => 'jasa',
            'unit_price' => $request->unit_price,
            'stok' => $request->stok, // Jasa tidak memiliki stok,
            'description' => $request->description ?? '',
        ]);

        return redirect()->route('services.index')->with('success', 'Jasa berhasil ditambahkan');
    }

    public function edit(Product $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Product $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
        ]);

        $service->update([
            'name' => $request->name,
            'unit_price' => $request->unit_price,
            'stok' => $request->stok
        ]);

        return redirect()->route('services.index')->with('success', 'Jasa berhasil diperbarui');
    }

    public function destroy(Product $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Jasa berhasil dihapus');
    }
}
