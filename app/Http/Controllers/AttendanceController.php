<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {



            $data = Attendance::with(['employee'])
                ->select('*');


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('employee_name', function ($row) {
                    return $row->employee->name;
                })
                ->addColumn('format_date', function ($row) {
                    return $row->date->format('d-M-Y');
                })
                ->addColumn('status_badge', function ($row) {
                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full ' . $row->statusColor() . '">' . ucfirst($row->statusText()) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="flex justify-end gap-2">';
                    $btn .= '<a href="' . route('attendances.show', $row->id) . '" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
                    $btn .= '</a>';
                    $btn .= '<a href="' . route('attendances.edit', $row->id) . '" class="p-2 text-green-600 hover:bg-green-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
                    $btn .= '</a>';

                    if ($row->status != 'completed' && $row->status != 'progress') {
                        $btn .= '<button type="button" data-id="' . $row->id . '" data-name="' . $row->unique_id . '"class="delete-jo p-2 text-red-600 hover:bg-green-50 rounded-lg">';
                        $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                        $btn .= '</button>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                // ->orderColumn('service_at', 'service_at $1')
                // ->orderColumn('formatted_total', 'total $1')
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('attendances.index');
    }

    public function create()
    {
        $employees = Employee::orderBy('name')->get();
        $attendance = null;
        return view('attendances.create', compact('employees', 'attendance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'status' => 'required|in:present,late,absent,leave',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            Attendance::create($request->all());

            DB::commit();
            return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data absensi: ' . $e->getMessage());
        }
    }

    public function show(Attendance $attendance)
    {
        return view('attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $employees = Employee::orderBy('name')->get();
        return view('attendances.create', compact('attendance', 'employees'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'status' => 'required|in:present,late,absent,leave',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $attendance->update($request->all());

            DB::commit();
            return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data absensi: ' . $e->getMessage());
        }
    }

    public function destroy(Attendance $attendance)
    {
        try {
            DB::beginTransaction();

            $attendance->delete();

            DB::commit();
            return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data absensi: ' . $e->getMessage());
        }
    }

    public function report()
    {


        $employees = Employee::with(['attendances' => function ($query) {
            $query->whereMonth('date', now()->month)
                ->whereYear('date', now()->year);
        }])->get();

        return view('attendances.report', compact('employees'));
    }
}
