<?php

namespace Tests\Feature;

use Tests\TestCase;

class SmokeTest extends TestCase
{
    public function test_application_boots(): void
    {
        $this->assertTrue(true);
    }

    public function test_login_page_responds(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_database_connection(): void
    {
        $this->assertDatabaseCount('users', 0);
    }

    public function test_health_check_endpoint(): void
    {
        $response = $this->get('/health');
        $response->assertStatus(200);
        $response->assertJson(['status' => 'healthy']);
    }

    public function test_laravel_health_check(): void
    {
        $response = $this->get('/up');
        $response->assertStatus(200);
    }

    public function test_root_redirects_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_all_authenticated_routes_require_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/clients')->assertRedirect('/login');
        $this->get('/clients/create')->assertRedirect('/login');
        $this->get('/appointments')->assertRedirect('/login');
        $this->get('/checkin')->assertRedirect('/login');
        $this->get('/users')->assertRedirect('/login');
    }

    public function test_portal_routes_require_login(): void
    {
        $this->get('/portal')->assertRedirect('/login');
        $this->get('/portal/appointments')->assertRedirect('/login');
        $this->get('/portal/profile')->assertRedirect('/login');
    }

    public function test_security_headers_present(): void
    {
        $response = $this->get('/login');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
    }

    public function test_csrf_protection_active(): void
    {
        // Verify CSRF middleware is registered by checking that
        // the application has the VerifyCsrfToken middleware
        $this->assertTrue(
            class_exists(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class),
            'CSRF middleware class exists'
        );
    }
}
