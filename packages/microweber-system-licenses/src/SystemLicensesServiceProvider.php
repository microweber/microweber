<?php

namespace MicroweberPackages\SystemLicenses;

use MicroweberPackages\SystemLicenses\Contracts\LicenseValidatorInterface;
use MicroweberPackages\SystemLicenses\Validators\NullLicenseValidator;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class SystemLicensesServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/system-licenses');
    }

    public function packageBooted(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function packageRegistered(): void
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
