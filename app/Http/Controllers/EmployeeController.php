<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Employee::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('photo', function ($row) {
                    return $row->photo
                        ? '<img src="' . asset('storage/' . $row->photo) . '" class="w-10 h-10 rounded-full object-cover">'
                        : '<div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center text-white">' . strtoupper(substr($row->name, 0, 1)) . '</div>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="flex justify-end gap-2">';
                    $btn .= '<a href="' . route('employees.show', $row->id) . '" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
                    $btn .= '</a>';
                    $btn .= '<a href="' . route('employees.edit', $row->id) . '" class="p-2 text-green-600 hover:bg-green-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
                    $btn .= '</a>';


                    $btn .= '<button type="button" data-id="' . $row->id . '" data-name="' . $row->name . '"class="delete-karyawan p-2 text-red-600 hover:bg-green-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                    $btn .= '</button>';

                    $btn .= '</div>';
                    return $btn;
                })

                ->rawColumns(['photo', 'action'])
                ->make(true);
        }
        return view('employees.index');
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'position' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'nullable|numeric',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('employee-photos', 'public');
        }

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'position' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'nullable|numeric',
        ]);

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employee-photos', 'public');
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus');
    }

    public function datatable()
    {
        $employees = Employee::query();

        return datatables()->eloquent($employees)
            ->addIndexColumn()
            ->addColumn('photo', function ($row) {
                return $row->photo
                    ? '<img src="' . asset('storage/' . $row->photo) . '" class="w-10 h-10 rounded-full object-cover">'
                    : '<div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center text-white">' . strtoupper(substr($row->name, 0, 1)) . '</div>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="flex justify-end space-x-2">
                        <a href="' . route('employees.show', $row->id) . '" class="px-3 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-md">Detail</a>
                        <a href="' . route('employees.edit', $row->id) . '" class="px-3 py-1 text-sm text-white bg-yellow-600 hover:bg-yellow-700 rounded-md">Edit</a>
                        <button onclick="confirmDelete(' . $row->id . ')" class="px-3 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded-md">Hapus</button>
                    </div>
                ';
            })
            ->rawColumns(['photo', 'action'])
            ->toJson();
    }
}
