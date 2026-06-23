<?php

namespace App\Services;

class DistanceService
{
    public function haversine(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    public function estimateMinutes(float $distanceKm, float $avgSpeedKmPerHour = 30): int
    {
        if ($avgSpeedKmPerHour <= 0) {
            return 0;
        }

        return (int) ceil(($distanceKm / $avgSpeedKmPerHour) * 60);
    }

    public function roadDistance(float $lat1, float $lon1, float $lat2, float $lon2): array
    {
        $haversine = $this->haversine($lat1, $lon1, $lat2, $lon2);
        $fallback = [
            'distance_km' => $haversine,
            'duration_minutes' => $this->estimateMinutes($haversine),
        ];

        try {
            // OSRM format: lng1,lat1;lng2,lat2
            $url = "http://router.project-osrm.org/route/v1/driving/{$lon1},{$lat1};{$lon2},{$lat2}?overview=false";
            
            $client = new \GuzzleHttp\Client(['timeout' => 3.0]);
            $response = $client->get($url);
            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['code']) && $data['code'] === 'Ok' && isset($data['routes'][0])) {
                $route = $data['routes'][0];
                $distanceKm = round($route['distance'] / 1000, 2);
                $durationMinutes = (int) ceil($route['duration'] / 60);

                return [
                    'distance_km' => $distanceKm,
                    'duration_minutes' => $durationMinutes,
                ];
            }
        } catch (\Exception $e) {
            // Fallback silently to Haversine
        }

        return $fallback;
    }

    public function buildGoogleMapsUrl(float $lat, float $lng): string
    {
        return 'https://www.google.com/maps/dir/?api=1&destination=' . $lat . ',' . $lng;
    }
}