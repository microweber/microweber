<?php

declare(strict_types=1);

namespace MicroweberPackages\SocialLogin\Tests;

use Illuminate\Support\Facades\Config;
use MicroweberPackages\SocialLogin\Contracts\SocialLoginServiceContract;
use MicroweberPackages\SocialLogin\Services\SocialLoginService;
use PHPUnit\Framework\Attributes\Test;

class SocialLoginServiceTest extends TestCase
{
    #[Test]
    public function service_is_bound_in_container(): void
    {
        $this->assertInstanceOf(
            SocialLoginServiceContract::class,
            $this->app->make(SocialLoginServiceContract::class)
        );
    }

    #[Test]
    public function service_is_aliased_as_social_login(): void
    {
        $this->assertInstanceOf(
            SocialLoginServiceContract::class,
            $this->app->make('social_login')
        );
    }

    #[Test]
    public function service_is_singleton(): void
    {
        $a = $this->app->make('social_login');
        $b = $this->app->make('social_login');

        $this->assertSame($a, $b);
    }

    #[Test]
    public function enabled_providers_returns_configured_providers(): void
    {
        /** @var SocialLoginService $service */
        $service = $this->app->make('social_login');

        $enabled = $service->enabledProviders();

        $this->assertContains('google', $enabled);
        // Facebook is disabled by default
        $this->assertNotContains('facebook', $enabled);
    }

    #[Test]
    public function is_provider_enabled_returns_true_for_configured(): void
    {
        /** @var SocialLoginService $service */
        $service = $this->app->make('social_login');

        $this->assertTrue($service->isProviderEnabled('google'));
    }

    #[Test]
    public function is_provider_enabled_returns_false_for_unconfigured(): void
    {
        /** @var SocialLoginService $service */
        $service = $this->app->make('social_login');

        $this->assertFalse($service->isProviderEnabled('facebook'));
    }

    #[Test]
    public function is_provider_enabled_returns_false_for_unknown(): void
    {
        /** @var SocialLoginService $service */
        $service = $this->app->make('social_login');

        $this->assertFalse($service->isProviderEnabled('nonexistent'));
    }

    #[Test]
    public function callback_url_uses_default_builder(): void
    {
        // Ensure no custom builder is set
        Config::set('social-login.callback_url_builder', null);
        $service = new SocialLoginService($this->app);

        $url = $service->callbackUrl('google');

        $this->assertStringContainsString('social_login_process', $url);
        $this->assertStringContainsString('provider=google', $url);
    }

    #[Test]
    public function callback_url_uses_custom_builder(): void
    {
        Config::set('social-login.callback_url_builder', function (string $provider): string {
            return 'https://example.com/oauth/callback/' . $provider;
        });

        $service = new SocialLoginService($this->app);

        $url = $service->callbackUrl('google');

        $this->assertEquals('https://example.com/oauth/callback/google', $url);
    }

    #[Test]
    public function config_is_merged_properly(): void
    {
        $config = Config::get('social-login');

        $this->assertIsArray($config);
        $this->assertArrayHasKey('providers', $config);
        $this->assertArrayHasKey('facebook', $config['providers']);
        $this->assertArrayHasKey('google', $config['providers']);
        $this->assertArrayHasKey('github', $config['providers']);
        $this->assertArrayHasKey('twitter', $config['providers']);
        $this->assertArrayHasKey('linkedin', $config['providers']);
        $this->assertArrayHasKey('microweber', $config['providers']);
    }

    #[Test]
    public function enabled_providers_empty_when_none_configured(): void
    {
        Config::set('social-login.providers', [
            'facebook' => ['enabled' => false, 'client_id' => '', 'client_secret' => ''],
        ]);

        $service = new SocialLoginService($this->app);

        $this->assertEmpty($service->enabledProviders());
    }

    #[Test]
    public function enabled_providers_requires_both_credentials(): void
    {
        Config::set('social-login.providers.twitter', [
            'enabled'       => true,
            'client_id'     => 'only-id',
            'client_secret' => '',
        ]);

        $service = new SocialLoginService($this->app);

        $this->assertFalse($service->isProviderEnabled('twitter'));
    }

    #[Test]
    public function refresh_config_picks_up_changes(): void
    {
        /** @var SocialLoginService $service */
        $service = $this->app->make('social_login');

        $this->assertFalse($service->isProviderEnabled('github'));

        Config::set('social-login.providers.github', [
            'enabled'       => true,
            'client_id'     => 'gh-id',
            'client_secret' => 'gh-secret',
        ]);

        $service->refreshConfig();

        $this->assertTrue($service->isProviderEnabled('github'));
    }

    #[Test]
    public function redirect_sets_service_config_and_returns_redirect(): void
    {
        // Ensure the request has a session (Socialite needs it for state)
        $request = $this->app->make('request');
        if (!$request->hasSession()) {
            $session = new \Illuminate\Session\Store(
                'test',
                new \Illuminate\Session\ArraySessionHandler(60)
            );
            $request->setLaravelSession($session);
        }

        /** @var SocialLoginService $service */
        $service = $this->app->make('social_login');

        $response = $service->redirect('google');

        // The redirect response should be a Symfony redirect pointing to
        // Google's OAuth authorization endpoint.
        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\RedirectResponse::class, $response);
        $this->assertStringContainsString('accounts.google.com', $response->getTargetUrl());

        // Verify the service config was applied
        $this->assertEquals('test-google-id', Config::get('services.google.client_id'));
        $this->assertEquals('test-google-secret', Config::get('services.google.client_secret'));
    }
}