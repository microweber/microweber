<?php

declare(strict_types=1);

namespace MicroweberPackages\Frontend\Tests;

use MicroweberPackages\Frontend\Providers\FrontendServiceProvider;
use PHPUnit\Framework\Attributes\Test;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function service_provider_boots_and_is_registered(): void
    {
        $this->assertArrayHasKey(
            FrontendServiceProvider::class,
            $this->app->getLoadedProviders(),
            'FrontendServiceProvider should be loaded by the booted app.'
        );
    }

    #[Test]
    public function home_route_is_registered_by_boot(): void
    {
        // FrontendServiceProvider::boot() loads src/routes/web.php, which
        // defines the named "home" route on "/".
        $route = $this->app['router']->getRoutes()->getByName('home');

        $this->assertNotNull($route, 'The "home" route should be registered.');
        $this->assertSame('/', $route->uri());
    }

    #[Test]
    public function home_route_points_at_the_frontend_controller(): void
    {
        $route = $this->app['router']->getRoutes()->getByName('home');

        $this->assertNotNull($route);
        $this->assertStringContainsString(
            'MicroweberPackages\\Frontend\\Http\\Controllers\\FrontendController',
            (string) $route->getActionName(),
            'The "home" route should dispatch to the Frontend controller.'
        );
    }
}
