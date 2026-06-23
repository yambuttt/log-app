<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\DeliveryTrip;
use App\Models\DriverVehicleAssignment;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\NearestNeighborRouteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DeliveryTripController extends Controller
{
    public function index(): View
    {
        $warehouseId = auth()->user()->warehouse_id;

        $trips = DeliveryTrip::with(['driver', 'vehicle', 'shipments.order'])
            ->where('warehouse_id', $warehouseId)
            ->latest()
            ->paginate(10);

        return view('warehouse.delivery-trips.index', compact('trips'));
    }

    public function create(Request $request): View
    {
        $warehouseId = auth()->user()->warehouse_id;
        $tripDate = $request->query('trip_date', now()->toDateString());

        $shipments = Shipment::with(['order.items.product', 'items.product'])
            ->where('warehouse_id', $warehouseId)
            ->whereDate('shipment_date', $tripDate)
            ->whereIn('status', ['assigned', 'waiting_driver'])
            ->whereDoesntHave('tripShipments')
            ->get();

        $drivers = User::where('role', 'driver')
            ->where('is_active', true)
            ->where('availability_status', 'available')
            ->orderBy('name')
            ->get();

        $vehicles = Vehicle::where('is_active', true)
            ->orderBy('name')
            ->get();

        $assignments = DriverVehicleAssignment::with(['vehicle.capacities.product', 'driver'])
            ->get();

        return view('warehouse.delivery-trips.create', compact('shipments', 'drivers', 'vehicles', 'tripDate', 'assignments'));
    }

    public function store(Request $request, NearestNeighborRouteService $nearestNeighborRouteService, \App\Services\DistanceService $distanceService): RedirectResponse
    {
        $warehouseId = auth()->user()->warehouse_id;

        $validated = $request->validate([
            'trip_date' => ['required', 'date'],
            'driver_user_id' => ['required', 'exists:users,id'],
            'shipment_ids' => ['required', 'array', 'min:1'],
            'shipment_ids.*' => ['required', 'exists:shipments,id'],
            'notes' => ['nullable', 'string'],
        ], [
            'trip_date.required' => 'Tanggal trip wajib diisi.',
            'driver_user_id.required' => 'Driver wajib dipilih.',
            'shipment_ids.required' => 'Pilih minimal satu shipment.',
        ]);

        $driverAssignment = DriverVehicleAssignment::where('driver_user_id', $validated['driver_user_id'])
            ->where('assignment_date', $validated['trip_date'])
            ->first();

        if (! $driverAssignment) {
            return back()
                ->withErrors(['driver_user_id' => 'Driver belum punya assignment kendaraan pada tanggal tersebut.'])
                ->withInput();
        }

        $vehicleId = $driverAssignment->vehicle_id;

        $shipments = Shipment::with(['order', 'items.product'])
            ->where('warehouse_id', $warehouseId)
            ->whereIn('id', $validated['shipment_ids'])
            ->whereDoesntHave('tripShipments')
            ->get();

        if ($shipments->count() !== count($validated['shipment_ids'])) {
            return back()
                ->withErrors(['shipment_ids' => 'Ada shipment yang sudah masuk ke trip lain.'])
                ->withInput();
        }

        // 1. Validasi Total Kapasitas Gabungan dari Seluruh Shipment yang dipilih terhadap Kendaraan
        $totals = [];
        foreach ($shipments as $shipment) {
            foreach ($shipment->items as $item) {
                $productId = $item->product_id;
                $totals[$productId] = ($totals[$productId] ?? 0) + $item->qty;
            }
        }

        $capacities = \App\Models\VehicleCapacity::where('vehicle_id', $vehicleId)
            ->get()
            ->keyBy('product_id');

        foreach ($totals as $productId => $totalQty) {
            $capacity = $capacities->get($productId);
            $productName = \App\Models\Product::find($productId)->name ?? 'Barang';

            if (!$capacity) {
                return back()
                    ->withErrors(['driver_user_id' => 'Kapasitas untuk produk "' . $productName . '" belum diatur pada kendaraan driver ini.'])
                    ->withInput();
            }

            if ($totalQty > $capacity->max_qty) {
                return back()
                    ->withErrors(['driver_user_id' => 'Total muatan produk "' . $productName . '" (' . $totalQty . ' tabung) melebihi kapasitas maksimal kendaraan driver (' . $capacity->max_qty . ' tabung).'])
                    ->withInput();
            }
        }

        $warehouse = auth()->user()->warehouse;

        if (! $warehouse || ! $warehouse->latitude || ! $warehouse->longitude) {
            return back()
                ->withErrors(['shipment_ids' => 'Lokasi gudang belum diatur.'])
                ->withInput();
        }

        foreach ($shipments as $shipment) {
            if (! optional($shipment->order)->delivery_latitude || ! optional($shipment->order)->delivery_longitude) {
                return back()
                    ->withErrors(['shipment_ids' => 'Ada shipment yang belum punya koordinat customer.'])
                    ->withInput();
            }
        }

        $sortedRoutes = $nearestNeighborRouteService->generate(
            $shipments,
            (float) $warehouse->latitude,
            (float) $warehouse->longitude
        );

        // Konversi jarak udara (Haversine) ke jarak jalan raya (OSRM)
        $currentLat = (float) $warehouse->latitude;
        $currentLng = (float) $warehouse->longitude;
        foreach ($sortedRoutes as &$route) {
            $destLat = (float) $route['shipment']->order->delivery_latitude;
            $destLng = (float) $route['shipment']->order->delivery_longitude;

            $road = $distanceService->roadDistance($currentLat, $currentLng, $destLat, $destLng);
            $route['distance_from_previous_km'] = $road['distance_km'];

            $currentLat = $destLat;
            $currentLng = $destLng;
        }
        unset($route);

        // Hitung jarak perjalanan pulang dari stop terakhir kembali ke gudang
        $returnRoad = $distanceService->roadDistance(
            $currentLat,
            $currentLng,
            (float) $warehouse->latitude,
            (float) $warehouse->longitude
        );
        $returnDistanceKm = $returnRoad['distance_km'];

        $oldDriverIds = $shipments->pluck('driver_user_id')->filter()->unique()->toArray();

        DB::transaction(function () use ($validated, $warehouseId, $sortedRoutes, $oldDriverIds, $vehicleId, $returnDistanceKm) {
            $trip = DeliveryTrip::create([
                'trip_number' => 'TRIP-' . now()->format('YmdHis'),
                'trip_date' => $validated['trip_date'],
                'warehouse_id' => $warehouseId,
                'driver_user_id' => $validated['driver_user_id'],
                'vehicle_id' => $vehicleId,
                'status' => 'planned',
                'total_shipments' => count($sortedRoutes),
                'total_estimated_distance_km' => collect($sortedRoutes)->sum('distance_from_previous_km'),
                'return_distance_km' => $returnDistanceKm,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($sortedRoutes as $route) {
                $trip->tripShipments()->create([
                    'shipment_id' => $route['shipment']->id,
                    'route_order' => $route['route_order'],
                    'distance_from_previous_km' => $route['distance_from_previous_km'],
                ]);

                $route['shipment']->update([
                    'driver_user_id' => $validated['driver_user_id'],
                    'vehicle_id' => $vehicleId,
                    'status' => 'assigned',
                ]);
            }

            User::where('id', $validated['driver_user_id'])->update([
                'availability_status' => 'assigned',
            ]);

            // Bebaskan driver lama jika mereka tidak lagi memegang shipment/trip aktif lain
            foreach ($oldDriverIds as $oldDriverId) {
                if ($oldDriverId == $validated['driver_user_id']) {
                    continue;
                }

                $hasOtherActiveShipments = Shipment::where('driver_user_id', $oldDriverId)
                    ->whereIn('status', ['assigned', 'on_delivery', 'delivered', 'returning'])
                    ->whereDoesntHave('tripShipments', function ($query) use ($trip) {
                        $query->where('delivery_trip_id', $trip->id);
                    })
                    ->exists();

                $hasActiveTrips = DeliveryTrip::where('driver_user_id', $oldDriverId)
                    ->where('id', '!=', $trip->id)
                    ->whereIn('status', ['planned', 'on_trip'])
                    ->exists();

                if (!$hasOtherActiveShipments && !$hasActiveTrips) {
                    User::where('id', $oldDriverId)->update([
                        'availability_status' => 'available',
                    ]);
                }
            }
        });

        return redirect()
            ->route('warehouse.delivery-trips.index')
            ->with('success', 'Trip berhasil dibuat dan rute otomatis diurutkan dengan Nearest Neighbor.');
    }
}