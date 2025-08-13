<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function productList(Request $request)
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

                ->rawColumns(['checkbox'])
                ->make(true);
        }
    }
}
