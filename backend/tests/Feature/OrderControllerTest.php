<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_an_order_for_a_tenant()
    {
        $tenant = Tenant::create(['id' => 'test-tenant', 'name' => 'Test Tenant', 'domain' => 'test-tenant.localhost']);
        $product = Product::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test Product',
            'price' => 100,
        ]);

        $payload = [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ];

        $response = $this->withHeaders(['Host' => $tenant->domain])
                         ->postJson('/api/v1/orders', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['total_amount' => 200])
                 ->assertJsonCount(1, 'items');

        $this->assertDatabaseHas('orders', ['tenant_id' => $tenant->id, 'total_amount' => 200]);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 2]);
    }
}
