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
                    <input type="hidden" class="buying_price" value="' . $row->buying_price . '" />
                    ' . $html . '
                    ';
                })
                ->addColumn('buying_price', function ($row) {
                    return 'Rp ' . number_format($row->buying_price, 0, ',', '.');
                })
                ->addColumn('grade', function ($row) {
                    $oem2 = '<span class=" text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm bg-red-100 text-red-800">' . $row->grade . '</span>';
                    $genuine = '<span class="text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm bg-green-100 text-green-800">' . $row->grade . '</span>';
                    $oem1 = '<span class=" text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm bg-yellow-100 text-yellow-800">' . $row->grade . '</span>';
                    if ($row->grade == 'Genuine') {
                        return $genuine;
                    } else if ($row->grade == 'OEM 1') {
                        return $oem1;
                    } else if ($row->grade == 'OEM 2') {
                        return $oem2;
                    } else {
                        return '<p>-</p>';
                    }
                })
                ->addColumn('formatted_price', function ($row) {
                    $price = ceil(100000 * $row->stok);
                    return $row->tipe == 'jasa' ? 'Rp ' . number_format($price, 0, ',', '.') : 'Rp ' . number_format($row->unit_price, 0, ',', '.');
                })
                ->addColumn('formatted_price_buying', function ($row) {
                    $price = ceil(100000 * $row->stok);
                    return $row->tipe == 'jasa' ? 'Rp ' . number_format($price, 0, ',', '.') : 'Rp ' . number_format($row->buying_price, 0, ',', '.');
                })
                ->addColumn('margin', function ($row) {
                    return $row->margin . '%';
                })

                ->rawColumns(['checkbox', 'grade'])
                ->make(true);
        }
    }
}
