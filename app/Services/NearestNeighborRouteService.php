<?php

namespace App\Services;

use App\Models\Shipment;

class NearestNeighborRouteService
{
    public function __construct(
        protected DistanceService $distanceService
    ) {
    }

    /**
     * @param \Illuminate\Support\Collection<int, Shipment> $shipments
     * @return array<int, array{
     *     shipment: Shipment,
     *     route_order: int,
     *     distance_from_previous_km: float
     * }>
     */
    public function generate($shipments, float $startLat, float $startLng): array
    {
        $remaining = $shipments->values()->all();
        $result = [];

        $currentLat = $startLat;
        $currentLng = $startLng;
        $routeOrder = 1;

        while (! empty($remaining)) {
            $nearestIndex = null;
            $nearestDistance = null;

            foreach ($remaining as $index => $shipment) {
                $lat = (float) optional($shipment->order)->delivery_latitude;
                $lng = (float) optional($shipment->order)->delivery_longitude;

                if (! $lat || ! $lng) {
                    continue;
                }

                $distance = $this->distanceService->haversine(
                    $currentLat,
                    $currentLng,
                    $lat,
                    $lng
                );

                if ($nearestDistance === null || $distance < $nearestDistance) {
                    $nearestDistance = $distance;
                    $nearestIndex = $index;
                }
            }

            if ($nearestIndex === null) {
                break;
            }

            $nearestShipment = $remaining[$nearestIndex];
            $nearestLat = (float) $nearestShipment->order->delivery_latitude;
            $nearestLng = (float) $nearestShipment->order->delivery_longitude;

            $result[] = [
                'shipment' => $nearestShipment,
                'route_order' => $routeOrder,
                'distance_from_previous_km' => $nearestDistance ?? 0,
            ];

            $currentLat = $nearestLat;
            $currentLng = $nearestLng;
            $routeOrder++;

            unset($remaining[$nearestIndex]);
            $remaining = array_values($remaining);
        }

        return $result;
    }
}