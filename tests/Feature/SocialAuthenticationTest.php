<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class SocialAuthenticationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_oauth_configuration_exists_in_services_config()
    {
        // Verify OAuth providers are configured in services.php
        $services = config('services');

        $this->assertArrayHasKey('google', $services);
        $this->assertArrayHasKey('facebook', $services);
        $this->assertArrayHasKey('twitter', $services);
        $this->assertArrayHasKey('github', $services);
        $this->assertArrayHasKey('linkedin', $services);
        $this->assertArrayHasKey('microweber', $services);

        // Verify Google OAuth structure
        $this->assertArrayHasKey('client_id', $services['google']);
        $this->assertArrayHasKey('client_secret', $services['google']);
        $this->assertArrayHasKey('redirect', $services['google']);
        $this->assertEquals('/oauth/callback/google', $services['google']['redirect']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_oauth_routes_are_registered()
    {
        // Test that OAuth routes exist
        $routes = [
            '/oauth/callback/google',
            '/profile/oauth/google',
            '/admin/oauth/google',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            // Should not return 404 (route exists)
            $this->assertNotEquals(404, $response->getStatusCode());
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_social_login_settings_page_exists()
    {
        // Verify the settings page for social login exists
        $this->assertFileExists(
            base_path('Modules/Settings/Filament/Pages/AdminLoginRegisterPage.php')
        );

        // Verify social login view exists
        $this->assertFileExists(
            base_path('Modules/Profile/resources/views/auth/social-login.blade.php')
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_user_manager_has_social_login_methods()
    {
        // Verify UserManager has social login methods
        $userManager = app()->user_manager;

        $this->assertTrue(method_exists($userManager, 'social_login'));
        $this->assertTrue(method_exists($userManager, 'social_login_process'));
        $this->assertTrue(method_exists($userManager, 'socialite_config'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_user_model_has_oauth_fields()
    {
        // Create a test user
        $user = User::create([
            'email' => 'oauth_test@example.com',
            'username' => 'oauth_test_user',
            'password' => bcrypt('password'),
            'is_active' => 1,
            'oauth_provider' => 'google',
            'oauth_uid' => '123456789',
        ]);

        // Verify OAuth fields exist
        $this->assertEquals('google', $user->oauth_provider);
        $this->assertEquals('123456789', $user->oauth_uid);

        // Cleanup
        $user->delete();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_finds_socialite_plugin_in_panel_providers()
    {
        // Verify the plugin is used in Profile panel
        $panelProviderPath = base_path('Modules/Profile/Providers/FilamentProfilePanelProvider.php');
        $content = file_get_contents($panelProviderPath);

        $this->assertStringContainsString('MicroweberFilamentSocialitePlugin', $content);
        $this->assertStringContainsString('FilamentSocialitePlugin', $content);
    }
}
