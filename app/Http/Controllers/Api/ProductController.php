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
            $data = Product::select('*');


            if ($request->has('tipe')) {
                if ($request->tipe == 'barang') {
                    $data->where('tipe', '!=', 'jasa');
                } else {
                    $data->where('tipe', '=', 'jasa');
                }
            } else {
                $data->where('tipe', '=', 'jasa');
            }

            // var_dump($data->toSql());

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $html = '<input type="hidden" class="qty" value="1" />';
                    if ($row->tipe == 'jasa') {
                        $html = '<input type="hidden" class="qty" value="' . $row->stok . '" />';
                    }
                    return '
                    <input type="checkbox" class="row-checkbox product-checkbox text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500" value="' . $row->id . '">
                    <input type="hidden" class="tipe" value="' . $row->tipe . '" />
                    ' . $html . '
                    ';
                })
                ->addColumn('buying_price', function ($row) {
                    return 'Rp ' . number_format($row->buying_price, 0, ',', '.');
                })
                ->addColumn('formatted_price', function ($row) {
                    $price = ceil(100000 * $row->stok);
                    return $row->tipe == 'jasa' ? 'Rp ' . number_format($price, 0, ',', '.') : 'Rp ' . number_format($row->unit_price, 0, ',', '.');
                })
                ->addColumn('margin', function ($row) {
                    return $row->margin . '%';
                })

                ->rawColumns(['checkbox'])
                ->make(true);
        }
    }
}
