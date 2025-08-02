<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use Illuminate\Http\Request;
use App\Models\CustomerVehicle;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FollowUpController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = FollowUp::with(['customerVehicle', 'customerVehicle.vehicle', 'customerVehicle.customer', 'jobOrder'])
                ->select('follow_ups.*');



            // Filter berdasarkan tanggal servis terakhir
            if ($request->has('last_service_date') && !empty($request->last_service_date)) {
                $dates = explode(' - ', $request->last_service_date);
                $query->whereBetween('last_service_date', [
                    date('Y-m-d', strtotime($dates[0])),
                    date('Y-m-d', strtotime($dates[1]))
                ]);
            }

            // Filter berdasarkan status contacted
            if ($request->has('contacted') && $request->contacted !== '') {
                $query->where('contacted', $request->contacted);
            }

            // Filter berdasarkan tanggal kontak
            if ($request->has('contact_date') && !empty($request->contact_date)) {
                $dates = explode(' - ', $request->contact_date);
                $query->whereBetween('contact_date', [
                    date('Y-m-d', strtotime($dates[0])),
                    date('Y-m-d', strtotime($dates[1]))
                ]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('vehicle', function ($row) {
                    $html = '<a href="' . route('follow-ups.show', $row->id) . '" class="text-blue-500 hover:text-blue-600">';
                    $html .= $row->customerVehicle->vehicle->merk . ' ' . $row->customerVehicle->vehicle->tipe;
                    $html .= '</a>';
                    return $html;
                })
                ->addColumn('customer', function ($row) {
                    return $row->customerVehicle->customer->name;
                })
                ->addColumn('jo_number', function ($row) {
                    return $row->jobOrder->unique_id;
                })
                ->addColumn('action', function ($row) {
                    return [
                        'id' => $row->id,
                        'edit_url' => route('follow-ups.edit', $row->id),
                        'delete_url' => route('follow-ups.destroy', $row->id)
                    ];
                })
                ->rawColumns(['action', 'vehicle'])
                ->make(true);
        }



        return view('follow-up.index');
    }

    public function store(Request $request)
    {

        // dd($request->all());

        try {
            $request->validate([
                'order_id' => 'required|exists:job_orders,id',
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
                'job_order_id' => $request->order_id,
            ]);

            return redirect()->back()->with('success', 'Follow up berhasil dicatat');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Follow up Gagal dicatat');
        }
    }

    public function show(FollowUp $followUp)
    {
        return view('follow-up.show', compact('followUp'));
    }

    public function edit(FollowUp $followUp)
    {
        $followUp->load([
            'customerVehicle',
            'customerVehicle.customer',
            'customerVehicle.vehicle',
            'jobOrder'
        ]);
        // dd($followUp);
        return view('follow-up.edit', compact('followUp'));
    }

    public function update(Request $request, FollowUp $followUp)
    {
        // Validasi data input
        $validated = $request->validate([
            'customer_vehicle_id' => 'required|exists:customer_vehicle,id',
            'last_service_date' => 'required|date',
            'contacted' => 'required|boolean',
            'contact_date' => 'nullable|required_if:contacted,true|date',
            'notes' => 'nullable|string|max:1000',
            'job_order_id' => 'nullable|exists:job_orders,id'
        ]);

        DB::beginTransaction();
        try {
            // Update data follow up
            $followUp->update([
                'customer_vehicle_id' => $validated['customer_vehicle_id'],
                'last_service_date' => $validated['last_service_date'],
                'contacted' => $validated['contacted'],
                'contact_date' => $validated['contacted'] ? $validated['contact_date'] : null,
                'notes' => $validated['notes'],
                'job_order_id' => $validated['job_order_id']
            ]);

            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()
                ->route('follow-ups.show', $followUp->id)
                ->with('success', 'Data follow up berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();

            // Redirect kembali dengan pesan error
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui follow up: ' . $e->getMessage());
        }
    }

    public function destroy(FollowUp $followUp)
    {
        $followUp->delete();
        return back()->with('success', 'Follow up berhasil dihapus');
    }
}
