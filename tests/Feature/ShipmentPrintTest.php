<?php

namespace Tests\Feature;

use App\Models\DeliveryTrip;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\Unit;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipmentPrintTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected DeliveryTrip $trip;
    protected Shipment $shipment;

    protected function setUp(): void
    {
        parent::setUp();

        // Create user
        $this->adminUser = User::factory()->create([
            'role' => 'admin',
        ]);

        // Create pre-requisites
        $warehouse = Warehouse::create([
            'name' => 'Gudang Utama',
            'code' => 'GUD-01',
            'address' => 'Jl. Utama',
            'latitude' => -7.25,
            'longitude' => 112.75,
        ]);

        $driver = User::factory()->create([
            'role' => 'driver',
        ]);

        $vehicle = Vehicle::create([
            'plate_number' => 'L 1234 AB',
            'name' => 'Truk Fuso',
            'fuel_efficiency' => 8.5,
        ]);

        $unit = Unit::create([
            'symbol' => 'tabung',
            'name' => 'Tabung',
        ]);

        $product = Product::create([
            'sku' => 'LPG-3KG',
            'name' => 'Gas Elpiji 3 Kg',
            'unit_id' => $unit->id,
            'weight_kg' => 3.0,
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-001',
            'customer_name' => 'PT. Pelanggan Setia',
            'delivery_address' => 'Jl. Pelanggan No. 10',
            'latitude' => -7.26,
            'longitude' => 112.76,
            'status' => 'pending',
            'order_date' => now()->toDateString(),
            'warehouse_id' => $warehouse->id,
            'created_by' => $this->adminUser->id,
        ]);

        // Create trip
        $this->trip = DeliveryTrip::create([
            'trip_number' => 'TRIP-001',
            'trip_date' => now()->toDateString(),
            'warehouse_id' => $warehouse->id,
            'driver_user_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'status' => 'planned',
            'created_by' => $this->adminUser->id,
        ]);

        // Create shipment
        $this->shipment = Shipment::create([
            'shipment_number' => 'SJ-001959',
            'shipment_date' => now()->toDateString(),
            'warehouse_id' => $warehouse->id,
            'order_id' => $order->id,
            'driver_user_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'status' => 'assigned',
            'notes' => 'Tolong kirim sebelum jam 12 siang.',
            'created_by' => $this->adminUser->id,
        ]);

        // Link shipment to trip
        $this->trip->shipments()->attach($this->shipment->id, [
            'route_order' => 1,
            'distance_from_previous_km' => 5.2,
        ]);

        // Add item
        ShipmentItem::create([
            'shipment_id' => $this->shipment->id,
            'product_id' => $product->id,
            'qty' => 150,
        ]);
    }

    /**
     * Test that guest users are redirected to login.
     */
    public function test_guests_cannot_access_print_views(): void
    {
        $response1 = $this->get(route('shipments.print-surat-jalan', $this->shipment->id));
        $response1->assertRedirect(route('login'));

        $response2 = $this->get(route('delivery-trips.print-all-surat-jalan', $this->trip->id));
        $response2->assertRedirect(route('login'));
    }

    /**
     * Test that authenticated admin users can print single Surat Jalan.
     */
    public function test_authenticated_admin_can_print_single_surat_jalan(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('shipments.print-surat-jalan', $this->shipment->id));

        $response->assertStatus(200);
        $response->assertViewIs('shipments.print-surat-jalan');
        $response->assertSee('PT. SULFATAMA KENCANA');
        $response->assertSee('SURAT JALAN');
        $response->assertSee('SJ-001959');
        $response->assertSee('PT. Pelanggan Setia');
        $response->assertSee('Gas Elpiji 3 Kg');
        $response->assertSee('KIRIM: 150 Tabung');
        $response->assertSee('L 1234 AB');
        $response->assertSee('Tolong kirim sebelum jam 12 siang.');
    }

    /**
     * Test that authenticated admin users can print all Surat Jalans in a trip.
     */
    public function test_authenticated_admin_can_print_all_surat_jalans_in_trip(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('delivery-trips.print-all-surat-jalan', $this->trip->id));

        $response->assertStatus(200);
        $response->assertViewIs('shipments.print-surat-jalan');
        $response->assertSee('PT. SULFATAMA KENCANA');
        $response->assertSee('SJ-001959');
        $response->assertSee('Trip: TRIP-001');
    }
}
