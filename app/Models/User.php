<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'warehouse_id',
        'phone',
        'is_active',
        'availability_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function driverProfile(): HasOne
    {
        return $this->hasOne(DriverProfile::class);
    }

    public function vehicleAssignments(): HasMany
    {
        return $this->hasMany(DriverVehicleAssignment::class, 'driver_user_id');
    }
    public function isAvailableDriver(): bool
    {
        return $this->role === 'driver'
            && $this->is_active
            && $this->availability_status === 'available';
    }
    public function deliveryTrips(): HasMany
    {
        return $this->hasMany(DeliveryTrip::class, 'driver_user_id');
    }
}