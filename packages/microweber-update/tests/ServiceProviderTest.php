<?php

declare(strict_types=1);

namespace MicroweberPackages\Update\Tests;

use MicroweberPackages\Update\Providers\UpdateMigratorServiceProvider;
use PHPUnit\Framework\Attributes\Test;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function service_provider_boots_and_is_registered(): void
    {
        $provider = $this->app->getProvider(UpdateMigratorServiceProvider::class);

        $this->assertInstanceOf(UpdateMigratorServiceProvider::class, $provider);
    }

    #[Test]
    public function service_provider_configures_the_package(): void
    {
        /** @var UpdateMigratorServiceProvider $provider */
        $provider = $this->app->getProvider(UpdateMigratorServiceProvider::class);

        $this->assertTrue($provider->usesMicroweberPackageLoader());
        $this->assertSame('microweber-update', $provider->getPackage()->name);
    }
}
