<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create two example tenants for demonstration with different currencies
        $tenants = [
            ['id' => 'tenanta', 'name' => 'Coffee House A', 'domain' => 'tenanta.localhost', 'currency' => 'TWD'],
            ['id' => 'tenantb', 'name' => 'Burger Joint B', 'domain' => 'tenantb.localhost', 'currency' => 'USD'],
        ];

        foreach ($tenants as $tenantData) {
            Tenant::firstOrCreate(['id' => $tenantData['id']], $tenantData);
            // Seed products for each tenant
            $products = [
                ['name' => 'Espresso', 'price' => 75.00],
                ['name' => 'Latte', 'price' => 110.00],
                ['name' => 'Cappuccino', 'price' => 100.00],
            ];
            foreach ($products as $productData) {
                Product::firstOrCreate(
                    ['tenant_id' => $tenantData['id'], 'name' => $productData['name']],
                    ['description' => 'Delicious ' . $productData['name'], 'price' => $productData['price']]
                );
            }
        }
    }
}
