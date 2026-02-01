<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Tests\TestCase;

class ClientCrudTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->admin()->create();
    }

    public function test_index_displays_clients(): void
    {
        Client::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get('/clients');

        $response->assertStatus(200);
        $response->assertViewHas('clients');
    }

    public function test_index_paginates_clients(): void
    {
        Client::factory()->count(30)->create();

        $response = $this->actingAs($this->user)->get('/clients');

        $response->assertStatus(200);
    }

    public function test_index_search_filter(): void
    {
        Client::factory()->create(['first_name' => 'UniqueSearchName']);
        Client::factory()->count(5)->create();

        $response = $this->actingAs($this->user)->get('/clients?search=UniqueSearchName');

        $response->assertStatus(200);
        $response->assertSee('UniqueSearchName');
    }

    public function test_index_status_filter(): void
    {
        Client::factory()->create(['status' => 'Active']);
        Client::factory()->create(['status' => 'Inactive']);

        $response = $this->actingAs($this->user)->get('/clients?status=Active');

        $response->assertStatus(200);
    }

    public function test_create_form_loads(): void
    {
        $response = $this->actingAs($this->user)->get('/clients/create');

        $response->assertStatus(200);
    }

    public function test_store_creates_client(): void
    {
        $response = $this->actingAs($this->user)->post('/clients', [
            'first_name' => 'Test',
            'last_name' => 'Client',
            'email' => 'test@client.com',
            'membership_type' => 'Basic',
            'status' => 'Active',
        ]);

        $response->assertRedirect('/clients');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('clients', ['email' => 'test@client.com']);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post('/clients', []);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'membership_type', 'status']);
    }

    public function test_store_validates_unique_email(): void
    {
        Client::factory()->create(['email' => 'taken@example.com']);

        $response = $this->actingAs($this->user)->post('/clients', [
            'first_name' => 'Test',
            'last_name' => 'Client',
            'email' => 'taken@example.com',
            'membership_type' => 'Basic',
            'status' => 'Active',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_store_validates_address_max_length(): void
    {
        $response = $this->actingAs($this->user)->post('/clients', [
            'first_name' => 'Test',
            'last_name' => 'Client',
            'email' => 'test@example.com',
            'membership_type' => 'Basic',
            'status' => 'Active',
            'address' => str_repeat('a', 501),
        ]);

        $response->assertSessionHasErrors('address');
    }

    public function test_show_displays_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)->get("/clients/{$client->id}");

        $response->assertStatus(200);
        $response->assertSee($client->first_name);
    }

    public function test_edit_form_loads(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)->get("/clients/{$client->id}/edit");

        $response->assertStatus(200);
    }

    public function test_update_modifies_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)->put("/clients/{$client->id}", [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'email' => $client->email,
            'membership_type' => 'VIP',
            'status' => 'Active',
        ]);

        $response->assertRedirect('/clients');
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'first_name' => 'Updated', 'membership_type' => 'VIP']);
    }

    public function test_update_allows_same_email(): void
    {
        $client = Client::factory()->create(['email' => 'test@example.com']);

        $response = $this->actingAs($this->user)->put("/clients/{$client->id}", [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'email' => 'test@example.com',
            'membership_type' => 'Basic',
            'status' => 'Active',
        ]);

        $response->assertRedirect('/clients');
        $response->assertSessionDoesntHaveErrors('email');
    }

    public function test_destroy_soft_deletes_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)->delete("/clients/{$client->id}");

        $response->assertRedirect('/clients');
        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    }
}
