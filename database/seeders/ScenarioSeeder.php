<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Vehicle;
use App\Models\DriverVehicleAssignment;
use Illuminate\Database\Seeder;

class ScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $warehouse = Warehouse::first();
        if (!$warehouse) {
            return;
        }

        // Ambil produk LPG
        $lpg3 = Product::where('sku', 'LPG-3KG')->first();
        $lpg5 = Product::where('sku', 'LPG-55KG')->first();
        $lpg12 = Product::where('sku', 'LPG-12KG')->first();

        // 1. Buat Driver Baru
        $driverBesar = User::updateOrCreate(
            ['email' => 'driver_besar@perusahaan.com'],
            [
                'name' => 'Driver Truk Besar (CDD)',
                'password' => 'password',
                'role' => 'driver',
                'is_active' => true,
                'availability_status' => 'available',
            ]
        );

        $driverKecil = User::updateOrCreate(
            ['email' => 'driver_kecil@perusahaan.com'],
            [
                'name' => 'Driver Pick Up (L300)',
                'password' => 'password',
                'role' => 'driver',
                'is_active' => true,
                'availability_status' => 'available',
            ]
        );

        // Ambil kendaraan
        $cdd = Vehicle::where('plate_number', 'B 9876 CDD')->first(); // CDD (Besar)
        $l300 = Vehicle::where('plate_number', 'B 4 BI')->first();    // L300 (Kecil)

        $today = '2026-06-16';

        // 2. Beri Assignment Kendaraan Hari Ini
        if ($cdd) {
            DriverVehicleAssignment::updateOrCreate(
                [
                    'driver_user_id' => $driverBesar->id,
                    'assignment_date' => $today,
                ],
                [
                    'vehicle_id' => $cdd->id,
                    'notes' => 'Truk CDD untuk pengiriman besar',
                ]
            );
        }

        if ($l300) {
            DriverVehicleAssignment::updateOrCreate(
                [
                    'driver_user_id' => $driverKecil->id,
                    'assignment_date' => $today,
                ],
                [
                    'vehicle_id' => $l300->id,
                    'notes' => 'Pick Up L300 untuk pengiriman kecil',
                ]
            );
        }

        // 3. Buat 4 Pesanan Campuran
        // PESANAN BESAR 1 (Sarah)
        $orderSarah = Order::create([
            'order_number' => 'ORD-SARAH-LARGE-' . time(),
            'order_date' => $today,
            'warehouse_id' => $warehouse->id,
            'customer_name' => 'Sarah (Truk)',
            'customer_phone' => '0811111111',
            'delivery_address' => 'Jl. Ahmad Yani No.117, Surabaya',
            'delivery_latitude' => -7.3293698,
            'delivery_longitude' => 112.7311686,
            'status' => 'draft',
            'created_by' => 2,
        ]);
        $orderSarah->items()->create(['product_id' => $lpg12->id, 'qty' => 10]); // melebihi L300 (max 5)
        $orderSarah->items()->create(['product_id' => $lpg5->id, 'qty' => 12]);  // melebihi L300 (max 10)
        $orderSarah->items()->create(['product_id' => $lpg3->id, 'qty' => 25]);  // melebihi L300 (max 20)

        // PESANAN BESAR 2 (Budi)
        $orderBudi = Order::create([
            'order_number' => 'ORD-BUDI-LARGE-' . time(),
            'order_date' => $today,
            'warehouse_id' => $warehouse->id,
            'customer_name' => 'Budi (Truk)',
            'customer_phone' => '0822222222',
            'delivery_address' => 'Jl. Joyoboyo No.19, Surabaya',
            'delivery_latitude' => -7.2990106,
            'delivery_longitude' => 112.7351577,
            'status' => 'draft',
            'created_by' => 2,
        ]);
        $orderBudi->items()->create(['product_id' => $lpg12->id, 'qty' => 12]); // melebihi L300 (max 5)
        $orderBudi->items()->create(['product_id' => $lpg5->id, 'qty' => 15]);  // melebihi L300 (max 10)
        $orderBudi->items()->create(['product_id' => $lpg3->id, 'qty' => 35]);  // melebihi L300 (max 20)

        // PESANAN KECIL 1 (Cici)
        $orderCici = Order::create([
            'order_number' => 'ORD-CICI-SMALL-' . time(),
            'order_date' => $today,
            'warehouse_id' => $warehouse->id,
            'customer_name' => 'Cici (Kecil)',
            'customer_phone' => '0833333333',
            'delivery_address' => 'Jl. Darmo No.10, Surabaya',
            'delivery_latitude' => -7.289123,
            'delivery_longitude' => 112.738123,
            'status' => 'draft',
            'created_by' => 2,
        ]);
        $orderCici->items()->create(['product_id' => $lpg12->id, 'qty' => 2]);
        $orderCici->items()->create(['product_id' => $lpg5->id, 'qty' => 3]);
        $orderCici->items()->create(['product_id' => $lpg3->id, 'qty' => 5]);

        // PESANAN KECIL 2 (Dedi)
        $orderDedi = Order::create([
            'order_number' => 'ORD-DEDI-SMALL-' . time(),
            'order_date' => $today,
            'warehouse_id' => $warehouse->id,
            'customer_name' => 'Dedi (Kecil)',
            'customer_phone' => '0844444444',
            'delivery_address' => 'Jl. Basuki Rahmat No.5, Surabaya',
            'delivery_latitude' => -7.271234,
            'delivery_longitude' => 112.741234,
            'status' => 'draft',
            'created_by' => 2,
        ]);
        $orderDedi->items()->create(['product_id' => $lpg12->id, 'qty' => 3]);
        $orderDedi->items()->create(['product_id' => $lpg5->id, 'qty' => 4]);
        $orderDedi->items()->create(['product_id' => $lpg3->id, 'qty' => 6]);

        // 4. Buat Shipment untuk masing-masing order
        $orders = [$orderSarah, $orderBudi, $orderCici, $orderDedi];
        foreach ($orders as $index => $order) {
            $shipment = Shipment::create([
                'shipment_number' => 'SHP-SCENARIO-' . ($index + 1) . '-' . time(),
                'shipment_date' => $today,
                'warehouse_id' => $warehouse->id,
                'order_id' => $order->id,
                'driver_user_id' => null,
                'vehicle_id' => null,
                'status' => 'waiting_driver',
                'notes' => 'Skenario Uji Rute NN',
                'created_by' => 2,
            ]);

            foreach ($order->items as $item) {
                $shipment->items()->create([
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                ]);
            }
        }
    }
}
