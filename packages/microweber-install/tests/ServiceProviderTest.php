<?php

declare(strict_types=1);

namespace MicroweberPackages\Install\Tests;

use Illuminate\Contracts\Foundation\Application;
use MicroweberPackages\Install\InstallServiceProvider;
use PHPUnit\Framework\Attributes\Test;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function application_boots_with_the_package_provider(): void
    {
        $this->assertInstanceOf(Application::class, $this->app);
        $this->assertTrue(
            $this->app->providerIsLoaded(InstallServiceProvider::class),
            'InstallServiceProvider should be registered and loaded.'
        );
    }

    #[Test]
    public function install_views_namespace_is_registered(): void
    {
        $this->assertTrue(
            $this->app['view']->getFinder()->getHints()['install'] !== null,
            'The "install" view namespace should be registered.'
        );
    }
}
