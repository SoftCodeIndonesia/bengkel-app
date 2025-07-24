<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\JobOrder;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $invoices = Invoice::query()
                ->with('customer')
                ->select(['invoices.*']);


            // Filter by date range
            if ($request->has('date_range') && !empty($request->date_range)) {
                $dates = explode(' - ', $request->date_range);
                $startDate = date('Y-m-d', strtotime($dates[0]));
                $endDate = date('Y-m-d', strtotime($dates[1]));

                $invoices->whereBetween('invoices.date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }

            // Filter by type
            if ($request->has('type') && !empty($request->type)) {
                $invoices->where('tipe', $request->type);
            }

            // Filter by status
            if ($request->has('status') && !empty($request->status)) {
                $invoices->where('status', $request->status);
            }

            return DataTables::of($invoices)
                ->addIndexColumn()
                ->addColumn('date', function ($invoice) {
                    return $invoice->date->format('d-M-Y');
                })
                ->addColumn('action', function ($invoice) {
                    return '
                <div class="flex justify-end space-x-2">
                    <a href="' . route('invoices.show', $invoice->id) . '" class="text-blue-500 hover:text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>
                    <a href="' . route('invoices.edit', $invoice->id) . '" class="text-yellow-500 hover:text-yellow-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    <a href="' . route('invoices.print', $invoice->id) . '" target="_blank" class="text-red-500 hover:text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                    </a>
                </div>
            ';
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('invoices.index');
    }

    public function data(Request $request)
    {
        try {
            $invoices = Invoice::query()
                ->with('customer')
                ->select(['invoices.*']);


            // // Filter by date range
            // if ($request->has('date_range') && !empty($request->date_range)) {
            //     $dates = explode(' - ', $request->date_range);
            //     $startDate = date('Y-m-d', strtotime($dates[0]));
            //     $endDate = date('Y-m-d', strtotime($dates[1]));

            //     $invoices->whereBetween('invoices.date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            // }

            // // Filter by type
            // if ($request->has('type') && !empty($request->type)) {
            //     $invoices->where('tipe', $request->type);
            // }

            // // Filter by status
            // if ($request->has('status') && !empty($request->status)) {
            //     $invoices->where('status', $request->status);
            // }

            return DataTables::of($invoices)
                ->addIndexColumn()
                ->addColumn('date', function ($invoice) {
                    return $invoice->date->format('d-M-Y');
                })
                ->addColumn('action', function ($invoice) {
                    return '
                <div class="flex justify-end space-x-2">
                    <a href="' . route('invoices.show', $invoice->id) . '" class="text-blue-500 hover:text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>
                    <a href="' . route('invoices.edit', $invoice->id) . '" class="text-yellow-500 hover:text-yellow-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    <a href="' . route('invoices.print', $invoice->id) . '" target="_blank" class="text-red-500 hover:text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                    </a>
                </div>
            ';
                })
                ->rawColumns(['action'])
                ->toJson();
        } catch (\Throwable $th) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Terjadi kesalahan saat memuat data'
            ]);
        }
    }

    public function create(Request $request)
    {
        $type = $request->query('type');
        $referenceId = $request->query('reference_id');

        $reference = null;
        $customer = null;

        if ($type && $referenceId) {
            if ($type === 'sales') {
                $reference = Sales::with(['customer', 'items.product'])->where('unique_id', $referenceId)->first();
            } else {
                $reference = JobOrder::with(['customerVehicle.customer', 'customerVehicle.vehicle', 'items.product'])
                    ->where('unique_id', $referenceId)
                    ->first();
            }

            if ($reference) {
                $customer = $type === 'sales'
                    ? $reference->customer
                    : $reference->customerVehicle->customer;
            }
        }

        return view('invoices.create', [
            'type' => $type,
            'reference' => $reference,
            'customer' => $customer,
            'salesList' => Sales::with('customer')->get(),
            'jobOrders' => JobOrder::with('customerVehicle.customer')->get()
        ]);
    }

    public function createFromSale(Sales $sale)
    {
        return view('invoices.createFrom', [
            'type' => 'sales',
            'reference' => $sale,
            'customers' => Customer::all()
        ]);
    }

    public function createFromService(JobOrder $jobOrder)
    {
        return view('invoices.createFrom', [
            'type' => 'services',
            'reference' => $jobOrder,
            'customers' => Customer::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe' => 'required|in:sales,services',
            'reference_id' => 'required',
            'customer_id' => 'required|exists:customers,id',
            'due_date' => 'nullable|date',
            'diskon_value' => 'nullable|numeric|min:0'
        ]);

        if ($validated['tipe'] === 'sales') {
            $sale = Sales::findOrFail($validated['reference_id']);
            $validated['subtotal'] = $sale->subtotal;
            $validated['total'] = $sale->total;
        } else {
            $jobOrder = JobOrder::findOrFail($validated['reference_id']);
            $validated['subtotal'] = $jobOrder->subtotal;
            $validated['total'] = $jobOrder->total;
        }

        $customer = Customer::find($validated['customer_id']);

        $invoice = Invoice::create([
            'tipe' => $validated['tipe'],
            'reference_id' => $validated['reference_id'],
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_address' => $customer->address,
            'subtotal' => $validated['subtotal'],
            'diskon_unit' => 'nominal',
            'diskon_value' => $validated['diskon_value'] ?? 0,
            'total' => $validated['total'],
            'date' => $validated['due_date'] ?? now()->addDays(7)
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice berhasil dibuat');
    }

    public function show(Invoice $invoice)
    {

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $reference = $invoice->reference;
        $customer = $invoice->customer;

        // dd($reference->orderItems);

        return view('invoices.edit', [
            'invoice' => $invoice,
            'reference' => $reference,
            'customer' => $customer,
            'type' => $invoice->tipe,
            'salesList' => $invoice->tipe === 'sales' ? Sales::with('customer')->get() : [],
            'jobOrders' => $invoice->tipe === 'services' ? JobOrder::with('customerVehicle.customer')->get() : []
        ]);
    }

    public function update(Request $request, Invoice $invoice)
    {

        $validated = $request->validate([
            'reference_id' => 'required',
            'customer_id' => 'required|exists:customers,id',
            'subtotal' => 'required|numeric',
            'diskon_unit' => 'nullable|in:percentage,nominal',
            'diskon_value' => 'nullable|numeric',
            'total' => 'required|numeric',
            'status' => 'required|in:paid,unpaid'
        ]);

        $customer = Customer::find($validated['customer_id']);

        $invoice->update([
            'reference_id' => $validated['reference_id'],
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_address' => $customer->address,
            'status' => $validated['status'],
            'subtotal' => $validated['subtotal'],
            'diskon_unit' => $validated['diskon_unit'],
            'diskon_value' => $validated['diskon_value'] ?? 0,
            'total' => $validated['total'],
        ]);

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', 'Invoice berhasil diperbarui');
    }

    public function print(Invoice $invoice)
    {
        $pdf = Pdf::loadView('invoices.print', compact('invoice'));
        return $pdf->stream('invoice-' . $invoice->unique_id . '.pdf');
    }
}
