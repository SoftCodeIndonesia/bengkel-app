<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('expenses.index');
    }

    public function getExpenses(Request $request)
    {
        $expenses = Expense::with(['category', 'recorder'])
            ->select(['id', 'date', 'expense_category_id', 'description', 'amount', 'payment_method', 'recorded_by'])
            ->orderBy('date', 'desc');

        return DataTables::of($expenses)
            ->addColumn('date_formatted', function ($expense) {
                return $expense->date->format('d/m/Y');
            })
            ->addColumn('category_name', function ($expense) {
                return $expense->category->name;
            })
            ->addColumn('amount_formatted', function ($expense) {
                return 'Rp ' . number_format($expense->amount, 2);
            })
            ->addColumn('action', function ($expense) {
                return '
                <a href="' . route('expenses.edit', $expense->id) . '" 
                   class="text-blue-400 hover:text-blue-300 mr-2">Edit</a>
                <button type="button" data-id="' . $expense->id . '" data-name="' . $expense->description . '" class="text-red-400 btn-delete hover:text-red-300">Hapus</button>
            ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        return view('expenses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'payment_method' => 'required|in:cash,bank_transfer,credit',
            'invoice_number' => 'nullable|string|unique:expenses'
        ]);

        $validated['recorded_by'] = auth()->id();

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil dicatat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        // dd($expense);
        // dd();
        $categories = ExpenseCategory::orderBy('name')->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        // dd($expense);
        $validated = $request->validate([
            'date' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'payment_method' => 'required|in:cash,bank_transfer,credit',
            'invoice_number' => 'nullable|string|unique:expenses,invoice_number,' . $expense->id
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::find($id);
        if ($expense) {
            $expense->delete();
            return back()->with('success', 'Pengeluaran berhasil dihapus');
        } else {
            return back()->with('error', 'Data Tidak Di Temukan!');
        }
    }

    public function report(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $category = $request->input('category_id');

        $query = Expense::with('category')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($category) {
            $query->where('expense_category_id', $category);
        }

        $expenses = $query->orderBy('date')->get();
        $categories = ExpenseCategory::orderBy('name')->get();

        return view('expenses.report', compact('expenses', 'categories', 'startDate', 'endDate'));
    }
}
