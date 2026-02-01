<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Tests\TestCase;

class CheckInTest extends TestCase
{
    public function test_checkin_index_loads(): void
    {
        $user = User::factory()->admin()->create();
        Client::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/checkin');

        $response->assertStatus(200);
    }

    public function test_update_saves_checkin_data(): void
    {
        $user = User::factory()->admin()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)->patch("/checkin/{$client->id}", [
            'package' => 'Premium Plan',
            'check_in_frequency' => 'Weekly',
            'check_in_day' => 'Monday',
            'submitted' => 'Submitted',
            'rank' => 'Gold',
        ]);

        $response->assertRedirect('/checkin');
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'package' => 'Premium Plan',
            'check_in_frequency' => 'Weekly',
        ]);
    }

    public function test_update_validates_loom_link_url(): void
    {
        $user = User::factory()->admin()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)->patch("/checkin/{$client->id}", [
            'loom_link' => 'not-a-valid-url',
        ]);

        $response->assertSessionHasErrors('loom_link');
    }

    public function test_checkin_is_paginated(): void
    {
        $user = User::factory()->admin()->create();
        Client::factory()->count(30)->create();

        $response = $this->actingAs($user)->get('/checkin');

        $response->assertStatus(200);
    }
}
