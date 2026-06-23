<?php

namespace App\Services;

use App\Models\DriverVehicleAssignment;
use App\Models\Order;
use App\Models\User;
use App\Models\VehicleCapacity;

class AutoAssignShipmentService
{
    public function assign(Order $order, string $shipmentDate): array
    {
        $drivers = User::where('role', 'driver')
            ->where('is_active', true)
            ->where('availability_status', 'available')
            ->orderBy('name')
            ->get();

        foreach ($drivers as $driver) {
            $assignment = DriverVehicleAssignment::with('vehicle')
                ->where('driver_user_id', $driver->id)
                ->where('assignment_date', $shipmentDate)
                ->first();

            if (!$assignment || !$assignment->vehicle || !$assignment->vehicle->is_active) {
                continue;
            }

            $capacityErrors = $this->validateOrderAgainstVehicle($order, $assignment->vehicle_id);

            if (empty($capacityErrors)) {
                return [
                    'matched' => true,
                    'driver' => $driver,
                    'vehicle' => $assignment->vehicle,
                    'errors' => [],
                ];
            }
        }

        return [
            'matched' => false,
            'driver' => null,
            'vehicle' => null,
            'errors' => ['Belum ada driver available dengan kendaraan yang sesuai kapasitas pesanan.'],
        ];
    }

    protected function validateOrderAgainstVehicle(Order $order, int $vehicleId): array
    {
        $errors = [];

        $capacities = VehicleCapacity::where('vehicle_id', $vehicleId)
            ->get()
            ->keyBy('product_id');

        foreach ($order->items as $item) {
            $capacity = $capacities->get($item->product_id);

            if (!$capacity) {
                $errors[] = 'Kapasitas untuk produk "' . ($item->product->name ?? 'Unknown') . '" belum diatur.';
                continue;
            }

            if ($item->qty > $capacity->max_qty) {
                $errors[] = 'Produk "' . ($item->product->name ?? 'Unknown') . '" melebihi kapasitas kendaraan.';
            }
        }

        return $errors;
    }

    public function assignSpecificDriver(Order $order, string $shipmentDate, User $driver, $vehicle): array
    {
        if (!$driver->is_active || $driver->availability_status !== 'available') {
            return [
                'matched' => false,
                'driver' => null,
                'vehicle' => null,
                'errors' => ['Driver tidak available.'],
            ];
        }

        if (!$vehicle || !$vehicle->is_active) {
            return [
                'matched' => false,
                'driver' => null,
                'vehicle' => null,
                'errors' => ['Kendaraan driver tidak aktif.'],
            ];
        }

        $capacityErrors = $this->validateOrderAgainstVehicle($order, $vehicle->id);

        if (!empty($capacityErrors)) {
            return [
                'matched' => false,
                'driver' => null,
                'vehicle' => null,
                'errors' => $capacityErrors,
            ];
        }

        return [
            'matched' => true,
            'driver' => $driver,
            'vehicle' => $vehicle,
            'errors' => [],
        ];
    }
}