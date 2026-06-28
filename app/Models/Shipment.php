<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    protected $fillable = [
        'shipment_number',
        'shipment_date',
        'warehouse_id',
        'order_id',
        'driver_user_id',
        'vehicle_id',
        'status',
        'notes',
        'created_by',
        'estimated_distance_km',
        'estimated_duration_minutes',
        'google_maps_url',
    ];

    protected static function booted(): void
    {
        static::updated(function (Shipment $shipment) {
            if ($shipment->isDirty('status') && $shipment->status === 'on_delivery') {
                $inventoryService = app(\App\Services\InventoryService::class);
                foreach ($shipment->items as $item) {
                    $inventoryService->stockOut([
                        'transaction_date' => now()->toDateString(),
                        'warehouse_id' => $shipment->warehouse_id,
                        'product_id' => $item->product_id,
                        'qty' => $item->qty,
                        'reference_type' => Shipment::class,
                        'reference_id' => $shipment->id,
                        'notes' => 'Pengiriman barang untuk shipment #' . $shipment->shipment_number,
                        'created_by' => auth()->id() ?? $shipment->created_by,
                    ]);
                }
            }
        });
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function tripShipments(): HasMany
    {
        return $this->hasMany(DeliveryTripShipment::class);
    }
    public function deliveryTrips()
    {
        return $this->belongsToMany(DeliveryTrip::class, 'delivery_trip_shipments')
            ->withPivot(['route_order', 'distance_from_previous_km'])
            ->withTimestamps();
    }
    public function getGoogleMapsUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }

        if ($this->order && $this->order->delivery_latitude && $this->order->delivery_longitude) {
            return 'https://www.google.com/maps/dir/?api=1&destination=' . $this->order->delivery_latitude . ',' . $this->order->delivery_longitude;
        }

        return null;
    }

    public function getEstimatedDistanceKmAttribute($value)
    {
        if ($value !== null) {
            return $value;
        }
        if ($this->order && $this->order->delivery_latitude && $this->order->delivery_longitude) {
            $warehouse = $this->warehouse ?? \App\Models\Warehouse::first();
            if ($warehouse && $warehouse->latitude && $warehouse->longitude) {
                return (new \App\Services\DistanceService())->haversine(
                    (float) $warehouse->latitude, (float) $warehouse->longitude,
                    (float) $this->order->delivery_latitude, (float) $this->order->delivery_longitude
                );
            }
        }
        return null;
    }

    public function getEstimatedDurationMinutesAttribute($value)
    {
        if ($value !== null) {
            return $value;
        }
        $distance = $this->estimated_distance_km;
        return $distance !== null ? (new \App\Services\DistanceService())->estimateMinutes($distance) : null;
    }
}