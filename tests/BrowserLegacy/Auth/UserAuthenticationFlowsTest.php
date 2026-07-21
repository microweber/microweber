<?php

namespace Tests\Browser\Auth;

use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

/**
 * Critical User Authentication Flows
 *
 * These tests cover critical authentication paths:
 * 1. User registration
 * 2. User login/logout
 * 3. Password reset
 * 4. Profile editing
 */
class UserAuthenticationFlowsTest extends DuskTestCase
{
    /**
     * Test complete user registration flow.
     */
    #[Test]
    public function it_user_can_register_and_login(): void
    {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Disable captcha for testing
            save_option([
                'option_key' => 'login_captcha_enabled',
                'option_group' => 'users',
                'option_value' => 0
            ]);

            // Navigate to registration page
            $browser->visit($siteUrl . 'register');
            $browser->pause(2000);
            $browser->waitForText('Register', 30);

            // Fill registration form
            $browser->type('username', 'testuser' . $uniqueId);
            $browser->type('email', 'test' . $uniqueId . '@example.com');
            $browser->type('password', 'TestPass123!');
            $browser->type('password_confirmation', 'TestPass123!');

            // Submit form
            $browser->click('button[type="submit"]');
            $browser->pause(3000);

            // Verify successful registration
            $browser->assertPathIs('/profile');
            $browser->assertSee('Welcome');

            // Check for JavaScript errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            // Logout
            $browser->visit($siteUrl . 'logout');
            $browser->pause(2000);

            // Try to login with new credentials
            $browser->visit($siteUrl . 'login');
            $browser->waitForText('Login', 30);

            $browser->type('email', 'test' . $uniqueId . '@example.com');
            $browser->type('password', 'TestPass123!');
            $browser->click('button[type="submit"]');
            $browser->pause(3000);

            // Verify login successful
            $browser->assertPathIs('/profile');
            $browser->assertSee('Welcome');

            // Cleanup
            User::where('email', 'test' . $uniqueId . '@example.com')->delete();
        });
    }

    /**
     * Test user login with invalid credentials shows error.
     */
    #[Test]
    public function it_user_login_shows_error_for_invalid_credentials(): void
    {
        $this->browse(function (Browser $browser) {
            $siteUrl = $this->siteUrl;

            // Navigate to login page
            $browser->visit($siteUrl . 'login');
            $browser->pause(2000);
            $browser->waitForText('Login', 30);

            // Fill with invalid credentials
            $browser->type('email', 'nonexistent@example.com');
            $browser->type('password', 'wrongpassword');
            $browser->click('button[type="submit"]');
            $browser->pause(2000);

            // Verify error message
            $browser->assertSee('Invalid credentials');

            // Check for JavaScript errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test user can update their profile.
     */
    #[Test]
    public function it_user_can_update_profile(): void
    {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Create test user
            $user = User::factory()->create([
                'username' => 'profiletest' . $uniqueId,
                'email' => 'profile' . $uniqueId . '@example.com',
                'password' => Hash::make('TestPass123!'),
                'is_active' => 1
            ]);

            // Login as user
            $browser->visit($siteUrl . 'login');
            $browser->pause(2000);
            $browser->type('email', 'profile' . $uniqueId . '@example.com');
            $browser->type('password', 'TestPass123!');
            $browser->click('button[type="submit"]');
            $browser->pause(3000);

            // Navigate to profile edit
            $browser->visit($siteUrl . 'profile/edit');
            $browser->pause(2000);
            $browser->waitForText('Edit Profile', 30);

            // Update profile information
            $browser->type('first_name', 'John' . $uniqueId);
            $browser->type('last_name', 'Doe' . $uniqueId);
            $browser->type('phone', '+1234567890');

            // Save changes
            $browser->click('button[type="submit"]');
            $browser->pause(3000);

            // Verify success message
            $browser->assertSee('Profile updated');

            // Verify changes persisted
            $browser->visit($siteUrl . 'profile/edit');
            $browser->pause(2000);
            $browser->assertInputValue('first_name', 'John' . $uniqueId);
            $browser->assertInputValue('last_name', 'Doe' . $uniqueId);

            // Check for JavaScript errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            // Cleanup
            $user->delete();
        });
    }

    /**
     * Test logout functionality.
     */
    #[Test]
    public function it_user_can_logout(): void
    {
        $this->browse(function (Browser $browser) {
            $siteUrl = $this->siteUrl;

            // Login as admin first
            $browser->within(new AdminLogin(), function ($browser) {
                $browser->fillForm();
            });

            // Verify logged in
            $browser->visit($siteUrl . 'admin');
            $browser->pause(2000);
            $browser->assertSee('Dashboard');

            // Logout
            $browser->visit($siteUrl . 'logout');
            $browser->pause(2000);

            // Verify logged out by trying to access admin
            $browser->visit($siteUrl . 'admin');
            $browser->pause(2000);

            // Should redirect to login page
            $browser->assertPathIs('/admin/login');

            // Check for JavaScript errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test password reset flow.
     */
    #[Test]
    public function it_user_can_reset_password(): void
    {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Disable captcha
            save_option([
                'option_key' => 'captcha_disabled',
                'option_group' => 'users',
                'option_value' => 1
            ]);

            // Create test user
            $user = User::factory()->create([
                'username' => 'resettest' . $uniqueId,
                'email' => 'reset' . $uniqueId . '@example.com',
                'password' => Hash::make('OldPass123!'),
                'is_active' => 1
            ]);

            // Navigate to forgot password
            $browser->visit($siteUrl . 'forgot-password');
            $browser->pause(2000);
            $browser->waitForText('Forgot Password', 30);

            // Submit email for reset
            $browser->type('email', 'reset' . $uniqueId . '@example.com');
            $browser->click('button[type="submit"]');
            $browser->pause(3000);

            // Verify success message
            $browser->assertSee('password reset link');

            // Check for JavaScript errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            // Cleanup
            $user->delete();
        });
    }
}
