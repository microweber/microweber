<?php

declare(strict_types=1);

namespace MicroweberPackages\Http\Tests;

use MicroweberPackages\Http\HttpService;
use PHPUnit\Framework\Attributes\Test;

class HttpServiceProviderTest extends TestCase
{
    #[Test]
    public function it_registers_http_binding(): void
    {
        $http = $this->app->make(\MicroweberPackages\Http\HttpService::class);

        $this->assertInstanceOf(HttpService::class, $http);
    }

    #[Test]
    public function it_resolves_fresh_instance_each_time(): void
    {
        $http1 = $this->app->make(\MicroweberPackages\Http\HttpService::class);
        $http2 = $this->app->make(\MicroweberPackages\Http\HttpService::class);

        $this->assertNotSame($http1, $http2);
    }

    #[Test]
    public function it_http_instance_has_guzzle_adapter(): void
    {
        $http = $this->app->make(\MicroweberPackages\Http\HttpService::class);

        $this->assertInstanceOf(
            \MicroweberPackages\Http\Adapters\Guzzle::class,
            $http->adapter
        );
    }
}