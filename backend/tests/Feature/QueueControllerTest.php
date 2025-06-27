<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class QueueControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a tenant
        $this->tenant = Tenant::create(['name' => 'Test Restaurant', 'subdomain' => 'test']);

        // Create an admin user for the tenant
        $this->adminUser = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'admin',
        ]);

        // Use the tenant's subdomain for all tests
        // This is a simplified approach, a real application might use middleware.
        $this->actingAs($this->adminUser);

        // Seed some queue data for testing
        DB::table('queues')->insert([
            ['queue_number' => 'A01', 'status' => 'waiting', 'created_at' => now()->subMinutes(5)],
            ['queue_number' => 'B02', 'status' => 'waiting', 'created_at' => now()->subMinutes(3)],
            ['queue_number' => 'C03', 'status' => 'waiting', 'created_at' => now()->subMinutes(1)],
            ['queue_number' => 'Z99', 'status' => 'serving', 'created_at' => now()->subMinutes(10)],
        ]);
    }

    /** @test */
    public function a_public_endpoint_can_get_the_queue_status()
    {
        $response = $this->getJson('/api/queue/public');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'currently_serving',
            'waiting_list' => [
                '*' => ['id', 'queue_number', 'status']
            ]
        ]);
        $response->assertJsonPath('currently_serving', 'Z99');
        $this->assertCount(3, $response->json('waiting_list'));
        $this->assertEquals('A01', $response->json('waiting_list.0.queue_number')); // Check order
    }

    /** @test */
    public function an_admin_can_advance_the_queue()
    {
        // First, check the initial state
        $initialQueue = DB::table('queues')->where('status', 'waiting')->orderBy('created_at')->first();
        $this->assertEquals('A01', $initialQueue->queue_number);

        // Call the advance queue endpoint
        $response = $this->actingAs($this->adminUser)->postJson('/api/queue/advance');
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Queue advanced.']);

        // Check that 'A01' is now serving and 'Z99' is completed
        $this->assertEquals('serving', DB::table('queues')->where('queue_number', 'A01')->value('status'));
        $this->assertEquals('completed', DB::table('queues')->where('queue_number', 'Z99')->value('status'));
    }

    /** @test */
    public function a_non_admin_cannot_advance_the_queue()
    {
        // Create a non-admin user
        $managerUser = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'manager',
        ]);

        $response = $this->actingAs($managerUser)->postJson('/api/queue/advance');
        $response->assertStatus(403);
    }
}
