<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'name',
        'plate_number',
        'vehicle_type',
        'fuel_efficiency',
        'is_active',
        'status',
        'maintenance_notes',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'fuel_efficiency' => 'float',
        ];
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(VehicleMaintenanceLog::class)->orderBy('start_date', 'desc');
    }

    public function capacities(): HasMany
    {
        return $this->hasMany(VehicleCapacity::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(DriverVehicleAssignment::class);
    }

    public function deliveryTrips(): HasMany
    {
        return $this->hasMany(DeliveryTrip::class);
    }

    public function activeTrip()
    {
        return $this->hasOne(DeliveryTrip::class)
            ->whereIn('status', ['on_trip', 'returning'])
            ->with('driver')
            ->latest();
    }

    public function todayAssignment()
    {
        return $this->hasOne(DriverVehicleAssignment::class)
            ->where('assignment_date', now()->toDateString())
            ->with('driver');
    }
}