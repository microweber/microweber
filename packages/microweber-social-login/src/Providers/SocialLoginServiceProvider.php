<?php

declare(strict_types=1);

namespace MicroweberPackages\SocialLogin\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\SocialLogin\Contracts\SocialLoginServiceContract;
use MicroweberPackages\SocialLogin\Services\SocialLoginService;

class SocialLoginServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/social-login.php', 'social-login');

        $this->app->singleton(SocialLoginServiceContract::class, SocialLoginService::class);
        $this->app->singleton(SocialLoginService::class, SocialLoginService::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/social-login.php' => $this->app->configPath('social-login.php'),
            ], 'social-login-config');
        }
    }
}
