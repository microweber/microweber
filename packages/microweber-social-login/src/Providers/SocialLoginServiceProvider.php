<?php

declare(strict_types=1);

namespace MicroweberPackages\SocialLogin\Providers;

use MicroweberPackages\SocialLogin\Contracts\SocialLoginServiceContract;
use MicroweberPackages\SocialLogin\Services\SocialLoginService;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class SocialLoginServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber/social-login');
    }

    public function packageRegistered(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/social-login.php', 'social-login');

        $this->app->singleton(SocialLoginServiceContract::class, SocialLoginService::class);
        $this->app->singleton(SocialLoginService::class, SocialLoginService::class);
    }

    public function packageBooted(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/social-login.php' => $this->app->configPath('social-login.php'),
            ], 'social-login-config');
        }
    }
}
