<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryTrip extends Model
{
    protected $fillable = [
        'trip_number',
        'trip_date',
        'warehouse_id',
        'driver_user_id',
        'vehicle_id',
        'status',
        'total_shipments',
        'total_estimated_distance_km',
        'return_distance_km',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'return_distance_km' => 'float',
        'total_estimated_distance_km' => 'float',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function tripShipments(): HasMany
    {
        return $this->hasMany(DeliveryTripShipment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function shipments()
    {
        return $this->belongsToMany(Shipment::class, 'delivery_trip_shipments')
            ->withPivot(['route_order', 'distance_from_previous_km'])
            ->withTimestamps()
            ->orderBy('delivery_trip_shipments.route_order');
    }

    public function getReturnDistanceKmAttribute($value)
    {
        if ($value !== null) {
            return (float) $value;
        }

        $lastShipment = $this->shipments->last();
        if ($lastShipment && $lastShipment->order && $lastShipment->order->delivery_latitude && $lastShipment->order->delivery_longitude) {
            $warehouse = $this->warehouse ?? \App\Models\Warehouse::first();
            if ($warehouse && $warehouse->latitude && $warehouse->longitude) {
                return (new \App\Services\DistanceService())->haversine(
                    (float) $lastShipment->order->delivery_latitude, (float) $lastShipment->order->delivery_longitude,
                    (float) $warehouse->latitude, (float) $warehouse->longitude
                );
            }
        }

        return 0.0;
    }

    public function getTotalTripDistanceKmAttribute()
    {
        return (float) $this->total_estimated_distance_km + (float) $this->return_distance_km;
    }

    public function getFuelConsumedLitersAttribute()
    {
        $efficiency = $this->vehicle ? (float) $this->vehicle->fuel_efficiency : 0.0;
        if ($efficiency <= 0) {
            return 0.0;
        }
        return round($this->total_trip_distance_km / $efficiency, 2);
    }
}