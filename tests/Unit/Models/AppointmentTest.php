<?php

namespace Tests\Unit\Models;

use App\Models\Appointment;
use App\Models\Client;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    public function test_can_create_appointment(): void
    {
        $client = Client::factory()->create();
        $appointment = Appointment::factory()->create([
            'client_id' => $client->id,
            'session_type' => 'Personal Training',
        ]);

        $this->assertDatabaseHas('appointments', [
            'client_id' => $client->id,
            'session_type' => 'Personal Training',
        ]);
    }

    public function test_belongs_to_client(): void
    {
        $client = Client::factory()->create();
        $appointment = Appointment::factory()->create(['client_id' => $client->id]);

        $this->assertNotNull($appointment->client);
        $this->assertEquals($client->id, $appointment->client->id);
    }

    public function test_soft_delete(): void
    {
        $appointment = Appointment::factory()->create();
        $appointmentId = $appointment->id;

        $appointment->delete();

        $this->assertSoftDeleted('appointments', ['id' => $appointmentId]);
        $this->assertNull(Appointment::find($appointmentId));
        $this->assertNotNull(Appointment::withTrashed()->find($appointmentId));
    }

    public function test_date_cast(): void
    {
        $appointment = Appointment::factory()->create([
            'appointment_date' => '2025-06-15',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $appointment->appointment_date);
    }

    public function test_default_duration(): void
    {
        $client = Client::factory()->create();
        $appointment = Appointment::create([
            'client_id' => $client->id,
            'appointment_date' => '2025-06-15',
            'appointment_time' => '10:00',
            'session_type' => 'Consultation',
            'status' => 'Scheduled',
        ]);

        $appointment->refresh();
        $this->assertEquals(60, $appointment->duration_minutes);
    }
}
