<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Product;
use App\Models\Table;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Create Tenant A
            $tenantA = Tenant::create([
                'name' => 'Restaurant A',
                'subdomain' => 'tenanta'
            ]);

            // Create Admin and Manager for Tenant A
            User::create([
                'tenant_id' => $tenantA->id,
                'name' => 'Admin A',
                'email' => 'admin@tenanta.com',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]);
            User::create([
                'tenant_id' => $tenantA->id,
                'name' => 'Manager A',
                'email' => 'manager@tenanta.com',
                'password' => Hash::make('password'),
                'role' => 'manager'
            ]);

            // Create some products for Tenant A
            $tenantA->products()->saveMany([
                new Product(['name' => 'Burger', 'description' => 'Classic beef burger', 'price' => 12.99, 'stock' => 100]),
                new Product(['name' => 'Pizza', 'description' => 'Pepperoni pizza', 'price' => 15.50, 'stock' => 50]),
            ]);

            // Create some tables for Tenant A
            $tenantA->tables()->saveMany([
                new Table(['number' => 'T01', 'capacity' => 4, 'status' => 'available']),
                new Table(['number' => 'T02', 'capacity' => 2, 'status' => 'occupied']),
                new Table(['number' => 'T03', 'capacity' => 6, 'status' => 'available']),
            ]);

            // Create Tenant B
            $tenantB = Tenant::create([
                'name' => 'Cafe B',
                'subdomain' => 'tenantb'
            ]);

            // Create Admin for Tenant B
            User::create([
                'tenant_id' => $tenantB->id,
                'name' => 'Admin B',
                'email' => 'admin@tenantb.com',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]);

            // Create some products for Tenant B
            $tenantB->products()->saveMany([
                new Product(['name' => 'Coffee', 'description' => 'Freshly brewed coffee', 'price' => 4.50, 'stock' => 999]),
                new Product(['name' => 'Croissant', 'description' => 'Butter croissant', 'price' => 3.00, 'stock' => 20]),
            ]);
        });
    }
}
