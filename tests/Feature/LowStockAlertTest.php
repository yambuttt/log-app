<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Unit;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LowStockAlertTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected Warehouse $warehouse;
    protected Unit $unit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create(['role' => 'admin']);
        
        $this->warehouse = Warehouse::create([
            'name' => 'Gudang Alert',
            'code' => 'GUD-AL',
            'address' => 'Jl. Alert',
            'latitude' => -7.25,
            'longitude' => 112.75,
        ]);

        $this->unit = Unit::create([
            'symbol' => 'tabung',
            'name' => 'Tabung',
        ]);
    }

    /**
     * Test storing product with minimum stock.
     */
    public function test_can_create_product_with_minimum_stock(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.products.store'), [
                'name' => 'LPG 3kg Baru',
                'sku' => 'LPG-3KG-NEW',
                'unit_id' => $this->unit->id,
                'weight_kg' => 3.0,
                'minimum_stock' => 15,
                'is_active' => 1,
            ]);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'sku' => 'LPG-3KG-NEW',
            'minimum_stock' => 15.00,
        ]);
    }

    /**
     * Test admin dashboard shows low stock warning when stock is below or equal to minimum.
     */
    public function test_dashboard_shows_alert_when_stock_reaches_minimum(): void
    {
        // 1. Create a product with min stock of 10
        $product = Product::create([
            'name' => 'Gas Elpiji 3 Kg',
            'sku' => 'LPG-3KG',
            'unit_id' => $this->unit->id,
            'weight_kg' => 3.0,
            'minimum_stock' => 10.00,
            'is_active' => true,
        ]);

        // 2. Create stock of 8 (below minimum)
        Stock::create([
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $product->id,
            'qty' => 8.0,
        ]);

        // 3. Get Dashboard
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Peringatan: Stok Menipis!');
        $response->assertSee('Gas Elpiji 3 Kg');
        $response->assertSee('Gudang: Gudang Alert');
        $response->assertSee('Stok: 8 tabung');
        $response->assertSee('Min: 10');
    }

    /**
     * Test admin dashboard does not show low stock warning when stock is above minimum.
     */
    public function test_dashboard_does_not_show_alert_when_stock_above_minimum(): void
    {
        // 1. Create a product with min stock of 10
        $product = Product::create([
            'name' => 'Gas Elpiji 3 Kg',
            'sku' => 'LPG-3KG',
            'unit_id' => $this->unit->id,
            'weight_kg' => 3.0,
            'minimum_stock' => 10.00,
            'is_active' => true,
        ]);

        // 2. Create stock of 12 (above minimum)
        Stock::create([
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $product->id,
            'qty' => 12.0,
        ]);

        // 3. Get Dashboard
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertDontSee('Peringatan: Stok Menipis!');
    }

    /**
     * Test products with minimum stock set to 0 do not trigger alerts even if stock is 0.
     */
    public function test_zero_minimum_stock_does_not_trigger_alert(): void
    {
        $product = Product::create([
            'name' => 'Gas Non-Alert',
            'sku' => 'LPG-0KG',
            'unit_id' => $this->unit->id,
            'weight_kg' => 3.0,
            'minimum_stock' => 0.00, // 0 means no threshold configured
            'is_active' => true,
        ]);

        Stock::create([
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $product->id,
            'qty' => 0.0,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertDontSee('Peringatan: Stok Menipis!');
    }
}
