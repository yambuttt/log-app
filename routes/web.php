<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\Inventory\GoodsReceiptController;
use App\Http\Controllers\Admin\Inventory\InventoryMovementController;
use App\Http\Controllers\Admin\Inventory\ProductController;
use App\Http\Controllers\Admin\Inventory\StockController;
use App\Http\Controllers\Admin\Inventory\StockOpnameController;
use App\Http\Controllers\Admin\Inventory\WasteController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehicleCapacityController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\VehicleMaintenanceController;
use App\Http\Controllers\Admin\DeliveryTripController as AdminDeliveryTripController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Driver\DashboardController as DriverDashboardController;
use App\Http\Controllers\Driver\VehicleAssignmentController;
use App\Http\Controllers\Warehouse\DashboardController as WarehouseDashboardController;
use App\Http\Controllers\Warehouse\Inventory\GoodsReceiptController as WarehouseGoodsReceiptController;
use App\Http\Controllers\Warehouse\Inventory\InventoryMovementController as WarehouseInventoryMovementController;
use App\Http\Controllers\Warehouse\Inventory\StockController as WarehouseStockController;
use App\Http\Controllers\Warehouse\Inventory\StockOpnameController as WarehouseStockOpnameController;
use App\Http\Controllers\Warehouse\Inventory\WasteController as WarehouseWasteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Warehouse\OrderController as WarehouseOrderController;
use App\Http\Controllers\Warehouse\ShipmentController as WarehouseShipmentController;
use App\Http\Controllers\Driver\ShipmentController as DriverShipmentController;
use App\Http\Controllers\Warehouse\SettingController as WarehouseSettingController;
use App\Http\Controllers\Warehouse\DeliveryTripController as WarehouseDeliveryTripController;
use App\Http\Controllers\Driver\DeliveryTripController as DriverDeliveryTripController;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return match (Auth::user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'warehouse' => redirect()->route('warehouse.dashboard'),
        'driver' => redirect()->route('driver.dashboard'),
        default => redirect()->route('login'),
    };
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('shipments/{shipment}/print-surat-jalan', [App\Http\Controllers\ShipmentPrintController::class, 'print'])
        ->name('shipments.print-surat-jalan');
    Route::get('delivery-trips/{deliveryTrip}/print-all-surat-jalan', [App\Http\Controllers\ShipmentPrintController::class, 'printAll'])
        ->name('delivery-trips.print-all-surat-jalan');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', ProductController::class)->except(['show', 'destroy']);

        Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');

        Route::get('goods-receipts', [GoodsReceiptController::class, 'index'])->name('goods-receipts.index');
        Route::get('goods-receipts/create', [GoodsReceiptController::class, 'create'])->name('goods-receipts.create');
        Route::post('goods-receipts', [GoodsReceiptController::class, 'store'])->name('goods-receipts.store');

        Route::get('inventory-movements', [InventoryMovementController::class, 'index'])->name('inventory-movements.index');

        Route::get('wastes', [WasteController::class, 'index'])->name('wastes.index');
        Route::get('wastes/create', [WasteController::class, 'create'])->name('wastes.create');
        Route::post('wastes', [WasteController::class, 'store'])->name('wastes.store');

        Route::get('stock-opnames', [StockOpnameController::class, 'index'])->name('stock-opnames.index');
        Route::get('stock-opnames/create', [StockOpnameController::class, 'create'])->name('stock-opnames.create');
        Route::post('stock-opnames', [StockOpnameController::class, 'store'])->name('stock-opnames.store');

        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');

        Route::get('vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
        Route::get('vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create');
        Route::post('vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
        Route::get('vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
        Route::put('vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');

        Route::get('vehicle-maintenances', [VehicleMaintenanceController::class, 'index'])->name('vehicle-maintenances.index');
        Route::post('vehicle-maintenances', [VehicleMaintenanceController::class, 'store'])->name('vehicle-maintenances.store');
        Route::post('vehicle-maintenances/{vehicle}/release', [VehicleMaintenanceController::class, 'release'])->name('vehicle-maintenances.release');

        Route::get('vehicle-capacities', [VehicleCapacityController::class, 'index'])->name('vehicle-capacities.index');
        Route::get('vehicle-capacities/create', [VehicleCapacityController::class, 'create'])->name('vehicle-capacities.create');
        Route::post('vehicle-capacities', [VehicleCapacityController::class, 'store'])->name('vehicle-capacities.store');
        Route::get('delivery-trips', [AdminDeliveryTripController::class, 'index'])->name('delivery-trips.index');
    });

    Route::prefix('warehouse')->name('warehouse.')->middleware('role:warehouse')->group(function () {
        Route::get('/dashboard', [WarehouseDashboardController::class, 'index'])->name('dashboard');

        Route::get('stocks', [WarehouseStockController::class, 'index'])->name('stocks.index');
        Route::get('inventory-movements', [WarehouseInventoryMovementController::class, 'index'])->name('inventory-movements.index');

        Route::get('goods-receipts', [WarehouseGoodsReceiptController::class, 'index'])->name('goods-receipts.index');
        Route::get('goods-receipts/create', [WarehouseGoodsReceiptController::class, 'create'])->name('goods-receipts.create');
        Route::post('goods-receipts', [WarehouseGoodsReceiptController::class, 'store'])->name('goods-receipts.store');

        Route::get('wastes', [WarehouseWasteController::class, 'index'])->name('wastes.index');
        Route::get('wastes/create', [WarehouseWasteController::class, 'create'])->name('wastes.create');
        Route::post('wastes', [WarehouseWasteController::class, 'store'])->name('wastes.store');

        Route::get('stock-opnames', [WarehouseStockOpnameController::class, 'index'])->name('stock-opnames.index');
        Route::get('stock-opnames/create', [WarehouseStockOpnameController::class, 'create'])->name('stock-opnames.create');
        Route::post('stock-opnames', [WarehouseStockOpnameController::class, 'store'])->name('stock-opnames.store');

        Route::get('orders', [WarehouseOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [WarehouseOrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [WarehouseOrderController::class, 'store'])->name('orders.store');

        Route::get('shipments', [WarehouseShipmentController::class, 'index'])->name('shipments.index');
        Route::get('shipments/create', [WarehouseShipmentController::class, 'create'])->name('shipments.create');
        Route::post('shipments', [WarehouseShipmentController::class, 'store'])->name('shipments.store');

        Route::get('settings/location', [WarehouseSettingController::class, 'edit'])->name('settings.location.edit');
        Route::put('settings/location', [WarehouseSettingController::class, 'update'])->name('settings.location.update');
        Route::get('delivery-trips', [WarehouseDeliveryTripController::class, 'index'])->name('delivery-trips.index');
        Route::get('delivery-trips/create', [WarehouseDeliveryTripController::class, 'create'])->name('delivery-trips.create');
        Route::post('delivery-trips', [WarehouseDeliveryTripController::class, 'store'])->name('delivery-trips.store');
    });

    Route::prefix('driver')->name('driver.')->middleware('role:driver')->group(function () {
        Route::get('/dashboard', [DriverDashboardController::class, 'index'])->name('dashboard');

        Route::get('vehicle-assignment', [VehicleAssignmentController::class, 'index'])->name('vehicle-assignment.index');
        Route::post('vehicle-assignment', [VehicleAssignmentController::class, 'store'])->name('vehicle-assignment.store');

        Route::get('shipments', [DriverShipmentController::class, 'index'])->name('shipments.index');
        Route::post('shipments/{shipment}/start', [DriverShipmentController::class, 'start'])->name('shipments.start');
        Route::post('shipments/{shipment}/delivered', [DriverShipmentController::class, 'delivered'])->name('shipments.delivered');
        Route::post('shipments/{shipment}/returned', [DriverShipmentController::class, 'returned'])->name('shipments.returned');
        Route::get('delivery-trips', [DriverDeliveryTripController::class, 'index'])->name('delivery-trips.index');
        Route::post('delivery-trips/{deliveryTrip}/start', [DriverDeliveryTripController::class, 'start'])->name('delivery-trips.start');
        Route::post('delivery-trips/{deliveryTrip}/trip-shipments/{tripShipmentId}/complete-stop', [DriverDeliveryTripController::class, 'completeStop'])->name('delivery-trips.complete-stop');
        Route::post('delivery-trips/{deliveryTrip}/return-home', [DriverDeliveryTripController::class, 'returnHome'])->name('delivery-trips.return-home');
        Route::post('delivery-trips/{deliveryTrip}/finish', [DriverDeliveryTripController::class, 'finish'])->name('delivery-trips.finish');
    });
});