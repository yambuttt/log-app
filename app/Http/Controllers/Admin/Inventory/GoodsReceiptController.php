<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceipt;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GoodsReceiptController extends Controller
{
    public function index(): View
    {
        $goodsReceipts = GoodsReceipt::with(['warehouse', 'product'])
            ->latest()
            ->paginate(10);

        return view('admin.goods-receipts.index', compact('goodsReceipts'));
    }

    public function create(): View
    {
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.goods-receipts.create', compact('warehouses', 'products'));
    }

    public function store(Request $request, InventoryService $inventoryService): RedirectResponse
    {
        $validated = $request->validate([
            'receipt_date' => ['required', 'date'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'numeric', 'min:0.01'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ], [
            'receipt_date.required' => 'Tanggal wajib diisi.',
            'warehouse_id.required' => 'Gudang wajib dipilih.',
            'product_id.required' => 'Barang wajib dipilih.',
            'qty.required' => 'Qty wajib diisi.',
            'qty.numeric' => 'Qty harus berupa angka.',
            'qty.min' => 'Qty minimal 0.01.',
        ]);

        DB::transaction(function () use ($validated, $inventoryService) {
            $receipt = GoodsReceipt::create([
                'receipt_number' => 'GR-' . now()->format('YmdHis'),
                'receipt_date' => $validated['receipt_date'],
                'warehouse_id' => $validated['warehouse_id'],
                'product_id' => $validated['product_id'],
                'qty' => $validated['qty'],
                'supplier_name' => $validated['supplier_name'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $inventoryService->stockIn([
                'transaction_date' => $validated['receipt_date'],
                'warehouse_id' => $validated['warehouse_id'],
                'product_id' => $validated['product_id'],
                'qty' => $validated['qty'],
                'reference_type' => GoodsReceipt::class,
                'reference_id' => $receipt->id,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.goods-receipts.index')
            ->with('success', 'Barang masuk berhasil disimpan.');
    }
}