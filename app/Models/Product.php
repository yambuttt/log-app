<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'unit_id',
        'name',
        'sku',
        'weight_kg',
        'harga_modal',
        'harga_jual',
        'minimum_stock',
        'is_active',
    ];

    protected $casts = [
        'harga_modal' => 'float',
        'harga_jual' => 'float',
        'minimum_stock' => 'float',
        'is_active' => 'boolean',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function getKeuntunganAttribute(): float
    {
        return (float) ($this->harga_jual - $this->harga_modal);
    }

    public function getMarginAttribute(): float
    {
        if ($this->harga_jual <= 0) {
            return 0;
        }
        return ($this->keuntungan / $this->harga_jual) * 100;
    }
}