<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $vendor;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->admin = User::factory()->create([
            'role_id' => 3,
            'is_approved' => true
        ]);

        // Create a pending vendor
        $this->vendor = User::factory()->create([
            'role_id' => 2,
            'is_approved' => false
        ]);

        // Create a regular user
        $this->regularUser = User::factory()->create([
            'role_id' => 1,
            'is_approved' => true
        ]);
    }

    public function test_non_admin_cannot_access_admin_routes()
    {
        // Test with regular user
        $response = $this->actingAs($this->regularUser)
            ->getJson('/api/admin/pending-vendors');
        $response->assertStatus(403);

        // Test with vendor
        $response = $this->actingAs($this->vendor)
            ->getJson('/api/admin/pending-vendors');
        $response->assertStatus(403);
    }

    public function test_admin_can_get_pending_vendors()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/admin/pending-vendors');

        $response->assertStatus(200)
            ->assertJsonStructure(['pending_vendors']);
    }

    public function test_admin_can_approve_vendor()
    {
        $response = $this->actingAs($this->admin)
            ->postJson("/api/admin/vendors/{$this->vendor->id}/approve");

        $response->assertStatus(200);
        $this->assertTrue($this->vendor->fresh()->is_approved);
    }

    public function test_admin_can_reject_vendor()
    {
        $response = $this->actingAs($this->admin)
            ->postJson("/api/admin/vendors/{$this->vendor->id}/reject");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $this->vendor->id]);
    }
} 