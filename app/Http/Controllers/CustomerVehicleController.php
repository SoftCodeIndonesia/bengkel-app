<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerVehicle;
use PDO;
use Yajra\DataTables\DataTables;

class CustomerVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function tableSearch(Request $request)
    {
        $data = CustomerVehicle::with('customer', 'vehicle');


        if ($request->search['value']) {
            $data = $data->whereHas('customer', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search['value'] . '%');
            })->orWhereHas('vehicle', function ($query) use ($request) {
                $query->where('no_pol', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('merk', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('tipe', 'like', '%' . $request->search['value'] . '%');
            });
        }

        $data = $data->select('*');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('customer_name', function ($row) {
                return $row->customer->name;
            })
            ->addColumn('phone', function ($row) {
                return $row->customer->phone;
            })
            ->addColumn('address', function ($row) {
                return $row->customer->address;
            })
            ->addColumn('no_pol', function ($row) {
                return $row->vehicle->no_pol;
            })
            ->addColumn('kendaraan', function ($row) {
                return $row->vehicle->merk . ' ' . $row->vehicle->tipe;
            })
            ->addColumn('action', function ($row) {

                $btn = '<button type="button" data-id="' . $row->id . '" data-name="' . $row->customer->name . '" class="select-customer text-white bg-green-800 px-5 py-1 rounded-lg">Pilih';
                $btn .= '</button>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerVehicle $customerVehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerVehicle $customerVehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerVehicle $customerVehicle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = CustomerVehicle::find($id);

        if ($data) {
            CustomerVehicle::destroy($id);
            return redirect()->route('vehicles.index')->with('success', 'Data Kendaraan Telah Dihapus!');
        } else {
            return redirect()->route('vehicles.index')->with('error', 'Data Kendaraan Tidak Ditemukan!');
        }
        // dd($customerVehicle);
    }

    public function followUps()
    {
        $vehicles = CustomerVehicle::with(['customer', 'latestJobOrder'])
            ->needsFollowUp()
            ->paginate(10);

        return view('follow-up.index', compact('vehicles'));
    }

    // app/Http/Controllers/CustomerVehicleController.php
    public function getDetails($id)
    {
        $customerVehicle = CustomerVehicle::with(['customer', 'vehicle'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'customer' => $customerVehicle->customer,
            'vehicle' => $customerVehicle->vehicle
        ]);
    }
}
