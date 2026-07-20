<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'role'           => 'admin',
            'login_attempts' => 0,
            'locked_until'   => null,
        ], $attrs));
    }

    public function test_login_form_is_accessible(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk ke Panel Admin');
    }

    public function test_authenticated_user_redirected_from_login(): void
    {
        $user = $this->makeUser();
        $response = $this->actingAs($user)->get('/admin/login');
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_succeeds_with_valid_credentials(): void
    {
        $user = $this->makeUser(['email' => 'admin@test.com', 'password' => bcrypt('Secret123!')]);

        $response = $this->post('/admin/login', [
            'email'    => 'admin@test.com',
            'password' => 'Secret123!',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $this->makeUser(['email' => 'admin@test.com', 'password' => bcrypt('Secret123!')]);

        $response = $this->post('/admin/login', [
            'email'    => 'admin@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_account_locked_after_five_failed_attempts(): void
    {
        $user = $this->makeUser(['email' => 'admin@test.com', 'password' => bcrypt('Secret123!')]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email'    => 'admin@test.com',
                'password' => 'wrongpassword',
            ]);
        }

        $user->refresh();
        $this->assertNotNull($user->locked_until);
        $this->assertGreaterThan(now(), $user->locked_until);

        // 6th attempt should see lockout message
        $response = $this->post('/admin/login', [
            'email'    => 'admin@test.com',
            'password' => 'Secret123!',
        ]);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_logout_invalidates_session_and_redirects(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $response = $this->post('/admin/logout');
        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect(route('admin.login'));
    }

    public function test_dashboard_accessible_when_authenticated(): void
    {
        $user = $this->makeUser();
        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }
}
