<?php

namespace App\Http\Controllers\Warehouse\Inventory;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceipt;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GoodsReceiptController extends Controller
{
    public function index(): View
    {
        $warehouseId = auth()->user()->warehouse_id;

        $goodsReceipts = GoodsReceipt::with(['warehouse', 'product'])
            ->where('warehouse_id', $warehouseId)
            ->latest()
            ->paginate(10);

        return view('warehouse.goods-receipts.index', compact('goodsReceipts'));
    }

    public function create(): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('warehouse.goods-receipts.create', compact('products'));
    }

    public function store(Request $request, InventoryService $inventoryService): RedirectResponse
    {
        $warehouseId = auth()->user()->warehouse_id;

        $validated = $request->validate([
            'receipt_date' => ['required', 'date'],
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'numeric', 'min:0.01'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $inventoryService, $warehouseId) {
            $receipt = GoodsReceipt::create([
                'receipt_number' => 'GR-' . now()->format('YmdHis'),
                'receipt_date' => $validated['receipt_date'],
                'warehouse_id' => $warehouseId,
                'product_id' => $validated['product_id'],
                'qty' => $validated['qty'],
                'supplier_name' => $validated['supplier_name'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $inventoryService->stockIn([
                'transaction_date' => $validated['receipt_date'],
                'warehouse_id' => $warehouseId,
                'product_id' => $validated['product_id'],
                'qty' => $validated['qty'],
                'reference_type' => GoodsReceipt::class,
                'reference_id' => $receipt->id,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('warehouse.goods-receipts.index')
            ->with('success', 'Barang masuk berhasil disimpan.');
    }
}