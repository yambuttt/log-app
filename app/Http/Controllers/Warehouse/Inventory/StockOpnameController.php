<?php

namespace App\Http\Controllers\Warehouse\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockOpname;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockOpnameController extends Controller
{
    public function index(): View
    {
        $warehouseId = auth()->user()->warehouse_id;

        $stockOpnames = StockOpname::with(['warehouse', 'product'])
            ->where('warehouse_id', $warehouseId)
            ->latest()
            ->paginate(10);

        return view('warehouse.stock-opnames.index', compact('stockOpnames'));
    }

    public function create(): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('warehouse.stock-opnames.create', compact('products'));
    }

    public function store(Request $request, InventoryService $inventoryService): RedirectResponse
    {
        $warehouseId = auth()->user()->warehouse_id;

        $validated = $request->validate([
            'opname_date' => ['required', 'date'],
            'product_id' => ['required', 'exists:products,id'],
            'physical_qty' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $inventoryService, $warehouseId) {
            $stock = Stock::firstOrCreate(
                [
                    'warehouse_id' => $warehouseId,
                    'product_id' => $validated['product_id'],
                ],
                [
                    'qty' => 0,
                ]
            );

            $systemQty = $stock->qty;
            $physicalQty = $validated['physical_qty'];
            $differenceQty = $physicalQty - $systemQty;

            $stockOpname = StockOpname::create([
                'opname_number' => 'SO-' . now()->format('YmdHis'),
                'opname_date' => $validated['opname_date'],
                'warehouse_id' => $warehouseId,
                'product_id' => $validated['product_id'],
                'system_qty' => $systemQty,
                'physical_qty' => $physicalQty,
                'difference_qty' => $differenceQty,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $inventoryService->stockOpname([
                'transaction_date' => $validated['opname_date'],
                'warehouse_id' => $warehouseId,
                'product_id' => $validated['product_id'],
                'physical_qty' => $physicalQty,
                'reference_type' => StockOpname::class,
                'reference_id' => $stockOpname->id,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('warehouse.stock-opnames.index')
            ->with('success', 'Stock opname berhasil disimpan.');
    }
}