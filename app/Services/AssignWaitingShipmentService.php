<?php

namespace App\Services;

use App\Models\DriverVehicleAssignment;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AssignWaitingShipmentService
{
    public function assignToDriver(User $driver, string $date): ?Shipment
    {
        if ($driver->role !== 'driver' || ! $driver->is_active || $driver->availability_status !== 'available') {
            return null;
        }

        $assignment = DriverVehicleAssignment::with('vehicle')
            ->where('driver_user_id', $driver->id)
            ->where('assignment_date', $date)
            ->first();

        if (! $assignment || ! $assignment->vehicle || ! $assignment->vehicle->is_active) {
            return null;
        }

        $waitingShipments = Shipment::with(['order.items.product'])
            ->where('status', 'waiting_driver')
            ->whereDate('shipment_date', $date)
            ->orderBy('created_at')
            ->get();

        $autoAssignShipmentService = app(AutoAssignShipmentService::class);

        foreach ($waitingShipments as $shipment) {
            $result = $autoAssignShipmentService->assignSpecificDriver(
                $shipment->order,
                $date,
                $driver,
                $assignment->vehicle
            );

            if ($result['matched']) {
                DB::transaction(function () use ($shipment, $driver, $assignment) {
                    $shipment->update([
                        'driver_user_id' => $driver->id,
                        'vehicle_id' => $assignment->vehicle_id,
                        'status' => 'assigned',
                    ]);

                    $shipment->order->update([
                        'status' => 'ready',
                    ]);

                    $driver->update([
                        'availability_status' => 'assigned',
                    ]);
                });

                return $shipment->fresh(['order', 'driver', 'vehicle']);
            }
        }

        return null;
    }
}