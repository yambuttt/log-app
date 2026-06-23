<?php

namespace App\Http\Controllers\Warehouse\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use Illuminate\View\View;

class InventoryMovementController extends Controller
{
    public function index(): View
    {
        $warehouseId = auth()->user()->warehouse_id;

        $inventoryMovements = InventoryMovement::with(['warehouse', 'product', 'creator'])
            ->where('warehouse_id', $warehouseId)
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(10);

        return view('warehouse.inventory-movements.index', compact('inventoryMovements'));
    }
}