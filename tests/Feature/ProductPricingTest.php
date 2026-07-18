<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPricingTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Unit $unit;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $this->unit = Unit::create(['symbol' => 'pcs', 'name' => 'Pieces']);
    }

    public function test_product_calculates_profit_and_margin_correctly(): void
    {
        $product = Product::create([
            'sku' => 'PRD-1',
            'name' => 'Test Product',
            'unit_id' => $this->unit->id,
            'weight_kg' => 1.0,
            'harga_modal' => 10000,
            'harga_jual' => 15000,
            'is_active' => true,
        ]);

        $this->assertEquals(5000, $product->keuntungan);
        $this->assertEquals(33.333333333333336, $product->margin);
    }

    public function test_product_margin_returns_zero_when_selling_price_is_zero(): void
    {
        $product = Product::create([
            'sku' => 'PRD-2',
            'name' => 'Zero Sale Product',
            'unit_id' => $this->unit->id,
            'harga_modal' => 10000,
            'harga_jual' => 0,
            'is_active' => true,
        ]);

        $this->assertEquals(-10000, $product->keuntungan);
        $this->assertEquals(0, $product->margin);
    }

    public function test_admin_can_create_product_with_pricing(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'sku' => 'NEW-PRD',
                'name' => 'Fresh Product',
                'unit_id' => $this->unit->id,
                'weight_kg' => 2.0,
                'harga_modal' => 8000,
                'harga_jual' => 12000,
                'minimum_stock' => 5,
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'sku' => 'NEW-PRD',
            'harga_modal' => 8000,
            'harga_jual' => 12000,
        ]);
    }

    public function test_admin_can_update_product_pricing(): void
    {
        $product = Product::create([
            'sku' => 'EXIST-PRD',
            'name' => 'Existing Product',
            'unit_id' => $this->unit->id,
            'harga_modal' => 5000,
            'harga_jual' => 7000,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.products.update', $product->id), [
                'sku' => 'EXIST-PRD',
                'name' => 'Existing Product Updated',
                'unit_id' => $this->unit->id,
                'harga_modal' => 6000,
                'harga_jual' => 9000,
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'harga_modal' => 6000,
            'harga_jual' => 9000,
        ]);
    }

    public function test_product_pricing_validation_requires_cost_and_sale_price(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'sku' => 'INVALID-PRD',
                'name' => 'Invalid Product',
                'unit_id' => $this->unit->id,
                'weight_kg' => 2.0,
            ]);

        $response->assertSessionHasErrors(['harga_modal', 'harga_jual']);
    }
}
