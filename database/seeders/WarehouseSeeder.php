<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        Warehouse::updateOrCreate(
            ['code' => 'GU'],
            [
                'name' => 'Gudang Utama',
                'address' => 'Gudang pusat perusahaan',
                'is_active' => true,
            ]
        );
    }
}