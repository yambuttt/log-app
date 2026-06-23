<?php

namespace App\Http\Controllers\Warehouse\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(): View
    {
        $warehouseId = auth()->user()->warehouse_id;

        $stocks = Stock::with(['warehouse', 'product.unit'])
            ->where('warehouse_id', $warehouseId)
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('warehouse.stocks.index', compact('stocks'));
    }
}