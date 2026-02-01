<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Tests\TestCase;

class ClientPortalTest extends TestCase
{
    public function test_client_can_view_portal_dashboard(): void
    {
        $user = User::factory()->client()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/portal');

        $response->assertStatus(200);
        $response->assertSee($client->full_name);
    }

    public function test_client_without_profile_sees_no_profile_page(): void
    {
        $user = User::factory()->client()->create();

        $response = $this->actingAs($user)->get('/portal');

        $response->assertStatus(200);
        $response->assertSee('Profile Not Found');
    }

    public function test_client_can_view_own_appointments(): void
    {
        $user = User::factory()->client()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);
        Appointment::factory()->count(3)->create(['client_id' => $client->id]);

        $response = $this->actingAs($user)->get('/portal/appointments');

        $response->assertStatus(200);
    }

    public function test_client_can_view_own_profile(): void
    {
        $user = User::factory()->client()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/portal/profile');

        $response->assertStatus(200);
        $response->assertSee($client->email);
    }

    public function test_client_cannot_access_admin_routes(): void
    {
        $user = User::factory()->client()->create();

        $this->actingAs($user)->get('/dashboard')->assertStatus(403);
        $this->actingAs($user)->get('/clients')->assertStatus(403);
        $this->actingAs($user)->get('/appointments')->assertStatus(403);
        $this->actingAs($user)->get('/users')->assertStatus(403);
    }
}
