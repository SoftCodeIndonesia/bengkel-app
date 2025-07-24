<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\CustomerVehicle;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Vehicle::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer', function ($row) {
                    return $row->customers()->first()->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="flex justify-end gap-2">';
                    $btn .= '<a href="' . route('vehicles.show', $row->id) . '" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
                    $btn .= '</a>';
                    $btn .= '<a href="' . route('products.edit', $row->id) . '" class="p-2 text-green-600 hover:bg-green-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
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
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('vehicles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        return view('vehicles.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'merk' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'no_pol' => 'required|string|max:20|unique:vehicles',
            'customer_id' => 'required|exists:customers,id'
        ]);

        $vehicle = Vehicle::create($request->only(['merk', 'tipe', 'no_pol']));

        // Create relationship in customer_vehicle table
        CustomerVehicle::create([
            'customer_id' => $request->customer_id,
            'vehicle_id' => $vehicle->id
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Data kendaraan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $currentCustomer = $vehicle->customers()->first();

        return view('vehicles.edit', compact('vehicle', 'currentCustomer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'merk' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'no_pol' => 'required|string|max:20|unique:vehicles,no_pol,' . $vehicle->id,
            'customer_id' => 'required|exists:customers,id'
        ]);

        $vehicle->update($request->only(['merk', 'tipe', 'no_pol']));

        // Update relationship
        $customerVehicle = CustomerVehicle::where('vehicle_id', $vehicle->id)->first();
        if ($customerVehicle) {
            $customerVehicle->update(['customer_id' => $request->customer_id]);
        }

        return redirect()->route('vehicles.index')->with('success', 'Data kendaraan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Data kendaraan berhasil dihapus');
    }
}
