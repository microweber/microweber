<?php

declare(strict_types=1);

namespace MicroweberPackages\SocialLogin\Services;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\SocialiteManager;
use MicroweberPackages\SocialLogin\Contracts\SocialLoginServiceContract;
use MicroweberPackages\SocialLogin\Contracts\SocialUserResult;
use MicroweberPackages\SocialLogin\Providers\MicroweberSocialiteProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Concrete social login service.
 *
 * Works entirely from the `social-login` config array — no hard-coded
 * option look-ups. The consuming application (e.g. Microweber CMS) is
 * responsible for populating the config before this service is called.
 */
class SocialLoginService implements SocialLoginServiceContract
{
    /** @var array<string, mixed> */
    private array $config;

    private SocialiteManager $socialite;

    public function __construct(Application $app)
    {
        /** @var array<string, mixed> $cfg */
        $cfg = $app->make('config')->get('social-login', []);
        $this->config = $cfg;
        $this->socialite = new SocialiteManager($app);
    }

    /* ------------------------------------------------------------------
     | Public API
     | -----------------------------------------------------------------*/

    public function redirect(string $provider): RedirectResponse
    {
        $this->applyServiceConfig($provider);

        $driver = $this->resolveDriver($provider);

        // Provider-specific scopes / prompts
        /** @var RedirectResponse $response */
        $response = match ($provider) {
            'github' => $driver->scopes(['user:email'])->redirect(),
            'google', 'azure' => $driver->with(['prompt' => 'select_account'])->redirect(),
            default => $driver->redirect(),
        };

        return $response;
    }

    public function handleCallback(string $provider): SocialUserResult
    {
        $this->applyServiceConfig($provider);

        $user = $this->resolveDriver($provider)->stateless()->user();

        return SocialUserResult::fromSocialiteUser($provider, $user);
    }

    /** @return list<string> */
    public function enabledProviders(): array
    {
        $enabled = [];

        /** @var array<string, array<string, mixed>> $providers */
        $providers = $this->config['providers'] ?? [];

        foreach ($providers as $name => $cfg) {
            if ($this->providerIsConfigured($name, $cfg)) {
                $enabled[] = $name;
            }
        }

        return $enabled;
    }

    public function isProviderEnabled(string $provider): bool
    {
        /** @var array<string, mixed> $cfg */
        $cfg = $this->config['providers'][$provider] ?? [];

        return $this->providerIsConfigured($provider, $cfg);
    }

    public function callbackUrl(string $provider): string
    {
        /** @var callable|null $builder */
        $builder = $this->config['callback_url_builder'] ?? null;

        if (is_callable($builder)) {
            return (string) $builder($provider);
        }

        // Sensible default — works for both Microweber and standalone apps.
        return url('api/social_login_process') . '?provider=' . $provider;
    }

    /**
     * Re-read the config at runtime (useful after the consuming app
     * has populated the config in a service provider boot method).
     */
    public function refreshConfig(): void
    {
        /** @var array<string, mixed> $cfg */
        $cfg = Config::get('social-login', []);
        $this->config = $cfg;
    }

    /* ------------------------------------------------------------------
     | Internals
     | -----------------------------------------------------------------*/

    /**
     * Push the per-provider credentials into Laravel's `services.*` config
     * so that Socialite can read them.
     */
    private function applyServiceConfig(string $provider): void
    {
        /** @var array<string, mixed> $providerConfig */
        $providerConfig = $this->config['providers'][$provider] ?? [];

        $callbackUrl = $this->callbackUrl($provider);

        Config::set("services.{$provider}.client_id", $providerConfig['client_id'] ?? '');
        Config::set("services.{$provider}.client_secret", $providerConfig['client_secret'] ?? '');
        Config::set("services.{$provider}.redirect", $callbackUrl);

        // Register the custom Microweber driver
        if ($provider === 'microweber') {
            $serverUrl = (string) ($providerConfig['server_url'] ?? 'https://mwlogin.com');
            $socialite = $this->socialite;
            $this->socialite->extend('microweber', function () use ($socialite, $serverUrl): MicroweberSocialiteProvider {
                /** @var array{client_id: string, client_secret: string, redirect: string} $svcConfig */
                $svcConfig = Config::get('services.microweber', []);

                /** @var MicroweberSocialiteProvider $mwProvider */
                $mwProvider = $socialite->buildProvider(
                    MicroweberSocialiteProvider::class,
                    $svcConfig
                );

                $mwProvider->setServerUrl($serverUrl);

                return $mwProvider;
            });
        }
    }

    /**
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    private function resolveDriver(string $provider): \Laravel\Socialite\Two\AbstractProvider
    {
        /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
        $driver = $this->socialite->driver($provider);

        return $driver;
    }

    /**
     * @param  array<string, mixed>  $cfg
     */
    private function providerIsConfigured(string $name, array $cfg): bool
    {
        if (empty($cfg['enabled'])) {
            return false;
        }

        $clientId = (string) ($cfg['client_id'] ?? '');
        $clientSecret = (string) ($cfg['client_secret'] ?? '');

        return $clientId !== '' && $clientSecret !== '';
    }
}