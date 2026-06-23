<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $warehouse = Warehouse::first();

        if (! $warehouse) {
            return;
        }

        $products = Product::all();

        foreach ($products as $product) {
            Stock::firstOrCreate(
                [
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                ],
                [
                    'qty' => 0,
                ]
            );
        }
    }
}