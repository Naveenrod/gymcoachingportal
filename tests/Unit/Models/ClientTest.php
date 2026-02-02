<?php

namespace Tests\Unit\Models;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Tests\TestCase;

class ClientTest extends TestCase
{
    public function test_can_create_client(): void
    {
        $client = Client::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);

        $this->assertDatabaseHas('clients', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);
    }

    public function test_full_name_accessor(): void
    {
        $client = Client::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Smith',
        ]);

        $this->assertEquals('John Smith', $client->full_name);
    }

    public function test_has_many_appointments(): void
    {
        $client = Client::factory()->create();
        Appointment::factory()->count(3)->create(['client_id' => $client->id]);

        $this->assertCount(3, $client->appointments);
    }

    public function test_belongs_to_user(): void
    {
        $user = User::factory()->client()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $this->assertNotNull($client->user);
        $this->assertEquals($user->id, $client->user->id);
    }

    public function test_soft_delete(): void
    {
        $client = Client::factory()->create();
        $clientId = $client->id;

        $client->delete();

        $this->assertSoftDeleted('clients', ['id' => $clientId]);
        $this->assertNull(Client::find($clientId));
        $this->assertNotNull(Client::withTrashed()->find($clientId));
    }

    public function test_date_casts(): void
    {
        $client = Client::factory()->create([
            'date_of_birth' => '1990-05-15',
            'membership_start_date' => '2024-01-01',
            'membership_end_date' => '2025-01-01',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $client->date_of_birth);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $client->membership_start_date);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $client->membership_end_date);
    }

    public function test_client_without_user(): void
    {
        $client = Client::factory()->create(['user_id' => null]);

        $this->assertNull($client->user);
    }
}
