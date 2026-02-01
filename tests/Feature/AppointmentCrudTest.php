<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Tests\TestCase;

class AppointmentCrudTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->admin()->create();
    }

    public function test_index_displays_appointments(): void
    {
        Appointment::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get('/appointments');

        $response->assertStatus(200);
    }

    public function test_create_form_loads(): void
    {
        $response = $this->actingAs($this->user)->get('/appointments/create');

        $response->assertStatus(200);
    }

    public function test_create_with_preselected_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)->get("/appointments/create?client_id={$client->id}");

        $response->assertStatus(200);
    }

    public function test_store_creates_appointment(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)->post('/appointments', [
            'client_id' => $client->id,
            'appointment_date' => '2025-06-15',
            'appointment_time' => '10:00',
            'duration_minutes' => 60,
            'session_type' => 'Personal Training',
            'status' => 'Scheduled',
        ]);

        $response->assertRedirect('/appointments');
        $this->assertDatabaseHas('appointments', [
            'client_id' => $client->id,
            'session_type' => 'Personal Training',
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post('/appointments', []);

        $response->assertSessionHasErrors(['client_id', 'appointment_date', 'appointment_time', 'duration_minutes', 'session_type', 'status']);
    }

    public function test_store_validates_client_exists(): void
    {
        $response = $this->actingAs($this->user)->post('/appointments', [
            'client_id' => 9999,
            'appointment_date' => '2025-06-15',
            'appointment_time' => '10:00',
            'duration_minutes' => 60,
            'session_type' => 'Personal Training',
            'status' => 'Scheduled',
        ]);

        $response->assertSessionHasErrors('client_id');
    }

    public function test_store_validates_duration_range(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)->post('/appointments', [
            'client_id' => $client->id,
            'appointment_date' => '2025-06-15',
            'appointment_time' => '10:00',
            'duration_minutes' => 5,
            'session_type' => 'Personal Training',
            'status' => 'Scheduled',
        ]);

        $response->assertSessionHasErrors('duration_minutes');
    }

    public function test_show_displays_appointment(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->actingAs($this->user)->get("/appointments/{$appointment->id}");

        $response->assertStatus(200);
    }

    public function test_edit_form_loads(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->actingAs($this->user)->get("/appointments/{$appointment->id}/edit");

        $response->assertStatus(200);
    }

    public function test_update_modifies_appointment(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->actingAs($this->user)->put("/appointments/{$appointment->id}", [
            'client_id' => $appointment->client_id,
            'appointment_date' => '2025-07-01',
            'appointment_time' => '14:00',
            'duration_minutes' => 90,
            'session_type' => 'Consultation',
            'status' => 'Completed',
        ]);

        $response->assertRedirect('/appointments');
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'session_type' => 'Consultation',
            'status' => 'Completed',
        ]);
    }

    public function test_destroy_soft_deletes_appointment(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->actingAs($this->user)->delete("/appointments/{$appointment->id}");

        $response->assertRedirect('/appointments');
        $this->assertSoftDeleted('appointments', ['id' => $appointment->id]);
    }
}
