<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Services\GoogleMapsService;

class OrderController extends Controller
{
    public function index(): View
    {
        $warehouseId = auth()->user()->warehouse_id;

        $orders = Order::with(['items.product'])
            ->where('warehouse_id', $warehouseId)
            ->latest()
            ->paginate(10);

        return view('warehouse.orders.index', compact('orders'));
    }

    public function create(): View
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('warehouse.orders.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $warehouseId = auth()->user()->warehouse_id;

        $validated = $request->validate([
            'order_date' => ['required', 'date'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:255'],
            'delivery_address' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'min:0.01'],
            'delivery_latitude' => ['required', 'numeric'],
            'delivery_longitude' => ['required', 'numeric'],
        ], [
            'order_date.required' => 'Tanggal pesanan wajib diisi.',
            'customer_name.required' => 'Nama customer wajib diisi.',
            'delivery_address.required' => 'Alamat pengiriman wajib diisi.',
            'items.required' => 'Item pesanan wajib diisi.',
            'items.min' => 'Minimal ada 1 item pesanan.',
            'items.*.product_id.required' => 'Produk wajib dipilih.',
            'items.*.qty.required' => 'Qty wajib diisi.',
        ]);

        DB::transaction(function () use ($validated, $warehouseId) {
            $order = Order::create([
                'order_number' => 'ORD-' . now()->format('YmdHis'),
                'order_date' => $validated['order_date'],
                'warehouse_id' => $warehouseId,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'delivery_address' => $validated['delivery_address'],
                'status' => 'draft',
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
                'delivery_latitude' => $validated['delivery_latitude'],
                'delivery_longitude' => $validated['delivery_longitude'],
            ]);

            foreach ($validated['items'] as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                ]);
            }
        });

        return redirect()
            ->route('warehouse.orders.index')
            ->with('success', 'Pesanan berhasil dibuat.');
    }
}