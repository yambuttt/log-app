<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Vehicle;
use App\Models\VehicleCapacity;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil produk LPG
        $lpg3 = Product::where('sku', 'LPG-3KG')->first();
        $lpg5 = Product::where('sku', 'LPG-55KG')->first();
        $lpg12 = Product::where('sku', 'LPG-12KG')->first();

        // 1. KENDARAAN KECIL
        // L300 (Kecil)
        $l300 = Vehicle::updateOrCreate(
            ['plate_number' => 'B 4 BI'],
            [
                'name' => 'L300',
                'vehicle_type' => 'small',
                'fuel_efficiency' => 10.00,
                'is_active' => true,
            ]
        );

        // Kapasitas L300
        if ($lpg12) {
            VehicleCapacity::updateOrCreate(
                ['vehicle_id' => $l300->id, 'product_id' => $lpg12->id],
                ['max_qty' => 5]
            );
        }
        if ($lpg5) {
            VehicleCapacity::updateOrCreate(
                ['vehicle_id' => $l300->id, 'product_id' => $lpg5->id],
                ['max_qty' => 10]
            );
        }
        if ($lpg3) {
            VehicleCapacity::updateOrCreate(
                ['vehicle_id' => $l300->id, 'product_id' => $lpg3->id],
                ['max_qty' => 20]
            );
        }

        // Grand Max (Kecil)
        $grandMax = Vehicle::updateOrCreate(
            ['plate_number' => 'B 1234 XYZ'],
            [
                'name' => 'Grand Max Pick Up',
                'vehicle_type' => 'small',
                'fuel_efficiency' => 12.00,
                'is_active' => true,
            ]
        );

        // Kapasitas Grand Max
        if ($lpg12) {
            VehicleCapacity::updateOrCreate(
                ['vehicle_id' => $grandMax->id, 'product_id' => $lpg12->id],
                ['max_qty' => 8]
            );
        }
        if ($lpg5) {
            VehicleCapacity::updateOrCreate(
                ['vehicle_id' => $grandMax->id, 'product_id' => $lpg5->id],
                ['max_qty' => 15]
            );
        }
        if ($lpg3) {
            VehicleCapacity::updateOrCreate(
                ['vehicle_id' => $grandMax->id, 'product_id' => $lpg3->id],
                ['max_qty' => 30]
            );
        }

        // 2. KENDARAAN BESAR
        // Colt Diesel Double (CDD) (Besar)
        $cdd = Vehicle::updateOrCreate(
            ['plate_number' => 'B 9876 CDD'],
            [
                'name' => 'Colt Diesel Double (CDD)',
                'vehicle_type' => 'large',
                'fuel_efficiency' => 8.00,
                'is_active' => true,
            ]
        );

        // Kapasitas CDD
        if ($lpg12) {
            VehicleCapacity::updateOrCreate(
                ['vehicle_id' => $cdd->id, 'product_id' => $lpg12->id],
                ['max_qty' => 80]
            );
        }
        if ($lpg5) {
            VehicleCapacity::updateOrCreate(
                ['vehicle_id' => $cdd->id, 'product_id' => $lpg5->id],
                ['max_qty' => 150]
            );
        }
        if ($lpg3) {
            VehicleCapacity::updateOrCreate(
                ['vehicle_id' => $cdd->id, 'product_id' => $lpg3->id],
                ['max_qty' => 300]
            );
        }

        // Fuso (Besar)
        $fuso = Vehicle::updateOrCreate(
            ['plate_number' => 'B 5555 FSO'],
            [
                'name' => 'Truk Fuso',
                'vehicle_type' => 'large',
                'fuel_efficiency' => 6.00,
                'is_active' => true,
            ]
        );

        // Kapasitas Truk Fuso
        if ($lpg12) {
            VehicleCapacity::updateOrCreate(
                ['vehicle_id' => $fuso->id, 'product_id' => $lpg12->id],
                ['max_qty' => 150]
            );
        }
        if ($lpg5) {
            VehicleCapacity::updateOrCreate(
                ['vehicle_id' => $fuso->id, 'product_id' => $lpg5->id],
                ['max_qty' => 300]
            );
        }
        if ($lpg3) {
            VehicleCapacity::updateOrCreate(
                ['vehicle_id' => $fuso->id, 'product_id' => $lpg3->id],
                ['max_qty' => 600]
            );
        }
    }
}
