<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AdminAuthenticationTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'is_admin' => 1,
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create regular non-admin user
        $this->regularUser = User::factory()->create([
            'is_admin' => 0,
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    /**
     * Test that the admin login page exists and is accessible.
     */
    #[Test]
    public function it_admin_login_page_exists(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('Sign in');
    }

    /**
     * Test that an admin can login via the admin login form.
     */
    #[Test]
    public function it_admin_can_login(): void
    {
        $response = $this->post('/admin/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->admin);
    }

    /**
     * Test that a non-admin user cannot access the admin panel.
     */
    #[Test]
    public function it_non_admin_blocked_from_admin(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin');
        $response->assertStatus(403);
    }

    /**
     * Test that an authenticated admin can access the admin dashboard.
     */
    #[Test]
    public function it_admin_dashboard_renders(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    /**
     * Test that guests are redirected to login when accessing admin.
     */
    #[Test]
    public function it_guest_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test that login fails with invalid credentials.
     */
    #[Test]
    public function it_login_fails_with_invalid_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'email' => $this->admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /**
     * Test that login page has the expected form fields.
     */
    #[Test]
    public function it_login_page_has_form_fields(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('Email address');
        $response->assertSee('Password');
        $response->assertSee('Sign in');
    }
}
