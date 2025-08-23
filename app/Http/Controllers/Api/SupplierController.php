<?php

namespace App\Http\Controllers\Api;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Supplier::select('*');


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="flex justify-end gap-2">';
                    $btn .= '<button type="button" data-id="' . $row->id . '" data-name="' . $row->name . '"class="select-supplier text-blue-600">Pilih';
                    $btn .= '</button>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
