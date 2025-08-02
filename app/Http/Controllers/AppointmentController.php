<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $appointments = Appointment::with(['customer', 'vehicle'])
                ->select('appointments.*');

            return DataTables::of($appointments)
                ->addIndexColumn()
                ->addColumn('date_time', function ($row) {
                    return $row->date;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="flex justify-end">';
                    $btn .= '<a href="' . route('appointments.show', $row->id) . '" class="text-blue-500 hover:text-blue-600 mr-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>';
                    $btn .= '<a href="' . route('appointments.edit', $row->id) . '" class="text-yellow-500 hover:text-yellow-600 mr-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>';
                    $btn .= '<button type="button" data-id="' . $row->id . '" data-name="' . $row->customer->name . '" class="delete-appointments text-red-500 hover:text-red-600" ><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('appointments.index');
    }

    public function create()
    {
        $customers = Customer::all();
        return view('appointments.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'date' => 'required|date',
            'time' => 'required',
            'service_request' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        Appointment::create($validated);

        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['customer', 'vehicle', 'createdBy']);
        return view('appointments.show', compact('appointment'));
    }

    public function getCustomerVehicles($customerId)
    {
        $customer = Customer::with('vehicles')->findOrFail($customerId);
        return response()->json($customer->vehicles);
    }

    public function edit(Appointment $appointment)
    {

        $appointment->load([
            'customer',
            'vehicle',
        ]);

        return view('appointments.edit', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'service_request' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment->id)
            ->with('success', 'Appointment updated successfully.');
    }
    public function destroy(Appointment $appointment)
    {
        try {
            $appointment->delete();
            return redirect()->route('appointments.index')->with('success', 'Appointment Berhasil Di Hapus');
        } catch (\Throwable $th) {
            return back()->with('error', 'Appointment Gagal Di Hapus');
        }
    }
}
