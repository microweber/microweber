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

        $this->app->singleton('system_licenses_manager', function ($app) {
            return new SystemLicensesManager(
                $app->make(LicenseValidatorInterface::class)
            );
        });

        $this->app->alias('system_licenses_manager', SystemLicensesManager::class);
    }

    /**
     * @return list<string>
     */
    public function provides(): array
    {
        return [
            'system_licenses_manager',
            SystemLicensesManager::class,
            LicenseValidatorInterface::class,
        ];
    }
}