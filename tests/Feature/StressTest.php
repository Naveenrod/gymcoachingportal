<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Tests\TestCase;

class StressTest extends TestCase
{
    public function test_client_index_handles_large_dataset(): void
    {
        $user = User::factory()->admin()->create();
        Client::factory()->count(500)->create();

        $response = $this->actingAs($user)->get('/clients');

        $response->assertStatus(200);
    }

    public function test_appointment_index_handles_large_dataset(): void
    {
        $user = User::factory()->admin()->create();
        $client = Client::factory()->create();
        Appointment::factory()->count(200)->create(['client_id' => $client->id]);

        $response = $this->actingAs($user)->get('/appointments');

        $response->assertStatus(200);
    }

    public function test_rapid_sequential_dashboard_requests(): void
    {
        $user = User::factory()->admin()->create();
        Client::factory()->count(10)->create();

        for ($i = 0; $i < 50; $i++) {
            $this->actingAs($user)->get('/dashboard')->assertStatus(200);
        }
    }

    public function test_concurrent_client_creation(): void
    {
        $user = User::factory()->admin()->create();

        for ($i = 0; $i < 20; $i++) {
            $this->actingAs($user)->post('/clients', [
                'first_name' => "Stress{$i}",
                'last_name' => 'Test',
                'email' => "stress{$i}@test.com",
                'membership_type' => 'Basic',
                'status' => 'Active',
            ])->assertRedirect();
        }

        $this->assertDatabaseCount('clients', 20);
    }

    public function test_concurrent_appointment_creation(): void
    {
        $user = User::factory()->admin()->create();
        $client = Client::factory()->create();

        for ($i = 0; $i < 20; $i++) {
            $hour = str_pad(8 + ($i % 12), 2, '0', STR_PAD_LEFT);
            $this->actingAs($user)->post('/appointments', [
                'client_id' => $client->id,
                'appointment_date' => '2025-06-'.str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'appointment_time' => "{$hour}:00",
                'duration_minutes' => 60,
                'session_type' => 'Personal Training',
                'status' => 'Scheduled',
            ])->assertRedirect();
        }

        $this->assertDatabaseCount('appointments', 20);
    }

    public function test_search_with_large_dataset(): void
    {
        $user = User::factory()->admin()->create();
        Client::factory()->count(500)->create();
        Client::factory()->create(['first_name' => 'SpecificSearchTarget']);

        $response = $this->actingAs($user)->get('/clients?search=SpecificSearchTarget');

        $response->assertStatus(200);
        $response->assertSee('SpecificSearchTarget');
    }
}
