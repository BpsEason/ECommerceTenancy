<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test if a tenant can list their own products.
     */
    public function test_can_list_products_for_a_specific_tenant()
    {
        // Create a test tenant
        $tenant = Tenant::create(['id' => 'test-tenant', 'name' => 'Test Tenant', 'domain' => 'test-tenant.localhost']);
        // Create a product for the test tenant
        Product::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test Product A',
            'price' => 99.99,
        ]);
        // Create a product for another tenant (this should not be visible)
        Product::create([
            'tenant_id' => 'another-tenant',
            'name' => 'Test Product B',
            'price' => 50.00,
        ]);
        // Make a request to the API with the tenant's domain
        $response = $this->withHeaders(['Host' => $tenant->domain])
                         ->getJson('/api/v1/products');
        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonFragment(['name' => 'Test Product A']);
    }

    /**
     * Test that a product cannot be listed without a tenant context.
     */
    public function test_cannot_list_products_without_tenant_context()
    {
        $response = $this->getJson('/api/v1/products');
        $response->assertStatus(404)
                 ->assertJson(['error' => 'Tenant not found. Access via subdomain.']);
    }
}
