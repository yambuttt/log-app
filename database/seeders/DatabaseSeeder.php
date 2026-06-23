<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            WarehouseSeeder::class,
            UnitSeeder::class,
            ProductSeeder::class,
            StockSeeder::class,
            VehicleSeeder::class,
        ]);

        $warehouse = \App\Models\Warehouse::first();

        User::updateOrCreate(
            ['email' => 'admin@perusahaan.com'],
            [
                'name' => 'Administrator',
                'password' => 'password',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'gudang@perusahaan.com'],
            [
                'name' => 'Staff Gudang',
                'password' => 'password',
                'role' => 'warehouse',
                'warehouse_id' => $warehouse ? $warehouse->id : null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'driver@perusahaan.com'],
            [
                'name' => 'Driver Utama',
                'password' => 'password',
                'role' => 'driver',
            ]
        );
    }
}