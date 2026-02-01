<?php

namespace Tests\Unit\Models;

use App\Models\Client;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_can_create_user(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'full_name' => 'Test User',
        ]);

        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);
    }

    public function test_password_is_hashed(): void
    {
        $user = User::factory()->create(['password' => 'plaintext']);

        $this->assertNotEquals('plaintext', $user->password);
        $this->assertTrue(password_verify('plaintext', $user->password));
    }

    public function test_is_admin_returns_true_for_admin(): void
    {
        $user = User::factory()->admin()->create();

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isCoach());
        $this->assertFalse($user->isClient());
    }

    public function test_is_coach_returns_true_for_coach(): void
    {
        $user = User::factory()->coach()->create();

        $this->assertTrue($user->isCoach());
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isClient());
    }

    public function test_is_client_returns_true_for_client(): void
    {
        $user = User::factory()->client()->create();

        $this->assertTrue($user->isClient());
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isCoach());
    }

    public function test_user_has_client_relationship(): void
    {
        $user = User::factory()->client()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $this->assertNotNull($user->client);
        $this->assertEquals($client->id, $user->client->id);
    }

    public function test_password_is_hidden_in_array(): void
    {
        $user = User::factory()->create();
        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    public function test_default_role_is_coach(): void
    {
        $user = User::factory()->create();

        $this->assertEquals('coach', $user->role);
    }
}
