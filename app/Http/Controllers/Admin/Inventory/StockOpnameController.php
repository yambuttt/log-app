<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockOpname;
use App\Models\Warehouse;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockOpnameController extends Controller
{
    public function index(): View
    {
        $stockOpnames = StockOpname::with(['warehouse', 'product'])
            ->latest()
            ->paginate(10);

        return view('admin.stock-opnames.index', compact('stockOpnames'));
    }

    public function create(): View
    {
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.stock-opnames.create', compact('warehouses', 'products'));
    }

    public function store(Request $request, InventoryService $inventoryService): RedirectResponse
    {
        $validated = $request->validate([
            'opname_date' => ['required', 'date'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'product_id' => ['required', 'exists:products,id'],
            'physical_qty' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ], [
            'opname_date.required' => 'Tanggal wajib diisi.',
            'warehouse_id.required' => 'Gudang wajib dipilih.',
            'product_id.required' => 'Barang wajib dipilih.',
            'physical_qty.required' => 'Stok fisik wajib diisi.',
            'physical_qty.numeric' => 'Stok fisik harus berupa angka.',
        ]);

        DB::transaction(function () use ($validated, $inventoryService) {
            $stock = Stock::firstOrCreate(
                [
                    'warehouse_id' => $validated['warehouse_id'],
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
                'warehouse_id' => $validated['warehouse_id'],
                'product_id' => $validated['product_id'],
                'system_qty' => $systemQty,
                'physical_qty' => $physicalQty,
                'difference_qty' => $differenceQty,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $inventoryService->stockOpname([
                'transaction_date' => $validated['opname_date'],
                'warehouse_id' => $validated['warehouse_id'],
                'product_id' => $validated['product_id'],
                'physical_qty' => $physicalQty,
                'reference_type' => StockOpname::class,
                'reference_id' => $stockOpname->id,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.stock-opnames.index')
            ->with('success', 'Stock opname berhasil disimpan.');
    }
}