<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\OrderItem;
use App\Models\ReturnItem;
use App\Models\SupplyItem;
use App\Models\MovementItem;
use Illuminate\Http\Request;
use App\Models\CustomerVehicle;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ServicePackage;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class JobOrderController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $status = $request->status;


            $data = JobOrder::with(['customerVehicle.customer', 'customerVehicle.vehicle'])
                ->select('*');

            if ($startDate) {
                $data->when($startDate, function ($query) use ($startDate) {
                    $query->whereDate('created_at', '>=', $startDate);
                });
            }
            if ($endDate) {
                $data->when($endDate, function ($query) use ($endDate) {
                    $query->whereDate('created_at', '<=', $endDate);
                });
            }
            if ($status) {
                $data->when($status, function ($query) use ($status) {
                    $query->where('status', $status);
                });
            } else {
                $data->where('status', '!=', 'estimation');
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_name', function ($row) {
                    return $row->customerVehicle->customer->name;
                })
                ->addColumn('vehicle', function ($row) {
                    return $row->customerVehicle->vehicle->merk . ' - ' . $row->customerVehicle->vehicle->no_pol;
                })
                ->addColumn('formatted_total', function ($row) {
                    return 'Rp ' . number_format($row->total, 0, ',', '.');
                })
                ->addColumn('service_at', function ($row) {
                    return $row->service_at->format('d M Y H:i');
                })
                ->addColumn('status_badge', function ($row) {
                    $statusClass = [
                        'new' => 'bg-yellow-100 text-yellow-800',
                        'draft' => 'bg-gray-100 text-gray-800',
                        'progress' => 'bg-blue-100 text-blue-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800'
                    ];
                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full ' . $statusClass[$row->status] . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="flex justify-end gap-2">';
                    $btn .= '<a href="' . route('job-orders.show', $row->id) . '" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
                    $btn .= '</a>';
                    $btn .= '<a href="' . route('job-orders.edit', $row->id) . '" class="p-2 text-green-600 hover:bg-green-50 rounded-lg">';
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
                ->orderColumn('service_at', 'service_at $1')
                ->orderColumn('formatted_total', 'total $1')
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('job-orders.index');
    }

    public function create()
    {
        $customerVehicles = CustomerVehicle::with(['customer', 'vehicle'])->get();
        $products = Product::all();
        $packages = ServicePackage::all();

        return view('job-orders.create', compact('customerVehicles', 'products', 'packages'));
    }

    protected function validateRequest(Request $request)
    {
        $rules = [
            'service_at' => 'required|date',
            'package' => 'nullable',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required',
            'items.*.quantity' => 'required|numeric',
            'items.*.diskon_value' => 'nullable|numeric|min:0',
            'breakdowns' => 'nullable|array',
            'breakdowns.*.name' => 'required|string|max:255',
            'diskon_unit' => 'nullable|in:percentage,nominal',
            'diskon_value' => 'nullable|numeric|min:0',
            'total' => 'required|numeric',
            'notes' => 'nullable',
        ];

        // Validasi conditional
        if ($request->customer_vehicle_id == null) {
            $rules = array_merge($rules, [
                'customer_name' => 'required|string|max:255',
                'merk' => 'required|string|max:255',
                'tipe' => 'required|string|max:255',
                'no_pol' => 'required|string|max:20',
            ]);
        } else {
            $rules['customer_vehicle_id'] = 'required|exists:customer_vehicle,id';
        }

        return $request->validate($rules, [
            'breakdowns.*.name.required' => 'Kolom nama breakdown tidak boleh kosong.',
        ]);
    }

    public function store(Request $request)
    {



        $validated = $this->validateRequest($request);




        DB::transaction(function () use ($request) {

            if ($request->customer_vehicle_id) {
                $customerVehicle = CustomerVehicle::find($request->customer_vehicle_id);
            } else {
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]);

                $vehicle = Vehicle::create($request->only(['merk', 'tipe', 'no_pol']));

                $customerVehicle = CustomerVehicle::create(['customer_id' => $customer->id, 'vehicle_id' => $vehicle->id]);
            }

            $subtotal = $request->subtotal;
            $total = $request->total;

            $diskonUnit = 'nominal';
            $diskonValue = $request->total_diskon_item ?? 0;

            // dd()


            $jobOrder = JobOrder::create([
                'customer_vehicle_id' => $customerVehicle->id,
                'km' => $request->km,
                'service_at' => $request->service_at,
                'status' => 'draft',
                'subtotal' => $request->total_sparepart + $request->total_jasa,
                'diskon_unit' => $diskonUnit,
                'diskon_value' => $diskonValue,
                'total' => $total,
                'notes' => $request->notes,
            ]);

            // Simpan items
            foreach ($request->items as $item) {
                $data_item = json_decode($item['product_id']);


                if (is_object($data_item)) {
                    $product = Product::withTrashed()->find($data_item->id);
                } else {
                    $product = Product::withTrashed()->find($item['product_id']);
                }


                $subtotal = $product->unit_price * $item['quantity'];
                $potongan = ($item['diskon_value'] / 100) * $subtotal;

                if ($product->tipe == 'jasa') {
                    $subtotaljasa = 100000 * $item['quantity'];
                    $data_input = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => 0,
                        'total_price' => $subtotaljasa,
                        'diskon_value' => $item['diskon_value'] ?? 0,
                        'price_after_diskon' => $subtotaljasa - $potongan,
                    ];
                } else {
                    $data_input = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->unit_price,
                        'total_price' => $subtotal,
                        'diskon_value' => $item['diskon_value'] ?? 0,
                        'price_after_diskon' => $subtotal - $potongan,
                    ];
                }



                // dd($data_input);
                $order_item = $jobOrder->orderItems()->create($data_input);

                // Kurangi stok jika produk adalah sparepart
                // if ($product->tipe != 'jasa') {
                //     $product->decrement('stok', $item['quantity']);

                // MovementItem::create([
                //     'move' => 'out',
                //     'reference' => 'order_items',
                //     'reference_id' => $order_item->id,
                //     'product_id' => $product->id,
                //     'item_name' => $product->name, // pastikan ada 'name' di array
                //     'name' => 'supply',
                //     'item_description' => $product->description ?? null,
                //     'quantity' => 0,
                //     'buying_price' => $product->buying_price,
                //     'selling_price' => $product->unit_price,
                //     'total_price' => $order_item->total_price,
                //     'discount' => $potongan ?? 0,
                //     'grand_total' => $order_item->price_after_diskon,
                //     'created_by' => Auth::id(),
                //     'status' => 'pending',
                //     'est_quantity' => $order_item->quantity,
                //     'note' => null,
                // ]);
                // }
            }

            // Simpan breakdowns jika ada
            if ($request->breakdowns) {
                foreach ($request->breakdowns as $breakdown) {
                    $jobOrder->breakdowns()->create([
                        'name' => $breakdown['name']
                    ]);
                }
            }
        });
        return redirect()->route('job-orders.index')->with('success', 'Job Order berhasil dibuat');
    }

    public function show(JobOrder $jobOrder)
    {
        $jobOrder->load([
            'customerVehicle.customer',
            'customerVehicle.vehicle',
            'orderItems.product',
            'breakdowns',
            'invoice',
            'orderItems',
            'orderItems.product'
        ]);


        return view('job-orders.show', compact('jobOrder'));
    }

    public function edit(JobOrder $jobOrder)
    {
        $jobOrder->load(['orderItems.product', 'breakdowns', 'customerVehicle', 'customerVehicle.customer', 'customerVehicle.vehicle']);
        $customerVehicles = CustomerVehicle::with(['customer', 'vehicle'])->get();
        $products = Product::all();

        $total_sparepart = 0;
        $total_service = 0;

        $total_diskon = 0;

        foreach ($jobOrder->orderItems as $key => $value) {
            if ($value->product->tipe != 'jasa') {
                $total_sparepart += $value->total_price;
            } else {
                $total_service += $value->total_price;
            }

            $diskon_nominal = $value->total_price * ($value->diskon_value / 100);
            $total_diskon += $diskon_nominal;
        }

        return view('job-orders.edit', compact('jobOrder', 'customerVehicles', 'products', 'total_sparepart', 'total_service', 'total_diskon'));
    }

    public function update(Request $request, JobOrder $jobOrder)
    {

        // dd($request->all());
        $validated = $this->validateRequest($request);

        DB::transaction(function () use ($request, $jobOrder) {

            if ($request->customer_vehicle_id) {
                $customerVehicle = CustomerVehicle::find($request->customer_vehicle_id);
            } else {
                $customer = Customer::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]);

                $vehicle = Vehicle::create($request->only(['merk', 'tipe', 'no_pol']));

                $customerVehicle = CustomerVehicle::create(['customer_id' => $customer->id, 'vehicle_id' => $vehicle->id]);
            }

            $subtotal = $request->subtotal;
            $total = $request->total;

            $diskonUnit = 'nominal';
            $diskonValue = $request->total_diskon_item ?? 0;

            $jo_data = [
                'customer_vehicle_id' => $customerVehicle->id,
                'km' => $request->km,
                'service_at' => $request->service_at,
                'status' => $jobOrder->status,
                'subtotal' => $request->total_sparepart + $request->total_jasa,
                'diskon_unit' => $diskonUnit,
                'diskon_value' => $diskonValue,
                'total' => $total,
                'notes' => $request->notes,
            ];

            // dd($jo_data);
            $jobOrder->update($jo_data);

            // $jobOrder = JobOrder::find($jobOrder->id);


            foreach ($request->items as $item) {
                if (str_starts_with($item['id'] ?? '', 'delete_')) {
                    // Delete marked items
                    $id = str_replace('delete_', '', $item['id']);
                    OrderItem::find($id)->delete();
                } elseif (empty($item['id'])) {
                    $data_item = json_decode($item['product_id']);
                    $product = Product::find($data_item->id);

                    $subtotal = $product->unit_price * $item['quantity'];
                    $potongan = ($item['diskon_value'] / 100) * $subtotal;


                    if ($product->tipe == 'jasa') {
                        $subtotaljasa = 100000 * $item['quantity'];
                        $data_input = [
                            'product_id' => $data_item->id,
                            'quantity' => $item['quantity'],
                            'unit_price' => 0,
                            'total_price' => $subtotaljasa,
                            'diskon_value' => $item['diskon_value'] ?? 0,
                            'price_after_diskon' => $subtotaljasa - $potongan,
                        ];
                    } else {
                        $data_input = [
                            'product_id' => $product->id,
                            'quantity' => $item['quantity'],
                            'unit_price' => $product->unit_price,
                            'total_price' => $subtotal,
                            'diskon_value' => $item['diskon_value'] ?? 0,
                            'price_after_diskon' => $subtotal - $potongan,
                        ];
                    }

                    // dd($data_input);
                    $jobOrder->orderItems()->create($data_input);
                } else {
                    $orderItem = OrderItem::find($item['id']);

                    if ($orderItem->product->tipe == 'jasa') {
                        $subtotal = 100000 * $item['quantity'];
                    } else {
                        $subtotal = $orderItem->unit_price * $item['quantity'];
                    }

                    $potongan = ($item['diskon_value'] / 100) * $subtotal;

                    $orderItem->quantity = $item['quantity'];
                    $orderItem->total_price = $subtotal;
                    $orderItem->diskon_value = $item['diskon_value'];
                    $orderItem->price_after_diskon = $subtotal - $potongan;

                    $orderItem->save();
                }
            }

            // Simpan items
            // foreach (array_merge($request->service, $request->items) as $item) {

            //     if (isset($item['id'])) {
            //         $data = OrderItem::find($item['id']);
            //         $data->quantity = $item['quantity'];
            //         $data->total_price = $data->unit_price * $item['quantity'];
            //         $data->save();
            //     } else {
            //         $data_item = json_decode($item['product_id']);

            //         $product = Product::find($data_item->value);

            //         $jobOrder->orderItems()->create([
            //             'product_id' => $data_item->value,
            //             'quantity' => $item['quantity'],
            //             'unit_price' => $product->unit_price,
            //             'total_price' => $product->unit_price * $item['quantity']
            //         ]);

            //         // Kurangi stok jika produk adalah sparepart
            //         if ($product->tipe === 'barang') {
            //             $product->decrement('stok', $item['quantity']);
            //         }
            //     }
            // }

            if ($request->has('breakdowns')) {
                foreach ($request->breakdowns as $breakdown) {
                    if (str_starts_with($breakdown['id'] ?? '', 'delete_')) {
                        // Delete marked breakdowns
                        $id = str_replace('delete_', '', $breakdown['id']);
                        $jobOrder->breakdowns()->where('id', $id)->delete();
                    } elseif (empty($breakdown['id'])) {
                        // Create new breakdowns
                        $jobOrder->breakdowns()->create([
                            'name' => $breakdown['name'],
                            // other breakdown fields
                        ]);
                    } else {
                        // Update existing breakdowns
                        $jobOrder->breakdowns()->where('id', $breakdown['id'])->update([
                            'name' => $breakdown['name'],
                            // other breakdown fields
                        ]);
                    }
                }
            }
        });
        return redirect()->route('job-orders.index')->with('success', 'Job Order berhasil dibuat');
    }

    public function destroy(JobOrder $jobOrder)
    {


        // Kembalikan stok jika ada (untuk sparepart)
        foreach ($jobOrder->orderItems as $item) {
            if ($item->product->tipe === 'barang') {
                $item->product->increment('stok', $item->quantity);
            }
        }

        $jobOrder->orderItems()->delete();
        $jobOrder->breakdowns()->delete();
        $jobOrder->delete();

        return redirect()->route('job-orders.index')
            ->with('success', 'Job Order berhasil dihapus');
    }

    public function deleteProduct($id, $joID)
    {
        OrderItem::destroy($id);
        return redirect()->route('job-orders.edit', $joID);
    }

    public function complete($id)
    {
        $jobOrder = JobOrder::findOrFail($id);

        if ($jobOrder->status === 'completed') {
            return redirect()->back()->with('error', 'Job Order sudah diselesaikan sebelumnya.');
        }

        $jobOrder->status = 'completed';
        $jobOrder->save();

        return redirect()->route('job-orders.show', $jobOrder->id)->with('success', 'Job Order berhasil diselesaikan.');
    }

    public function updateStatus($id, $status)
    {

        $jobOrder = JobOrder::findOrFail($id);
        $jobOrder->status = $status;
        $jobOrder->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }




    private function calculateTotal($subtotal, $diskonUnit, $diskonValue)
    {
        if (!$diskonUnit || !$diskonValue) {
            return $subtotal;
        }

        if ($diskonUnit === 'percentage') {
            return $subtotal - ($subtotal * $diskonValue / 100);
        }

        return $subtotal - $diskonValue;
    }

    public function deleteItems(Request $request, JobOrder $jobOrder)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'exists:order_items,id,order_id,' . $jobOrder->id
        ]);

        DB::beginTransaction();
        try {
            $deletedItems = OrderItem::whereIn('id', $request->items)->get();

            // Check if any completed supplies exist for these items
            foreach ($deletedItems as $item) {
                $supplyItems = SupplyItem::where('product_id', $item->product_id)
                    ->whereHas('supply', function ($q) use ($jobOrder) {
                        $q->where('job_order_id', $jobOrder->id)
                            ->where('status', 'completed');
                    })->get();

                foreach ($supplyItems as $supplyItem) {
                    // Create return item
                    ReturnItem::create([
                        'supply_id' => $supplyItem->supply_id,
                        'product_id' => $item->product_id,
                        'order_item_id' => $item->id,
                        'quantity' => $supplyItem->quantity_fulfilled,
                        'unit_price' => $supplyItem->unit_price,
                        'reason' => 'Item dihapus dari job order',
                        'status' => 'approved'
                    ]);

                    // Update product stock (optional)
                    $product = $item->product;
                    $product->stok += $supplyItem->quantity_fulfilled;
                    $product->save();
                }
            }

            // Delete selected items
            OrderItem::whereIn('id', $request->items)->delete();

            // Recalculate all totals
            $jobOrder->recalculateTotals();

            DB::commit();
            return redirect()->back()->with('success', 'Item terpilih berhasil dihapus. Retur telah dibuat untuk supply yang completed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus item: ' . $e->getMessage());
        }
    }

    public function deleteBreakdowns(Request $request, $id)
    {
        $jobOrder = JobOrder::findOrFail($id);

        // Validate request
        $request->validate([
            'breakdowns' => 'required|array',
            'breakdowns.*' => 'exists:breakdowns,id'
        ]);

        // Delete selected breakdowns
        $jobOrder->breakdowns()->whereIn('id', $request->breakdowns)->delete();

        return redirect()->back()
            ->with('success', 'Breakdown terpilih berhasil dihapus');
    }
}
