<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_dashboard_loads_with_statistics(): void
    {
        $user = User::factory()->admin()->create();
        Client::factory()->count(5)->create(['status' => 'Active']);
        Client::factory()->count(2)->create(['status' => 'Inactive']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('totalClients', 7);
        $response->assertViewHas('activeClients', 5);
    }

    public function test_dashboard_shows_todays_appointments(): void
    {
        $user = User::factory()->admin()->create();
        $client = Client::factory()->create();
        Appointment::factory()->create([
            'client_id' => $client->id,
            'appointment_date' => Carbon::today(),
            'status' => 'Scheduled',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('todaysAppointments');
    }

    public function test_dashboard_shows_upcoming_appointments(): void
    {
        $user = User::factory()->admin()->create();
        $client = Client::factory()->create();
        Appointment::factory()->create([
            'client_id' => $client->id,
            'appointment_date' => Carbon::today()->addDays(2),
            'status' => 'Scheduled',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('upcoming');
    }

    public function test_dashboard_shows_recent_clients(): void
    {
        $user = User::factory()->admin()->create();
        Client::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('recentClients');
    }
}
