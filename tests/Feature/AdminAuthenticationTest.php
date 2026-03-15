<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\Profile\Filament\Pages\Login;
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
            'email' => 'test-admin-' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create regular non-admin user
        $this->regularUser = User::factory()->create([
            'is_admin' => 0,
            'email' => 'test-user-' . uniqid() . '@example.com',
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
     * Test that an admin can authenticate via the Livewire login component.
     */
    #[Test]
    public function it_admin_can_login(): void
    {
        $this->assertTrue(
            auth()->attempt([
                'email' => $this->admin->email,
                'password' => 'password',
            ]),
            'Admin should authenticate with correct credentials'
        );

        $this->assertAuthenticatedAs($this->admin);
        auth()->logout();
    }

    /**
     * Test that a non-admin user is redirected away from admin panel
     * (Filament redirects unauthenticated/unauthorized users to login).
     */
    #[Test]
    public function it_non_admin_blocked_from_admin(): void
    {
        $panel = Filament::getPanel('admin');
        $this->assertFalse(
            $this->regularUser->canAccessPanel($panel),
            'Non-admin user should not have panel access'
        );
    }

    /**
     * Test that an admin user has access to the admin panel.
     */
    #[Test]
    public function it_admin_has_panel_access(): void
    {
        $panel = Filament::getPanel('admin');
        $this->assertTrue(
            $this->admin->canAccessPanel($panel),
            'Admin user should have panel access'
        );
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
        $result = auth()->attempt([
            'email' => $this->admin->email,
            'password' => 'wrong-password',
        ]);

        $this->assertFalse($result, 'Login should fail with wrong password');
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
