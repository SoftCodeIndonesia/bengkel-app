<?php

use App\Models\Customer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
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
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\JobOrderController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BreakdownController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EstimationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\MovementItemController;
use App\Http\Controllers\ServicePackageController;
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

    Route::prefix('test')->group(function () {
        Route::get('/login', function () {
            return view('test/login');
        });
        Route::get('/activation', function () {
            return view('test/activation');
        });
        Route::get('/activation_home', function () {
            return view('test/index');
        });
    });



    Route::middleware('permission:manage inventory')->group(function () {
        Route::resource('movements', MovementController::class)->only(['index']);
        Route::get('movements/report', [MovementController::class, 'report'])->name('movements.report');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customers
    Route::middleware('permission:view customers')->group(function () {
        Route::resource('customers', CustomerController::class);
    });

    // Vehicles and Master Data
    Route::middleware('permission:manage master data')->group(function () {
        Route::resource('vehicles', VehicleController::class);
        Route::resource('services', ServiceController::class);

        Route::get('/customer-vehicles/create', [CustomerVehicleController::class, 'create'])
            ->name('customer-vehicles.create');
        Route::delete('/customer-vehicles/{id}', [CustomerVehicleController::class, 'destroy'])
            ->name('customer-vehicles.destroy');
        Route::post('/customer-vehicles', [CustomerVehicleController::class, 'store'])
            ->name('customer-vehicles.store');
    });

    Route::middleware('permission:manage follow-ups')->group(function () {
        Route::resource('/follow-ups', FollowUpController::class);
    });

    Route::middleware('permission:manage inventory')->group(function () {
        Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
        Route::resource('products', ProductController::class);
    });

    // Route::get('info-stok', [ProductController::class, 'info'])->name('info-stok');
    // Route::get('info-detail/{id}', [ProductController::class, 'infoDetail'])->name('info-detail');

    // Workshop
    Route::middleware('permission:manage workshop')->group(function () {
        Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
        Route::post('/reminders/send', [ReminderController::class, 'send'])->name('reminders.send');
        Route::get('job-orders/print/{id}', [JobOrderController::class, 'print'])->name('job-orders.print');
        Route::resource('job-orders', JobOrderController::class);

        Route::get('/job-orders/delete/{id}/{joID}', [JobOrderController::class, 'deleteProduct'])->name('job-orders.deleteProduct');

        Route::patch('/job-orders/{job_order}/cancel', [JobOrderController::class, 'cancel'])
            ->name('job-orders.cancel');
        Route::patch('/job-orders/{job_order}/complete', [JobOrderController::class, 'complete'])
            ->name('job-orders.complete');
        Route::put('/job-orders/{id}/complete', [JobOrderController::class, 'complete'])->name('job-orders.complete');
        Route::get('/job-orders/{id}/update-status/{status}', [JobOrderController::class, 'updateStatus'])->name('job-orders.update-status');
        Route::delete('/job-orders/{jobOrder}/delete-items', [JobOrderController::class, 'deleteItems'])->name('job-orders.delete-items');
        Route::delete('/job-orders/{id}/delete-breakdowns', [JobOrderController::class, 'deleteBreakdowns'])
            ->name('job-orders.delete-breakdowns');

        Route::resource('breakdowns', BreakdownController::class)->except(['index', 'create', 'show']);
        Route::get('/job-orders/{job_order}/breakdowns/create', [BreakdownController::class, 'create'])
            ->name('breakdowns.create');
        Route::get('/job-orders/{job_order}/breakdowns', [BreakdownController::class, 'index'])
            ->name('breakdowns.index');

        Route::resource('order-items', OrderItemController::class)->except(['index', 'create', 'show']);
        Route::get('/job-orders/{job_order}/order-items/create', [OrderItemController::class, 'create'])
            ->name('order-items.create');
        Route::get('/job-orders/{job_order}/order-items', [OrderItemController::class, 'index'])
            ->name('order-items.index');
    });

    // Invoices
    Route::middleware('permission:manage invoices')->group(function () {
        Route::put('invoices/status/{id}', [InvoiceController::class, 'status'])->name('invoice-status-update');
        Route::get('invoices/print/{id}', [InvoiceController::class, 'print'])->name('invoice-print');
        Route::resource('invoices', InvoiceController::class)->only([
            'index',
            'create',
            'show',
            'store',
            'edit',
            'update',
        ]);
        Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

        Route::get('invoices/data', [InvoiceController::class, 'data'])->name('invoices.data');

        Route::prefix('invoices')->group(function () {
            Route::get('/create-from-sale/{sale}', [InvoiceController::class, 'createFromSale'])
                ->name('invoices.create-from-sale');

            Route::get('/create-from-service/{jobOrder}', [InvoiceController::class, 'createFromService'])
                ->name('invoices.create-from-service');
        });
    });

    // Purchasing
    Route::middleware('permission:manage purchases')->group(function () {
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
    });

    Route::middleware('permission:manage inventory')->group(function () {
        Route::put('/movement-items/{movementItem}', [MovementItemController::class, 'update'])
            ->name('movement-items.update');
        Route::prefix('movement-items')->group(function () {
            Route::get('/', [MovementItemController::class, 'index'])->name('movement-items.index');
            Route::get('/bulk_edit', [MovementItemController::class, 'bulk_check'])->name('movement-items.bulk_edit');
            Route::post('/update_bulk', [MovementItemController::class, 'update_bulk'])->name('movement-items.update_bulk');
            Route::get('/supply', [MovementItemController::class, 'supply'])->name('movement-items.supply');
            Route::get('/{data}', [MovementItemController::class, 'show'])->name('movement-items.show');
            Route::get('/{data}/edit', [MovementItemController::class, 'edit'])->name('movement-items.edit');
        });

        Route::resource('supplies', SupplyController::class)->only(['index', 'create']);
        Route::get('supplies/create-sales-order/{salesId}', [SupplyController::class, 'createFromSales'])->name('supplies.create-sales-order');
        Route::get('supplies/select/joborder', [SupplyController::class, 'selectJobOrder'])->name('supplies.select-job-order');
        Route::get('supplies/select/sales', [SupplyController::class, 'salectSalesOrder'])->name('supplies.select-sales-order');
        Route::post('supplies/create-from-selected', [SupplyController::class, 'createFromSelected'])->name('supplies.create-from-selected');
        Route::get('supplies/{supply}/fulfill', [SupplyController::class, 'fulfillForm'])->name('supplies.fulfill');
        Route::put('supplies/{supply}/fulfill', [SupplyController::class, 'fulfill'])->name('supplies.fulfill.store');
        Route::delete('/supplies/{supply}', [SupplyController::class, 'destroy'])->name('supplies.destroy');
    });

    Route::middleware('permission:create supply requests|manage inventory')->group(function () {
        Route::get('supplies/create-from-job/{jobOrder}', [SupplyController::class, 'createFromJobOrder'])->name('supplies.create-from-job');
        Route::post('supplies', [SupplyController::class, 'store'])->name('supplies.store');
        Route::get('supplies/{supply}', [SupplyController::class, 'show'])->name('supplies.show');
    });



    // Route::resource('movement-items', MovementItemController::class);
    // Route::get('movement-items/supply', [MovementItemController::class, 'supply'])->name('movement-items.supply');


    // Custom Invoice Routes
    Route::middleware('permission:manage invoices')->group(function () {
        Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])
            ->name('invoices.download');
        Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])
            ->name('invoices.print');
        Route::patch('/invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])
            ->name('invoices.mark-as-paid');
        Route::get('/invoices/export', [InvoiceController::class, 'export'])
            ->name('invoices.export');
    });

    // Sales
    Route::middleware('permission:manage sales')->group(function () {
        Route::resource('sales', SalesController::class);
        Route::get('sales/delete_item/{id}/{sale_id}', [SalesController::class, 'delete_item'])->name('delete_item_sales');
        Route::get('sales/{id}/print', [SalesController::class, 'print'])->name('print_so');
        Route::delete('/sales-items/{id}', [SalesController::class, 'destroyItem'])->name('sales-items.destroy');
    });

    // Inventory
    Route::middleware('permission:manage inventory')->group(function () {
        Route::resource('returns', ReturnController::class);
        Route::post('returns/{returnItem}/status', [ReturnController::class, 'updateStatus'])->name('returns.update-status');

        Route::get('stock-opname/products', [StockOpnameController::class, 'getProducts'])->name('stock-opname.products');
        Route::get('stock-opname/create-form', [StockOpnameController::class, 'createForm'])->name('stock-opname-create-form');
        Route::post('stock-opname/print-form', [StockOpnameController::class, 'printForm'])->name('stock-opname-print-form');
        Route::resource('stock-opname', StockOpnameController::class);
    });

    // Finance
    Route::middleware('permission:manage finance')->group(function () {
        Route::resource('expenses', ExpenseController::class)->except(['show']);
        Route::get('/expenses/data', [ExpenseController::class, 'getExpenses'])->name('expenses.getExpenses');
    });

    Route::middleware('permission:view reports')->group(function () {
        Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])
            ->name('reports.profit-loss');
        Route::get('/reports/prevew-excel', [ReportController::class, 'previewExportExcel'])
            ->name('reports.preview-excel');

        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
    });

    Route::middleware('permission:view users|create users|edit users|delete users')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    Route::middleware('permission:view roles|create roles|edit roles|delete roles')->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);
    });

    Route::middleware('permission:manage appointments')->group(function () {
        Route::resource('appointments', AppointmentController::class);
        Route::get('appointments/customer-vehicles/{customerId}', [AppointmentController::class, 'getCustomerVehicles']);
    });

    Route::middleware('permission:manage workshop')->group(function () {
        Route::get('/estimation/print/{id}', [EstimationController::class, 'print'])->name('estimation.print');
        Route::get('/estimation/job-order/{id}', [EstimationController::class, 'toJobOrder'])->name('to-job-order');
        Route::resource('estimation', EstimationController::class);

        Route::resource('service-packages', ServicePackageController::class);
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::middleware('permission:manage hr')->group(function () {
        Route::prefix('employees')->group(function () {
            Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');
            Route::get('/create', [EmployeeController::class, 'create'])->name('employees.create');
            Route::post('/', [EmployeeController::class, 'store'])->name('employees.store');
            Route::get('/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
            Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
            Route::put('/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
            Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
            Route::get('/datatable', [EmployeeController::class, 'datatable'])->name('employees.datatable');
        });

        Route::get('attendances/report', [AttendanceController::class, 'report'])->name('attendances.report');
        Route::resource('attendances', AttendanceController::class);
    });
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
