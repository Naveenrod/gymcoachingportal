<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    public function test_successful_login_redirects_to_dashboard(): void
    {
        $user = User::factory()->admin()->create(['password' => 'password123']);

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_client_login_redirects_to_portal(): void
    {
        $user = User::factory()->client()->create(['password' => 'password123']);

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/portal');
        $this->assertAuthenticatedAs($user);
    }

    public function test_failed_login_shows_error(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_login_requires_username_and_password(): void
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['username', 'password']);
    }

    public function test_logout_clears_session(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/clients')->assertRedirect('/login');
        $this->get('/checkin')->assertRedirect('/login');
        $this->get('/appointments')->assertRedirect('/login');
    }

    public function test_authenticated_user_redirected_from_login_page(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/dashboard');
    }

    public function test_rate_limiting_blocks_after_five_attempts(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'username' => $user->username,
                'password' => 'wrong',
            ]);
        }

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'wrong',
        ]);

        $response->assertStatus(429);
    }
}
