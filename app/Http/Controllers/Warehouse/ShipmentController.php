<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Services\DistanceService;

class ShipmentController extends Controller
{
    public function index(): View
    {
        $warehouseId = auth()->user()->warehouse_id;

        $shipments = Shipment::with(['order', 'items.product', 'tripShipments.deliveryTrip'])
            ->where('warehouse_id', $warehouseId)
            ->latest()
            ->paginate(10);

        return view('warehouse.shipments.index', compact('shipments'));
    }

    public function create(): View
    {
        $warehouseId = auth()->user()->warehouse_id;

        // Hanya tampilkan pesanan yang belum punya shipment aktif
        $orders = Order::with('items.product')
            ->where('warehouse_id', $warehouseId)
            ->where('status', 'draft')
            ->whereDoesntHave('shipments')
            ->latest()
            ->get();

        return view('warehouse.shipments.create', compact('orders'));
    }

    public function store(
        Request $request,
        DistanceService $distanceService
    ): RedirectResponse {
        $warehouseId = auth()->user()->warehouse_id;

        $validated = $request->validate([
            'shipment_date' => ['required', 'date'],
            'order_id'      => ['required', 'exists:orders,id'],
            'notes'         => ['nullable', 'string'],
        ], [
            'shipment_date.required' => 'Tanggal pengiriman wajib diisi.',
            'order_id.required'      => 'Pesanan wajib dipilih.',
        ]);

        $order = Order::with(['items.product'])
            ->where('warehouse_id', $warehouseId)
            ->findOrFail($validated['order_id']);

        // Hitung jarak & URL Maps jika koordinat tersedia
        $distanceData  = null;
        $googleMapsUrl = null;

        if ($order->delivery_latitude && $order->delivery_longitude) {
            $warehouse = auth()->user()->warehouse;

            if ($warehouse && $warehouse->latitude && $warehouse->longitude) {
                $road = $distanceService->roadDistance(
                    (float) $warehouse->latitude,
                    (float) $warehouse->longitude,
                    (float) $order->delivery_latitude,
                    (float) $order->delivery_longitude
                );

                $distanceData = [
                    'distance_km'      => $road['distance_km'],
                    'duration_minutes' => $road['duration_minutes'],
                ];
            }

            $googleMapsUrl = $distanceService->buildGoogleMapsUrl(
                (float) $order->delivery_latitude,
                (float) $order->delivery_longitude
            );
        }

        DB::transaction(function () use ($validated, $warehouseId, $order, $distanceData, $googleMapsUrl) {
            $shipment = Shipment::create([
                'shipment_number'            => 'SHP-' . now()->format('YmdHis'),
                'shipment_date'              => $validated['shipment_date'],
                'warehouse_id'               => $warehouseId,
                'order_id'                   => $order->id,
                'driver_user_id'             => null,
                'vehicle_id'                 => null,
                'status'                     => 'pending',
                'notes'                      => $validated['notes'] ?? null,
                'created_by'                 => auth()->id(),
                'estimated_distance_km'      => $distanceData['distance_km'] ?? null,
                'estimated_duration_minutes' => $distanceData['duration_minutes'] ?? null,
                'google_maps_url'            => $googleMapsUrl,
            ]);

            foreach ($order->items as $item) {
                $shipment->items()->create([
                    'product_id' => $item->product_id,
                    'qty'        => $item->qty,
                ]);
            }

            // Tandai pesanan sudah ada shipment, menunggu delivery trip
            $order->update(['status' => 'processing']);
        });

        return redirect()
            ->route('warehouse.shipments.index')
            ->with('success', 'Pengiriman berhasil dibuat. Silakan masukkan ke Delivery Trip untuk menentukan driver dan kendaraan.');
    }
}