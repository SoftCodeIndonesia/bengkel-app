<?php

namespace App\Http\Controllers;

use App\Models\CustomerVehicle;
use Illuminate\Http\Request;

class CustomerVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function destroy(CustomerVehicle $customerVehicle)
    {
        //
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
