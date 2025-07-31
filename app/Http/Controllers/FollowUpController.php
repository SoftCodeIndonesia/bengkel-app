<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use Illuminate\Http\Request;
use App\Models\CustomerVehicle;

class FollowUpController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_vehicle_id' => 'required|exists:customer_vehicle,id',
            'contact_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        FollowUp::create([
            'customer_vehicle_id' => $request->customer_vehicle_id,
            'last_service_date' => CustomerVehicle::find($request->customer_vehicle_id)
                ->jobOrders()
                ->where('status', 'completed')
                ->latest('service_at')
                ->first()
                ->service_at,
            'contact_date' => $request->contact_date,
            'contacted' => true,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Follow up berhasil dicatat');
    }
}
