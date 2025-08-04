<?php

use App\Models\Sales;
use App\Models\Product;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\JobOrder;
use Illuminate\Http\Request;
use App\Models\CustomerVehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerVehicleController;
use App\Http\Controllers\JobOrderController;
use App\Models\ServicePackage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/customers_vehicle/search', function (Request $request) {
    $query = $request->input('q');

    $query = CustomerVehicle::with(['customer', 'vehicle'])
        ->whereHas('customer', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->q . '%');
        })
        ->orWhereHas('vehicle', function ($q) use ($request) {
            $q->where('no_pol', 'like', '%' . $request->q . '%')
                ->orWhere('merk', 'like', '%' . $request->q . '%');
        })
        ->limit(10)
        ->get()
        ->map(function ($customer) {
            return [
                'id' => $customer->id,
                'text' => sprintf(
                    "%s - %s %s (%s)",
                    $customer->customer->name,
                    $customer->vehicle->merk,
                    $customer->vehicle->tipe,
                    $customer->vehicle->no_pol
                ),
            ];
        });



    return response()->json($query);
})->name('api.customers.search');

Route::get('/supplies/{supply}/items', function (App\Models\Supply $supply) {
    return $supply->items()->with(['product'])->get();
});

Route::get('/supplies/{supply}/products/{product}/order-items', function (App\Models\Supply $supply, App\Models\Product $product) {
    return $supply->jobOrder->orderItems()
        ->where('product_id', $product->id)
        ->get();
});

Route::get('/products/search', function (Request $request) {
    $query = $request->input('q');
    $tipe = $request->input('tipe') == 'barang' ? ['barang', 'part', 'oli', 'material'] : ['jasa'];

    $products = DB::table('products')->whereIn('tipe', $tipe)->where('name', 'like', "%$query%")
        ->limit(20)
        ->get()
        ->map(function ($product) {
            return [
                'id' => json_encode($product),
                'text' => $product->name,
                'stok' => $product->stok,
                'price' => $product->unit_price,
                'buying_price' => $product->buying_price,
                'tipe' => $product->tipe,
                'priceFormatted' => number_format($product->unit_price, 0, ',', '.'),
            ];
        });

    return response()->json($products);
})->name('api.product.search');

Route::post('/products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');

Route::get('/customer', function (Request $request) {

    $query = $request->q;

    $data = Customer::where('name', 'like', '%' . $query . '%')
        ->limit(20)
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => "{$item->name} - {$item->phone}",
            ];
        });

    return response()->json($data);
})->name('api.customer-vehicle.search');
Route::get('/package/{id}', function (Request $request) {
    $data = ServicePackage::with('items', 'items.product')->get()->first();

    return response()->json($data);
})->name('api.get_package');
Route::get('/vehicle', function (Request $request) {

    $query = $request->q;

    $data = Vehicle::where('merk', 'like', '%' . $query . '%')->orWhere('tipe', 'like', '%' . $query . '%')->orWhere('no_pol', 'like', '%' . $query . '%')
        ->limit(20)
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => "{$item->merk} - {$item->tipe} - {$item->no_pol}",
            ];
        });

    return response()->json($data);
})->name('api.customer-vehicle.search');
Route::get('/customers/search', function (Request $request) {
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
})->name('customers.search');
Route::get('/invoice-reference/search', function (Request $request) {
    $search = $request->get('q');
    $tipe = $request->get('tipe');

    if ($tipe == 'sales') {
        $customers = Sales::with(['customer', 'items', 'items.product'])->WhereHas('customer', function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->orWhere('unique_id', 'like', "%$search%")
            ->limit(10)
            ->get()
            ->map(function ($customer) {
                return [
                    'value' => json_encode($customer),
                    'text' => $customer->unique_id . '-' . $customer->customer->name,

                ];
            });

        return response()->json($customers);
    } else if ($tipe == 'services') {
        $jobOrders = JobOrder::with(['orderItems', 'orderItems.product', 'customerVehicle', 'customerVehicle.customer', 'customerVehicle.vehicle'])->whereHas('customerVehicle.customer',  function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->orWhere('unique_id', 'like', "%$search%")
            ->limit(10)
            ->get()
            ->map(function ($jobOrder) {
                return [
                    'value' => json_encode($jobOrder),
                    'text' => $jobOrder->unique_id . '-' . $jobOrder->customerVehicle->customer->name ?? '',
                ];
            });

        return response()->json($jobOrders);
    }
})->name('invoice-reference.search');
Route::get('/suppliers/search', [SupplierController::class, 'search']);
Route::get('/suppliers/{supplier}', [SupplierController::class, 'getSupplier']);
Route::post('/suppliers/quick-create', [SupplierController::class, 'quickCreate'])->name('suppliers.quickCreate');
Route::post('/products/quick-create', [ProductController::class, 'quickCreate'])->name('products.quick-create');
Route::get('/customer_vehicles/{id}/details', [CustomerVehicleController::class, 'getDetails']);
Route::get('/job_order/search', function (Request $request) {
    $search = $request->get('q');

    $jobOrder = JobOrder::when($search, function ($query) use ($search) {
        return $query->where('unique_id', 'like', "%$search%");
    })
        ->limit(10)
        ->get()
        ->map(function ($jobOrder) {
            return [
                'id' => $jobOrder->id,
                'text' => $jobOrder->unique_id,
            ];
        });

    return response()->json($jobOrder);
});
Route::get('/vehicle/search', function (Request $request) {
    $query = $request->input('q');


    $query = CustomerVehicle::with(['customer', 'vehicle'])
        ->whereHas('customer', function ($q) use ($request) {
            if ($request->customer_id) {
                $q->where('customer_id', '=', $request->customer_id);
            } else {
                $q->where('name', 'like', '%' . $request->q . '%');
            }
        })
        ->WhereHas('vehicle', function ($q) use ($request) {
            $q->where('no_pol', 'like', '%' . $request->q . '%')
                ->orWhere('tipe', 'like', '%' . $request->q . '%')
                ->orWhere('merk', 'like', '%' . $request->q . '%');
        })
        ->limit(10)
        ->get()
        ->map(function ($customer) {

            return [
                'id' => $customer->id,
                'text' => sprintf(
                    "%s %s (%s)",
                    $customer->vehicle->merk,
                    $customer->vehicle->tipe,
                    $customer->vehicle->no_pol
                ),
                'customer' => $customer->customer,
            ];
        });



    return response()->json($query);
});
