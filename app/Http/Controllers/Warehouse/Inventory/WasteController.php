<?php

namespace App\Http\Controllers\Warehouse\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Waste;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WasteController extends Controller
{
    public function index(): View
    {
        $warehouseId = auth()->user()->warehouse_id;

        $wastes = Waste::with(['warehouse', 'product'])
            ->where('warehouse_id', $warehouseId)
            ->latest()
            ->paginate(10);

        return view('warehouse.wastes.index', compact('wastes'));
    }

    public function create(): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('warehouse.wastes.create', compact('products'));
    }

    public function store(Request $request, InventoryService $inventoryService): RedirectResponse
    {
        $warehouseId = auth()->user()->warehouse_id;

        $validated = $request->validate([
            'waste_date' => ['required', 'date'],
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $inventoryService, $warehouseId) {
            $waste = Waste::create([
                'waste_number' => 'WS-' . now()->format('YmdHis'),
                'waste_date' => $validated['waste_date'],
                'warehouse_id' => $warehouseId,
                'product_id' => $validated['product_id'],
                'qty' => $validated['qty'],
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $inventoryService->waste([
                'transaction_date' => $validated['waste_date'],
                'warehouse_id' => $warehouseId,
                'product_id' => $validated['product_id'],
                'qty' => $validated['qty'],
                'reference_type' => Waste::class,
                'reference_id' => $waste->id,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('warehouse.wastes.index')
            ->with('success', 'Waste berhasil disimpan.');
    }
}