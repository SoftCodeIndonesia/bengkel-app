<?php

use App\Models\Customer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\JobOrderController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BreakdownController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\MovementItemController;
use App\Http\Controllers\CustomerVehicleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customers
    Route::resource('customers', CustomerController::class);


    // Vehicles
    Route::resource('vehicles', VehicleController::class);
    Route::resource('vehicles', VehicleController::class);

    Route::resource('services', ServiceController::class);

    // Customer Vehicles (Pivot)
    // Tambahkan route untuk create customer vehicle
    Route::get('/customer-vehicles/create', [CustomerVehicleController::class, 'create'])
        ->name('customer-vehicles.create');
    Route::post('/customer-vehicles', [CustomerVehicleController::class, 'store'])
        ->name('customer-vehicles.store');
    Route::delete('/customer-vehicles', [CustomerVehicleController::class, 'destroy'])
        ->name('customer-vehicles.destroy');

    Route::post('/follow-ups', [FollowUpController::class, 'store'])->name('follow-ups.store');

    // Products
    Route::resource('products', ProductController::class);

    // Route::get('info-stok', [ProductController::class, 'info'])->name('info-stok');
    // Route::get('info-detail/{id}', [ProductController::class, 'infoDetail'])->name('info-detail');

    // Job Orders
    Route::resource('job-orders', JobOrderController::class);

    // Job Orders
    Route::get('/job-orders/delete/{id}/{joID}', [JobOrderController::class, 'deleteProduct'])->name('job-orders.deleteProduct');

    // Custom Job Order Routes
    Route::patch('/job-orders/{job_order}/cancel', [JobOrderController::class, 'cancel'])
        ->name('job-orders.cancel');
    Route::patch('/job-orders/{job_order}/complete', [JobOrderController::class, 'complete'])
        ->name('job-orders.complete');
    Route::put('/job-orders/{id}/complete', [JobOrderController::class, 'complete'])->name('job-orders.complete');
    Route::get('/job-orders/{id}/update-status/{status}', [JobOrderController::class, 'updateStatus'])->name('job-orders.update-status');
    Route::delete('/job-orders/{jobOrder}/delete-items', [JobOrderController::class, 'deleteItems'])->name('job-orders.delete-items');
    Route::delete('/job-orders/{id}/delete-breakdowns', [JobOrderController::class, 'deleteBreakdowns'])
        ->name('job-orders.delete-breakdowns');

    // Breakdowns
    Route::resource('breakdowns', BreakdownController::class)->except(['index', 'create', 'show']);
    Route::get('/job-orders/{job_order}/breakdowns/create', [BreakdownController::class, 'create'])
        ->name('breakdowns.create');
    Route::get('/job-orders/{job_order}/breakdowns', [BreakdownController::class, 'index'])
        ->name('breakdowns.index');

    // Order Items
    Route::resource('order-items', OrderItemController::class)->except(['index', 'create', 'show']);
    Route::get('/job-orders/{job_order}/order-items/create', [OrderItemController::class, 'create'])
        ->name('order-items.create');
    Route::get('/job-orders/{job_order}/order-items', [OrderItemController::class, 'index'])
        ->name('order-items.index');


    Route::resource('returns', ReturnController::class);


    // Invoices
    Route::resource('invoices', InvoiceController::class)->only([
        'index',
        'create',
        'show',
        'store',
        'edit',
        'update',
    ]);

    Route::get('invoices/data', [InvoiceController::class, 'data'])->name('invoices.data');

    Route::prefix('invoices')->group(function () {
        Route::get('/create-from-sale/{sale}', [InvoiceController::class, 'createFromSale'])
            ->name('invoices.create-from-sale');

        Route::get('/create-from-service/{jobOrder}', [InvoiceController::class, 'createFromService'])
            ->name('invoices.create-from-service');
    });

    Route::prefix('suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
        Route::get('/data', [SupplierController::class, 'data'])->name('suppliers.data');
    });

    Route::prefix('purchases')->group(function () {
        Route::get('/', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/', [PurchaseController::class, 'store'])->name('purchases.store');
        Route::get('/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
        Route::get('/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
        Route::put('/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');
        Route::delete('/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');
        Route::get('/{purchase}/print', [PurchaseController::class, 'print'])->name('purchases.print');
        Route::get('/data', [PurchaseController::class, 'data'])->name('purchases.data');
        Route::get('/download/{id}', [PurchaseController::class, 'download'])->name('purchase.download');
        Route::delete('/delete-file/{id}', [PurchaseController::class, 'deleteFile'])->name('purchase.deleteFile');
    });

    Route::put('/movement-items/{movementItem}', [MovementItemController::class, 'update'])
        ->name('movement-items.update');
    Route::prefix('movement-items')->group(function () {
        Route::get('/', [MovementItemController::class, 'index'])->name('movement-items.index');
        Route::get('/supply', [MovementItemController::class, 'supply'])->name('movement-items.supply');
        Route::get('/{data}', [MovementItemController::class, 'show'])->name('movement-items.show');
        Route::get('/{data}/edit', [MovementItemController::class, 'edit'])->name('movement-items.edit');
        // Route::put('/{movementItem}', [MovementItemController::class, 'update'])
        //     ->name('movement-items.update');

        // Route::get('/create', [PurchaseController::class, 'create'])->name('purchases.create');
        // Route::post('/', [PurchaseController::class, 'store'])->name('purchases.store');
        // Route::put('/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');
        // Route::delete('/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');
        // Route::get('/{purchase}/print', [PurchaseController::class, 'print'])->name('purchases.print');
        // Route::get('/data', [PurchaseController::class, 'data'])->name('purchases.data');
    });

    Route::resource('supplies', SupplyController::class)->except(['edit', 'update', 'destroy']);
    Route::get('supplies/create-from-job/{jobOrder}', [SupplyController::class, 'createFromJobOrder'])->name('supplies.create-from-job');
    Route::get('supplies/select/joborder', [SupplyController::class, 'selectJobOrder'])->name('supplies.select-job-order');
    Route::post('supplies/create-from-selected', [SupplyController::class, 'createFromSelected'])->name('supplies.create-from-selected');
    Route::get('supplies/{supply}/fulfill', [SupplyController::class, 'fulfillForm'])->name('supplies.fulfill');
    Route::put('supplies/{supply}/fulfill', [SupplyController::class, 'fulfill'])->name('supplies.fulfill.store');
    Route::delete('/supplies/{supply}', [SupplyController::class, 'destroy'])->name('supplies.destroy');



    // Route::resource('movement-items', MovementItemController::class);
    // Route::get('movement-items/supply', [MovementItemController::class, 'supply'])->name('movement-items.supply');


    // Custom Invoice Routes
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])
        ->name('invoices.download');
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])
        ->name('invoices.print');
    Route::patch('/invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])
        ->name('invoices.mark-as-paid');
    Route::get('/invoices/export', [InvoiceController::class, 'export'])
        ->name('invoices.export');


    // sales
    Route::resource('sales', \App\Http\Controllers\SalesController::class);
    Route::get('sales/delete_item/{id}/{sale_id}', [\App\Http\Controllers\SalesController::class, 'delete_item'])->name('delete_item_sales');
    Route::delete('/sales-items/{id}', [SaleController::class, 'destroyItem'])->name('sales-items.destroy');

    Route::resource('returns', \App\Http\Controllers\ReturnController::class);
    Route::post('returns/{returnItem}/status', [\App\Http\Controllers\ReturnController::class, 'updateStatus'])->name('returns.update-status');

    // routes/web.php
    Route::resource('expenses', ExpenseController::class)->except(['show']);
    Route::get('/expenses/data', [ExpenseController::class, 'getExpenses'])->name('expenses.getExpenses');

    // routes/web.php
    Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])
        ->name('reports.profit-loss');

    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('roles', RoleController::class)->except(['show']);
});
// Di routes/web.php
// Route::get('/api/customers/search', function (Request $request) {
//     $query = $request->input('q');

//     $customers = Customer::when($query, function ($q) use ($query) {
//         $q->where('name', 'like', '%' . $query . '%')
//             ->orWhere('phone', 'like', '%' . $query . '%');
//     })
//         ->limit(10)
//         ->get()
//         ->map(function ($customer) {
//             return [
//                 'id' => $customer->id,
//                 'text' => $customer->name,
//                 'phone' => $customer->phone
//             ];
//         });

//     return response()->json($customers);
// })->name('api.customers.search');

require __DIR__ . '/auth.php';
