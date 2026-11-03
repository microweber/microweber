<?php

declare(strict_types=1);

namespace MicroweberPackages\LaravelHelperFunctions\Tests;

use MicroweberPackages\LaravelHelperFunctions\Providers\LaravelHelperFunctionsServiceProvider;
use PHPUnit\Framework\Attributes\Test;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function service_provider_boots_and_is_registered(): void
    {
        $provider = $this->app->getProvider(LaravelHelperFunctionsServiceProvider::class);

        $this->assertInstanceOf(
            LaravelHelperFunctionsServiceProvider::class,
            $provider,
            'The package service provider should be registered on the application.'
        );
    }

    #[Test]
    public function service_provider_uses_microweber_package_loader(): void
    {
        /** @var LaravelHelperFunctionsServiceProvider $provider */
        $provider = $this->app->getProvider(LaravelHelperFunctionsServiceProvider::class);

        $this->assertTrue(
            $provider->usesMicroweberPackageLoader(),
            'Provider should extend the Microweber package loader base.'
        );
        $this->assertSame(
            'microweber-packages/laravel-helper-functions',
            $provider->getPackage()->name,
            'configurePackage() should set the package name.'
        );
    }
}
