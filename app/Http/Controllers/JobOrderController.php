<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Breakdown;
use App\Models\Customer;
use App\Models\CustomerVehicle;
use App\Models\JobOrder;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ReturnItem;
use App\Models\ServicePackage;
use App\Models\Supply;
use App\Models\SupplyItem;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class JobOrderController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $status = $request->status;


            $data = JobOrder::with(['customerVehicle.customer', 'customerVehicle.vehicle'])
                ->select('*')->orderBy('created_at', 'desc');

            if ($startDate) {
                $data->when($startDate, function ($query) use ($startDate) {
                    $query->whereDate('service_at', '>=', $startDate);
                });
            }
            if ($endDate) {
                $data->when($endDate, function ($query) use ($endDate) {
                    $query->whereDate('service_at', '<=', $endDate);
                });
            }
            if ($status) {
                $data->when($status, function ($query) use ($status) {
                    $query->where('status', $status);
                });
            } else {
                $data->where('status', '!=', 'estimation');
            }

            foreach ($request->order as $key => $value) {
                if ($value['column'] == 0 || $value['column'] == 4) {
                    $data->orderBy('service_at', $value['dir']);
                } else {
                    $data->orderBy('total', $value['dir']);
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_name', function ($row) {
                    return $row->customerVehicle->customer->name;
                })
                ->addColumn('vehicle', function ($row) {
                    return $row->customerVehicle->vehicle->merk . ' - ' . $row->customerVehicle->vehicle->no_pol;
                })
                ->addColumn('subtotal', function ($row) {
                    return 'Rp ' . number_format($row->subtotal + $row->fee_amount, 2, ',', '.');
                })
                ->addColumn('ppn_amount', function ($row) {
                    return 'Rp ' . number_format($row->ppn_amount, 2, ',', '.');
                })
                ->addColumn('formatted_total', function ($row) {
                    return 'Rp ' . number_format(($row->subtotal + $row->fee_amount) + $row->ppn_amount, 2, ',', '.');
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
                        'cancelled' => 'bg-red-100 text-red-800',
                        'estimation' => 'bg-yellow-100 text-yellow-800'
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

    public function getSummary(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $totalJobOrders = JobOrder::where('status', '!=', 'estimation')
            ->whereBetween('service_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        $totalCompleted = JobOrder::where('status', 'completed')
            ->whereBetween('service_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get()
            ->sum(function ($job) {
                return ($job->subtotal ?? 0) + ($job->fee_amount ?? 0) + ($job->ppn_amount ?? 0);
            });

        $totalProgress = JobOrder::where('status', 'progress')
            ->whereBetween('service_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get()
            ->sum(function ($job) {
                return ($job->subtotal ?? 0) + ($job->fee_amount ?? 0) + ($job->ppn_amount ?? 0);
            });

        $totalCancelled = JobOrder::where('status', 'cancelled')
            ->whereBetween('service_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get()
            ->sum(function ($job) {
                return ($job->subtotal ?? 0) + ($job->fee_amount ?? 0) + ($job->ppn_amount ?? 0);
            });

        return response()->json([
            'totalJobOrders' => $totalJobOrders,
            'totalCompleted' => $totalCompleted,
            'totalProgress' => $totalProgress,
            'totalCancelled' => $totalCancelled,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function report(Request $request)
    {
        // Ambil parameter filter dengan default hari ini
        $startDate = $request->input('start_date', Carbon::now()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $status = $request->status;

        // ===== HITUNG SUMMARY (di luar AJAX) =====
        $summaryQuery = JobOrder::where('status', '!=', 'estimation')
            ->whereBetween('service_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // Total jumlah job order
        $totalJobOrders = $summaryQuery->count();

        // Total nominal untuk setiap status
        $totalCompleted = JobOrder::where('status', 'completed')
            ->whereBetween('service_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get()
            ->sum(function ($job) {
                return ($job->subtotal ?? 0) + ($job->fee_amount ?? 0) + ($job->ppn_amount ?? 0);
            });

        $totalProgress = JobOrder::where('status', 'progress')
            ->whereBetween('service_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get()
            ->sum(function ($job) {
                return ($job->subtotal ?? 0) + ($job->fee_amount ?? 0) + ($job->ppn_amount ?? 0);
            });

        $totalCancelled = JobOrder::where('status', 'cancelled')
            ->whereBetween('service_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get()
            ->sum(function ($job) {
                return ($job->subtotal ?? 0) + ($job->fee_amount ?? 0) + ($job->ppn_amount ?? 0);
            });

        // ===== DATA TABLES (AJAX) =====
        if ($request->ajax()) {
            $data = JobOrder::with(['customerVehicle.customer', 'customerVehicle.vehicle'])
                ->select('*')->orderBy('created_at', 'desc');

            if ($startDate && $endDate) {
                $data->whereBetween('service_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }

            if ($status) {
                $data->where('status', $status);
            } else {
                $data->where('status', '!=', 'estimation');
            }

            foreach ($request->order as $key => $value) {
                if ($value['column'] == 0 || $value['column'] == 4) {
                    $data->orderBy('service_at', $value['dir']);
                } else {
                    $data->orderBy('total', $value['dir']);
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_name', function ($row) {
                    return $row->customerVehicle->customer->name;
                })
                ->addColumn('vehicle', function ($row) {
                    return $row->customerVehicle->vehicle->merk . ' - ' . $row->customerVehicle->vehicle->no_pol;
                })
                ->addColumn('subtotal', function ($row) {
                    return 'Rp ' . number_format($row->subtotal + $row->fee_amount, 2, ',', '.');
                })
                ->addColumn('fee_amount', function ($row) {
                    return 'Rp ' . number_format($row->fee_amount, 2, ',', '.');
                })
                ->addColumn('ppn_amount', function ($row) {
                    return 'Rp ' . number_format($row->ppn_amount, 2, ',', '.');
                })
                ->addColumn('formatted_total', function ($row) {
                    return 'Rp ' . number_format(($row->subtotal + $row->fee_amount) + $row->ppn_amount, 2, ',', '.');
                })
                ->addColumn('net_profit', function ($row) {
                    return 'Rp ' . number_format($row->subtotal, 2, ',', '.');
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

        // ===== RETURN VIEW dengan data summary =====
        return view('job-orders.report', compact(
            'totalJobOrders',
            'totalCompleted',
            'totalProgress',
            'totalCancelled',
            'startDate',
            'endDate'
        ));
    }

    public function create(Request $request)
    {

        $packages = ServicePackage::all();
        $status = $request->status;
        $title = $request->status == 'estimation' ? "Buat Estimasi Baru" : "Buat Work Order Baru";
        return view('job-orders.create', compact('packages', 'status', 'title'));
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



        // dd($request->all());
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

                $customerVehicle = CustomerVehicle::where(['customer_id' => $customer->id, 'vehicle_id' => $vehicle->id])->get()->first();
            }


            $subtotal = $request->subtotal;
            $total = $request->total;
            $grantTotal = $request->grand_total;
            $ppn_value = $request->ppn;
            $ppn_amount = $request->ppn_amount;

            $diskonUnit = 'nominal';
            $diskonValue = $request->total_diskon_item ?? 0;

            $total_internal = $request->total_sparepart + $request->total_jasa - $request->total_fee_hidden;

            // dd()


            $jobOrder = JobOrder::create([
                'customer_vehicle_id' => $customerVehicle->id,
                'km' => $request->km,
                'service_at' => $request->service_at,
                'status' => $request->status,
                'subtotal' => $total_internal,
                'diskon_unit' => $diskonUnit,
                'diskon_value' => $diskonValue,
                'ppn_value' => $ppn_value,
                'ppn_amount' => $ppn_amount,
                'fee_amount' => $request->total_fee_hidden,
                'total' => $total_internal,
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
                $subtotalMarkup = $item['subtotal'];
                $potongan = ($item['diskon_value'] / 100) * $subtotal;
                $fee_value = $item['fee_value'];
                $fee_amount = $item['fee_amount'];
                $total = $item['total'];
                if ($product->tipe == 'jasa') {
                    $subtotaljasa = 100000 * $item['quantity'];
                    $data_input = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => 0,
                        'total_price' => $subtotalMarkup,
                        'markup_price' => $item['markup_price'],
                        'diskon_value' => $item['diskon_value'] ?? 0,
                        'fee_value' => $fee_value ?? 0,
                        'fee_amount' => $fee_amount ?? 0,
                        'price_after_diskon' => $subtotaljasa - $potongan,
                    ];
                } else {
                    $data_input = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->unit_price,
                        'total_price' => $subtotalMarkup,
                        'markup_price' => $item['markup_price'],
                        'diskon_value' => $item['diskon_value'] ?? 0,
                        'fee_value' => $fee_value ?? 0,
                        'fee_amount' => $fee_amount ?? 0,
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
        if($request->status == 'estimation'){
            return redirect()->route('estimation.index')->with('success', 'Job Order berhasil dibuat');
        }else{
            return redirect()->route('job-orders.index')->with('success', 'Job Order berhasil dibuat');
        }
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

        // dd($jobOrder->statuses());


        return view('job-orders.show', compact('jobOrder'));
    }
    public function showInternal(JobOrder $jobOrder)
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

        // dd($jobOrder->statuses());


        return view('job-orders.show_internal', compact('jobOrder'));
    }
    public function print(string $id)
    {


        $jobOrder = JobOrder::with('orderItems', 'orderItems.product', 'invoice', 'breakdowns', 'orderItems.product', 'customerVehicle', 'customerVehicle.customer', 'customerVehicle.vehicle')->find($id);

        $data['unique_id'] = $jobOrder->unique_id;
        

        if($jobOrder->status == 'estimation'){
            $data['type'] = 'ESTIMASI';
            }else{
            $data['type'] = 'WORK ORDER';
        }

        $data['tanggal'] = $jobOrder->service_at->format('d M Y H:i');
        $data['customer_name'] = $jobOrder->customerVehicle->customer->name;
        return view('job-orders.print', compact('jobOrder', 'data'));
    }

    public function edit(JobOrder $jobOrder, $status = null)
    {
        
        
        $jobOrder->load(['orderItems.product', 'breakdowns', 'customerVehicle', 'customerVehicle.customer', 'customerVehicle.vehicle']);
        $customerVehicles = CustomerVehicle::with(['customer', 'vehicle'])->get();
        $products = Product::all();

        // dd($jobOrder);

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

        return view('job-orders.edit', compact('jobOrder', 'customerVehicles', 'products', 'total_sparepart', 'total_service', 'total_diskon', 'status'));
    }

    public function update(Request $request, JobOrder $jobOrder)
    {
        $validated = $this->validateRequest($request);

        $redirectJobOrder = null;

        DB::transaction(function () use ($request, $jobOrder, &$redirectJobOrder) {
            
            // CEK: Apakah status berubah dari 'estimation' ke 'new'?
            $isEstimationToNew = ($jobOrder->status == 'estimation' && $request->status == 'new');

            // ==========================================
            // 1. HANDLE CUSTOMER & VEHICLE
            // ==========================================
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

                $customerVehicle = CustomerVehicle::create([
                    'customer_id' => $customer->id, 
                    'vehicle_id' => $vehicle->id
                ]);
            }

            // ==========================================
            // 2. PREPARE DATA JOB ORDER
            // ==========================================
            $subtotal = $request->subtotal;
            $total = $request->total;
            $grantTotal = $request->grand_total;

            $diskonUnit = 'nominal';
            $diskonValue = $request->total_diskon_item ?? 0;
            $total_internal = $request->total_sparepart + $request->total_jasa - $request->total_fee_hidden;

            $ppn_value = $request->ppn;
            $ppn_amount = $request->ppn_amount;

            $jo_data = [
                'customer_vehicle_id' => $customerVehicle->id,
                'km' => $request->km,
                'service_at' => $request->service_at,
                'status' => $request->status,
                'subtotal' => $total_internal,
                'diskon_unit' => $diskonUnit,
                'diskon_value' => $diskonValue,
                'ppn_value' => $ppn_value,
                'ppn_amount' => $ppn_amount,
                'total' => $total_internal,
                'fee_amount' => $request->total_fee_hidden,
                'notes' => $request->notes,
            ];

            // ==========================================
            // 3. TENTUKAN: DUPLICATE ATAU UPDATE?
            // ==========================================
            if ($isEstimationToNew) {
                // ==========================================
                // 3A. DUPLICATE: Buat JobOrder Baru
                // ==========================================
                
                // Hapus 'status' dari $jo_data karena akan di-set manual
                $jo_data['status'] = 'new';
                
                // Buat JobOrder baru (duplicate)
                $newJobOrder = JobOrder::create($jo_data);

                
                
                // Generate unique_id baru dengan prefix WO
                $now = now();
                $tanggal = $now->format('d');
                $bulan = $now->format('m');
                $tahun = $now->format('y');
                $prefix = 'WO';
                
                // Cari WO terakhir untuk nomor urut
                $latestWO = JobOrder::whereYear('created_at', $now->year)
                    ->where('status', '!=', 'estimation')
                    ->where('unique_id', 'like', "WO/%/%/{$tahun}/%")
                    ->orderByDesc('created_at')
                    ->withTrashed()
                    ->first();
                
                if ($latestWO) {
                    $parts = explode('/', $latestWO->unique_id);
                    $lastUrut = (int) ($parts[4] ?? 0);
                } else {
                    $lastUrut = 0;
                }
                
                $nextUrut = $lastUrut + 1;
                $nomorUrut = str_pad($nextUrut, 4, '0', STR_PAD_LEFT);
                $generated = "{$prefix}/{$tanggal}/{$bulan}/{$tahun}/{$nomorUrut}";
                
                // Update unique_id di newJobOrder
                $newJobOrder->unique_id = $generated;
                $newJobOrder->save();
                
                // ==========================================
                // 3B. DUPLICATE: Order Items
                // ==========================================
                foreach ($request->items as $item) {
                    if (str_starts_with($item['id'] ?? '', 'delete_')) {
                        // Skip deleted items (tidak di-copy ke data baru)
                        continue;
                    } elseif (empty($item['id'])) {
                        // Item baru - create langsung
                        $data_item = json_decode($item['product_id']);
                        $product = null;
                        if (gettype($data_item) == 'object') {
                            $product = Product::find($data_item->id);
                        } else {
                            $product = Product::find($data_item);
                        }

                        $subtotal = $product->unit_price * $item['quantity'];
                        $subtotalMarkup = $item['subtotal'];
                        $potongan = ($item['diskon_value'] / 100) * $subtotal;
                        $fee_value = $item['fee_value'];
                        $fee_amount = $item['fee_amount'];
                        $total = $item['total'];
                        
                        if ($product->tipe == 'jasa') {
                            $subtotaljasa = 100000 * $item['quantity'];
                            $data_input = [
                                'product_id' => $product->id,
                                'quantity' => $item['quantity'],
                                'unit_price' => 0,
                                'total_price' => $subtotalMarkup,
                                'markup_price' => $item['markup_price'],
                                'diskon_value' => $item['diskon_value'] ?? 0,
                                'fee_value' => $fee_value ?? 0,
                                'fee_amount' => $fee_amount ?? 0,
                                'price_after_diskon' => $subtotaljasa - $potongan,
                            ];
                        } else {
                            $data_input = [
                                'product_id' => $product->id,
                                'quantity' => $item['quantity'],
                                'unit_price' => $product->unit_price,
                                'total_price' => $subtotalMarkup,
                                'markup_price' => $item['markup_price'],
                                'diskon_value' => $item['diskon_value'] ?? 0,
                                'fee_value' => $fee_value ?? 0,
                                'fee_amount' => $fee_amount ?? 0,
                                'price_after_diskon' => $subtotal - $potongan,
                            ];
                        }

                        // Create item di newJobOrder
                        $newOrderItem = $newJobOrder->orderItems()->create($data_input);

                        // Handle Supply
                        $findSupply = Supply::where('job_order_id', $newJobOrder->id)->first();
                        if ($findSupply) {
                            $findSupply->items()->create([
                                'product_id' => $newOrderItem->product_id,
                                'item_id' => $newOrderItem->id,
                                'quantity_requested' => $newOrderItem->quantity,
                                'quantity_fulfilled' => 0,
                                'unit_price' => $newOrderItem->unit_price,
                                'total_price' => $newOrderItem->total_price,
                                'status' => 'pending',
                            ]);
                        }
                    } else {
                        // Item existing - duplicate ke newJobOrder
                        $oldOrderItem = OrderItem::find($item['id']);
                        if ($oldOrderItem) {
                            // Hitung ulang berdasarkan data dari request
                            $product = $oldOrderItem->product;
                            
                            if ($product->tipe == 'jasa') {
                                $subtotal = 100000 * $item['quantity'];
                            } else {
                                $subtotal = $oldOrderItem->unit_price * $item['quantity'];
                            }
                            
                            $potongan = ($item['diskon_value'] / 100) * $subtotal;
                            $subtotalMarkup = $item['subtotal'];
                            $fee_value = $item['fee_value'];
                            $fee_amount = $item['fee_amount'];
                            
                            $data_input = [
                                'product_id' => $oldOrderItem->product_id,
                                'quantity' => $item['quantity'],
                                'unit_price' => $oldOrderItem->unit_price,
                                'total_price' => $subtotalMarkup,
                                'markup_price' => $item['markup_price'],
                                'diskon_value' => $item['diskon_value'] ?? 0,
                                'fee_value' => $fee_value ?? 0,
                                'fee_amount' => $fee_amount ?? 0,
                                'price_after_diskon' => $subtotal - $potongan,
                            ];
                            
                            $newOrderItem = $newJobOrder->orderItems()->create($data_input);
                            
                            // Handle Supply untuk item yang diduplicate
                            $findSupply = Supply::where('job_order_id', $newJobOrder->id)->first();
                            if ($findSupply) {
                                $findSupply->items()->create([
                                    'product_id' => $newOrderItem->product_id,
                                    'item_id' => $newOrderItem->id,
                                    'quantity_requested' => $newOrderItem->quantity,
                                    'quantity_fulfilled' => 0,
                                    'unit_price' => $newOrderItem->unit_price,
                                    'total_price' => $newOrderItem->total_price,
                                    'status' => 'pending',
                                ]);
                            }
                        }
                    }
                }

                // ==========================================
                // 3C. DUPLICATE: Breakdowns
                // ==========================================
                if ($request->has('breakdowns')) {
                    foreach ($request->breakdowns as $breakdown) {
                        if (str_starts_with($breakdown['id'] ?? '', 'delete_')) {
                            // Skip deleted breakdowns
                            continue;
                        } elseif (empty($breakdown['id'])) {
                            // Breakdown baru - create langsung
                            $newJobOrder->breakdowns()->create([
                                'name' => $breakdown['name'],
                            ]);
                        } else {
                            // Breakdown existing - duplicate ke newJobOrder
                            $oldBreakdown = Breakdown::find($breakdown['id']);
                            if ($oldBreakdown) {
                                $newJobOrder->breakdowns()->create([
                                    'name' => $breakdown['name'],
                                    // tambahkan field lain jika ada
                                ]);
                            }
                        }
                    }
                }

                // ==========================================
                // 3D. DATA LAMA TETAP DENGAN STATUS 'estimation'
                // ==========================================
                // Tidak ada perubahan pada $jobOrder (data lama tetap estimation)
                // JobOrder lama tetap dengan status 'estimation' dan unique_id EST

                $redirectJobOrder = $newJobOrder; // Redirect ke data baru

            } else {
                // ==========================================
                // 3E. UPDATE: Update JobOrder seperti biasa
                // ==========================================
                $jobOrder->update($jo_data);

                // ==========================================
                // 3F. UPDATE: Order Items
                // ==========================================
                foreach ($request->items as $item) {
                    if (str_starts_with($item['id'] ?? '', 'delete_')) {
                        $id = str_replace('delete_', '', $item['id']);
                        OrderItem::find($id)->delete();
                    } elseif (empty($item['id'])) {
                        $data_item = json_decode($item['product_id']);
                        $product = null;
                        if (gettype($data_item) == 'object') {
                            $product = Product::find($data_item->id);
                        } else {
                            $product = Product::find($data_item);
                        }

                        $subtotal = $product->unit_price * $item['quantity'];
                        $subtotalMarkup = $item['subtotal'];
                        $potongan = ($item['diskon_value'] / 100) * $subtotal;
                        $fee_value = $item['fee_value'];
                        $fee_amount = $item['fee_amount'];
                        $total = $item['total'];
                        
                        if ($product->tipe == 'jasa') {
                            $subtotaljasa = 100000 * $item['quantity'];
                            $data_input = [
                                'product_id' => $product->id,
                                'quantity' => $item['quantity'],
                                'unit_price' => 0,
                                'total_price' => $subtotalMarkup,
                                'markup_price' => $item['markup_price'],
                                'diskon_value' => $item['diskon_value'] ?? 0,
                                'fee_value' => $fee_value ?? 0,
                                'fee_amount' => $fee_amount ?? 0,
                                'price_after_diskon' => $subtotaljasa - $potongan,
                            ];
                        } else {
                            $data_input = [
                                'product_id' => $product->id,
                                'quantity' => $item['quantity'],
                                'unit_price' => $product->unit_price,
                                'total_price' => $subtotalMarkup,
                                'markup_price' => $item['markup_price'],
                                'diskon_value' => $item['diskon_value'] ?? 0,
                                'fee_value' => $fee_value ?? 0,
                                'fee_amount' => $fee_amount ?? 0,
                                'price_after_diskon' => $subtotal - $potongan,
                            ];
                        }

                        $jobOrderItem = $jobOrder->orderItems()->create($data_input);

                        $findSupply = Supply::where('job_order_id', $jobOrder->id)->first();
                        if ($findSupply) {
                            $findSupply->items()->create([
                                'product_id' => $jobOrderItem->product_id,
                                'item_id' => $jobOrderItem->id,
                                'quantity_requested' => $jobOrderItem->quantity,
                                'quantity_fulfilled' => 0,
                                'unit_price' => $jobOrderItem->unit_price,
                                'total_price' => $jobOrderItem->total_price,
                                'status' => 'pending',
                            ]);
                        }
                    } else {
                        $orderItem = OrderItem::find($item['id']);
                        if ($orderItem) {
                            if ($orderItem->product->tipe == 'jasa') {
                                $subtotal = 100000 * $item['quantity'];
                            } else {
                                $subtotal = $orderItem->unit_price * $item['quantity'];
                            }

                            $potongan = ($item['diskon_value'] / 100) * $subtotal;
                            $subtotalMarkup = $item['subtotal'];
                            $fee_value = $item['fee_value'];
                            $fee_amount = $item['fee_amount'];

                            $orderItem->quantity = $item['quantity'];
                            $orderItem->total_price = $subtotalMarkup;
                            $orderItem->markup_price = $item['markup_price'];
                            $orderItem->diskon_value = $item['diskon_value'];
                            $orderItem->fee_value = $fee_value;
                            $orderItem->fee_amount = $fee_amount;
                            $orderItem->price_after_diskon = $subtotal - $potongan;
                            $orderItem->save();
                        }
                    }
                }

                // ==========================================
                // 3G. UPDATE: Breakdowns
                // ==========================================
                if ($request->has('breakdowns')) {
                    foreach ($request->breakdowns as $breakdown) {
                        if (str_starts_with($breakdown['id'] ?? '', 'delete_')) {
                            $id = str_replace('delete_', '', $breakdown['id']);
                            $jobOrder->breakdowns()->where('id', $id)->delete();
                        } elseif (empty($breakdown['id'])) {
                            $jobOrder->breakdowns()->create([
                                'name' => $breakdown['name'],
                            ]);
                        } else {
                            $jobOrder->breakdowns()->where('id', $breakdown['id'])->update([
                                'name' => $breakdown['name'],
                            ]);
                        }
                    }
                }

                $redirectJobOrder = $jobOrder; // Redirect ke data yang diupdate
            }

        });

        // Redirect ke show dengan data yang sesuai (baru atau yang diupdate)
        return redirect()->route('job-orders.show', $redirectJobOrder?->id)
            ->with('success', 'Job Order berhasil di ubah');
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
