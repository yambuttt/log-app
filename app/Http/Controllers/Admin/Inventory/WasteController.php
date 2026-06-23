<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Warehouse;
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
        $wastes = Waste::with(['warehouse', 'product'])
            ->latest()
            ->paginate(10);

        return view('admin.wastes.index', compact('wastes'));
    }

    public function create(): View
    {
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.wastes.create', compact('warehouses', 'products'));
    }

    public function store(Request $request, InventoryService $inventoryService): RedirectResponse
    {
        $validated = $request->validate([
            'waste_date' => ['required', 'date'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ], [
            'waste_date.required' => 'Tanggal wajib diisi.',
            'warehouse_id.required' => 'Gudang wajib dipilih.',
            'product_id.required' => 'Barang wajib dipilih.',
            'qty.required' => 'Qty wajib diisi.',
            'qty.numeric' => 'Qty harus berupa angka.',
            'qty.min' => 'Qty minimal 0.01.',
            'reason.required' => 'Alasan waste wajib diisi.',
        ]);

        DB::transaction(function () use ($validated, $inventoryService) {
            $waste = Waste::create([
                'waste_number' => 'WS-' . now()->format('YmdHis'),
                'waste_date' => $validated['waste_date'],
                'warehouse_id' => $validated['warehouse_id'],
                'product_id' => $validated['product_id'],
                'qty' => $validated['qty'],
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $inventoryService->waste([
                'transaction_date' => $validated['waste_date'],
                'warehouse_id' => $validated['warehouse_id'],
                'product_id' => $validated['product_id'],
                'qty' => $validated['qty'],
                'reference_type' => Waste::class,
                'reference_id' => $waste->id,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.wastes.index')
            ->with('success', 'Waste berhasil disimpan.');
    }
}