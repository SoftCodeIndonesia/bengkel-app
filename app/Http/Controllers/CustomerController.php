<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Customer::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="flex justify-end gap-2">';
                    $btn .= '<a href="' . route('customers.show', $row->id) . '" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
                    $btn .= '</a>';
                    $btn .= '<a href="' . route('customers.edit', $row->id) . '" class="p-2 text-green-600 hover:bg-green-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
                    $btn .= '</a>';

                    $btn .= '<form class="inline" action="' . route('customers.destroy', $row->id) . '" method="POST">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '<button type="submit" onclick="return confirm(\'Apakah Anda yakin ingin menghapus pelanggan ini?\')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                    $btn .= '</button>';
                    $btn .= '</form>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }



        return view('customers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'vehicles' => 'sometimes|array',
            'vehicles.*.merk' => 'required_with:vehicles|string|max:255',
            'vehicles.*.tipe' => 'required_with:vehicles|string|max:255',
            'vehicles.*.no_pol' => 'required_with:vehicles|string|max:20',
        ]);

        DB::transaction(function () use ($validated) {
            // Buat customer
            $customer = Customer::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);

            // Jika ada kendaraan yang ditambahkan
            if (isset($validated['vehicles'])) {
                foreach ($validated['vehicles'] as $vehicleData) {
                    // Cari atau buat kendaraan
                    $vehicle = Vehicle::firstOrCreate(
                        ['no_pol' => $vehicleData['no_pol']],
                        [
                            'merk' => $vehicleData['merk'],
                            'tipe' => $vehicleData['tipe'],
                            'no_pol' => $vehicleData['no_pol'],
                        ]
                    );

                    // Hubungkan dengan customer
                    $customer->vehicles()->attach($vehicle->id);
                }
            }
        });

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan baru berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui');
    }

    public function destroy(Customer $customer)
    {
        DB::transaction(function () use ($customer) {
            $customer->vehicles()->detach();
            $customer->delete();
        });

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus');
    }

    public function search(Request $request)
    {
        $search = $request->get('q');

        $customers = Customer::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%");
        })
            ->limit(10)
            ->get()
            ->map(function ($customer) {
                return [
                    'value' => $customer->id,
                    'text' => $customer->name,
                    'phone' => $customer->phone
                ];
            });

        return response()->json($customers);
    }
}
