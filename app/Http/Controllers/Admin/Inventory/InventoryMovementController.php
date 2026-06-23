<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use Illuminate\View\View;

class InventoryMovementController extends Controller
{
    public function index(): View
    {
        $inventoryMovements = InventoryMovement::with(['warehouse', 'product', 'creator'])
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(10);

        return view('admin.inventory-movements.index', compact('inventoryMovements'));
    }
}