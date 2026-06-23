<?php

namespace App\Services;

use App\Models\Order;
use App\Models\VehicleCapacity;

class ShipmentCapacityService
{
    public function validateOrderAgainstVehicle(Order $order, int $vehicleId): array
    {
        $errors = [];

        $capacities = VehicleCapacity::where('vehicle_id', $vehicleId)
            ->get()
            ->keyBy('product_id');

        foreach ($order->items as $item) {
            $capacity = $capacities->get($item->product_id);

            if (! $capacity) {
                $errors[] = 'Kapasitas untuk produk "' . ($item->product->name ?? 'Unknown') . '" belum diatur pada kendaraan ini.';
                continue;
            }

            if ($item->qty > $capacity->max_qty) {
                $errors[] = 'Produk "' . ($item->product->name ?? 'Unknown') . '" melebihi kapasitas kendaraan. Maksimal: '
                    . $capacity->max_qty . ', diminta: ' . rtrim(rtrim(number_format($item->qty, 2, '.', ''), '0'), '.');
            }
        }

        return $errors;
    }
}