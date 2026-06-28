<?php

namespace Tests\Feature;

use App\Models\DeliveryTrip;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\Stock;
use App\Models\Unit;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Warehouse;
use App\Models\Waste;
use App\Models\StockOpname;
use App\Models\InventoryMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $warehouseUser;
    protected User $driverUser;
    protected Warehouse $warehouse;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create(['role' => 'admin']);
        
        $this->warehouse = Warehouse::create([
            'name' => 'Gudang Utama',
            'code' => 'GUD-01',
            'address' => 'Jl. Utama',
            'latitude' => -7.25,
            'longitude' => 112.75,
        ]);

        $this->warehouseUser = User::factory()->create([
            'role' => 'warehouse',
            'warehouse_id' => $this->warehouse->id,
        ]);

        $this->driverUser = User::factory()->create([
            'role' => 'driver',
        ]);

        $unit = Unit::create([
            'symbol' => 'tabung',
            'name' => 'Tabung',
        ]);

        $this->product = Product::create([
            'sku' => 'LPG-3KG',
            'name' => 'Gas Elpiji 3 Kg',
            'unit_id' => $unit->id,
            'weight_kg' => 3.0,
            'is_active' => true,
        ]);

        // Initialize stock with 100 items
        Stock::create([
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'qty' => 100.0,
        ]);
    }

    /**
     * Test stock reduction and movement log when shipment transitions to on_delivery via single shipment start.
     */
    public function test_single_shipment_start_reducts_stock(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-001',
            'customer_name' => 'PT. Pelanggan',
            'delivery_address' => 'Jl. Pelanggan',
            'status' => 'pending',
            'order_date' => now()->toDateString(),
            'warehouse_id' => $this->warehouse->id,
            'created_by' => $this->adminUser->id,
        ]);

        $shipment = Shipment::create([
            'shipment_number' => 'SHP-001',
            'shipment_date' => now()->toDateString(),
            'warehouse_id' => $this->warehouse->id,
            'order_id' => $order->id,
            'driver_user_id' => $this->driverUser->id,
            'status' => 'assigned',
            'created_by' => $this->adminUser->id,
        ]);

        ShipmentItem::create([
            'shipment_id' => $shipment->id,
            'product_id' => $this->product->id,
            'qty' => 10,
        ]);

        // Act: Start shipment
        $response = $this->actingAs($this->driverUser)
            ->post(route('driver.shipments.start', $shipment->id));

        $response->assertRedirect();
        
        // Assert: stock decreased from 100 to 90
        $this->assertDatabaseHas('stocks', [
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'qty' => 90.0,
        ]);

        // Assert: movement created
        $this->assertDatabaseHas('inventory_movements', [
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'movement_type' => 'goods_out',
            'qty_out' => 10.0,
            'stock_before' => 100.0,
            'stock_after' => 90.0,
            'reference_type' => Shipment::class,
            'reference_id' => $shipment->id,
        ]);
    }

    /**
     * Test stock reduction when starting a delivery trip (bulk update).
     */
    public function test_delivery_trip_start_deducts_stock_for_all_shipments(): void
    {
        $vehicle = Vehicle::create([
            'plate_number' => 'L 1234 AB',
            'name' => 'Truk Fuso',
            'fuel_efficiency' => 8.5,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-002',
            'customer_name' => 'PT. Pelanggan 2',
            'delivery_address' => 'Jl. Pelanggan 2',
            'status' => 'pending',
            'order_date' => now()->toDateString(),
            'warehouse_id' => $this->warehouse->id,
            'created_by' => $this->adminUser->id,
        ]);

        $trip = DeliveryTrip::create([
            'trip_number' => 'TRIP-002',
            'trip_date' => now()->toDateString(),
            'warehouse_id' => $this->warehouse->id,
            'driver_user_id' => $this->driverUser->id,
            'vehicle_id' => $vehicle->id,
            'status' => 'planned',
            'created_by' => $this->adminUser->id,
        ]);

        $shipment = Shipment::create([
            'shipment_number' => 'SHP-002',
            'shipment_date' => now()->toDateString(),
            'warehouse_id' => $this->warehouse->id,
            'order_id' => $order->id,
            'driver_user_id' => $this->driverUser->id,
            'vehicle_id' => $vehicle->id,
            'status' => 'assigned',
            'created_by' => $this->adminUser->id,
        ]);

        $trip->shipments()->attach($shipment->id, [
            'route_order' => 1,
            'distance_from_previous_km' => 5.0,
        ]);

        ShipmentItem::create([
            'shipment_id' => $shipment->id,
            'product_id' => $this->product->id,
            'qty' => 35,
        ]);

        // Act: Start delivery trip
        $response = $this->actingAs($this->driverUser)
            ->post(route('driver.delivery-trips.start', $trip->id));

        $response->assertRedirect();

        // Assert: stock decreased from 100 to 65
        $this->assertDatabaseHas('stocks', [
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'qty' => 65.0,
        ]);

        // Assert: movement created
        $this->assertDatabaseHas('inventory_movements', [
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'movement_type' => 'goods_out',
            'qty_out' => 35.0,
            'stock_before' => 100.0,
            'stock_after' => 65.0,
            'reference_type' => Shipment::class,
            'reference_id' => $shipment->id,
        ]);
    }

    /**
     * Test recording waste via controller decreases stock.
     */
    public function test_recording_waste_decreases_stock(): void
    {
        $response = $this->actingAs($this->warehouseUser)
            ->post(route('warehouse.wastes.store'), [
                'waste_date' => now()->toDateString(),
                'product_id' => $this->product->id,
                'qty' => 15,
                'reason' => 'Broken unit',
                'notes' => 'Damaged during forklift operation',
            ]);

        $response->assertRedirect(route('warehouse.wastes.index'));

        // Assert: stock decreased from 100 to 85
        $this->assertDatabaseHas('stocks', [
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'qty' => 85.0,
        ]);

        // Assert: movement log exists
        $this->assertDatabaseHas('inventory_movements', [
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'movement_type' => 'waste',
            'qty_out' => 15.0,
            'stock_before' => 100.0,
            'stock_after' => 85.0,
        ]);
    }

    /**
     * Test recording waste with insufficient stock redirects back with validation error.
     */
    public function test_recording_waste_with_insufficient_stock_fails(): void
    {
        $response = $this->actingAs($this->warehouseUser)
            ->from(route('warehouse.wastes.create'))
            ->post(route('warehouse.wastes.store'), [
                'waste_date' => now()->toDateString(),
                'product_id' => $this->product->id,
                'qty' => 150, // More than the 100 in stock
                'reason' => 'Damaged',
            ]);

        $response->assertRedirect(route('warehouse.wastes.create'));
        $response->assertSessionHasErrors('qty');

        // Assert: stock remains 100
        $this->assertEquals(100, Stock::where('warehouse_id', $this->warehouse->id)->where('product_id', $this->product->id)->first()->qty);
    }

    /**
     * Test stock opname adjusts stock.
     */
    public function test_stock_opname_adjusts_stock_correctly(): void
    {
        // Act: Physical qty is 105 (adjustment of +5)
        $response = $this->actingAs($this->warehouseUser)
            ->post(route('warehouse.stock-opnames.store'), [
                'opname_date' => now()->toDateString(),
                'product_id' => $this->product->id,
                'physical_qty' => 105,
                'notes' => 'Found extra tabung in back corner',
            ]);

        $response->assertRedirect(route('warehouse.stock-opnames.index'));

        // Assert: stock increased to 105
        $this->assertDatabaseHas('stocks', [
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'qty' => 105.0,
        ]);

        // Assert: movement logged as opname_plus
        $this->assertDatabaseHas('inventory_movements', [
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'movement_type' => 'opname_plus',
            'qty_in' => 5.0,
            'stock_before' => 100.0,
            'stock_after' => 105.0,
        ]);
    }
}
