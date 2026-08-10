<?php

namespace MicroweberPackages\SystemLicenses;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\SystemLicenses\Contracts\LicenseValidatorInterface;
use MicroweberPackages\SystemLicenses\Validators\NullLicenseValidator;

class SystemLicensesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register(): void
    {
        // Bind the validator interface — standalone apps get the null validator;
        // Microweber CMS overrides this with its own implementation.
        if (!$this->app->bound(LicenseValidatorInterface::class)) {
            $this->app->singleton(LicenseValidatorInterface::class, NullLicenseValidator::class);
        }

        $this->app->singleton(SystemLicensesManager::class, function ($app) {
            return new SystemLicensesManager(
                $app->make(LicenseValidatorInterface::class)
            );
        });
    }

    /**
     * @return list<string>
     */
    public function provides(): array
    {
        return [
            SystemLicensesManager::class,
            LicenseValidatorInterface::class,
        ];
    }
}
