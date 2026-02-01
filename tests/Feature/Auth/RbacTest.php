<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class RbacTest extends TestCase
{
    public function test_admin_can_access_user_management(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/users')->assertStatus(200);
        $this->actingAs($admin)->get('/users/create')->assertStatus(200);
    }

    public function test_coach_cannot_access_user_management(): void
    {
        $coach = User::factory()->coach()->create();

        $this->actingAs($coach)->get('/users')->assertStatus(403);
        $this->actingAs($coach)->get('/users/create')->assertStatus(403);
    }

    public function test_client_cannot_access_user_management(): void
    {
        $clientUser = User::factory()->client()->create();

        $this->actingAs($clientUser)->get('/users')->assertStatus(403);
    }

    public function test_admin_can_access_clients(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/clients')->assertStatus(200);
        $this->actingAs($admin)->get('/dashboard')->assertStatus(200);
    }

    public function test_coach_can_access_clients(): void
    {
        $coach = User::factory()->coach()->create();

        $this->actingAs($coach)->get('/clients')->assertStatus(200);
        $this->actingAs($coach)->get('/dashboard')->assertStatus(200);
    }

    public function test_client_cannot_access_admin_dashboard(): void
    {
        $clientUser = User::factory()->client()->create();

        $this->actingAs($clientUser)->get('/dashboard')->assertStatus(403);
        $this->actingAs($clientUser)->get('/clients')->assertStatus(403);
    }

    public function test_client_can_access_portal(): void
    {
        $clientUser = User::factory()->client()->create();

        $this->actingAs($clientUser)->get('/portal')->assertStatus(200);
        $this->actingAs($clientUser)->get('/portal/appointments')->assertStatus(200);
        $this->actingAs($clientUser)->get('/portal/profile')->assertStatus(200);
    }

    public function test_admin_cannot_access_client_portal(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/portal')->assertStatus(403);
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/users', [
            'username' => 'newuser',
            'email' => 'new@example.com',
            'full_name' => 'New User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'coach',
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', ['username' => 'newuser', 'role' => 'coach']);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->delete("/users/{$admin->id}");

        $response->assertRedirect('/users');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}
