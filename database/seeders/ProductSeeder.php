<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $unit = Unit::where('symbol', 'tabung')->first();

        if (! $unit) {
            return;
        }

        Product::updateOrCreate(
            ['sku' => 'LPG-3KG'],
            [
                'name' => 'Gas Elpiji 3 Kg',
                'unit_id' => $unit->id,
                'weight_kg' => 3,
                'is_active' => true,
            ]
        );

        Product::updateOrCreate(
            ['sku' => 'LPG-55KG'],
            [
                'name' => 'Gas Elpiji 5.5 Kg',
                'unit_id' => $unit->id,
                'weight_kg' => 5.5,
                'is_active' => true,
            ]
        );

        Product::updateOrCreate(
            ['sku' => 'LPG-12KG'],
            [
                'name' => 'Gas Elpiji 12 Kg',
                'unit_id' => $unit->id,
                'weight_kg' => 12,
                'is_active' => true,
            ]
        );
    }
}