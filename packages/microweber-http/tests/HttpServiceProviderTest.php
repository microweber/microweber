<?php

declare(strict_types=1);

namespace MicroweberPackages\Http\Tests;

use MicroweberPackages\Http\Http;
use PHPUnit\Framework\Attributes\Test;

class HttpServiceProviderTest extends TestCase
{
    #[Test]
    public function it_registers_http_binding(): void
    {
        $http = $this->app->make('http');

        $this->assertInstanceOf(Http::class, $http);
    }

    #[Test]
    public function it_resolves_fresh_instance_each_time(): void
    {
        $http1 = $this->app->make('http');
        $http2 = $this->app->make('http');

        $this->assertNotSame($http1, $http2);
    }

    #[Test]
    public function it_http_instance_has_guzzle_adapter(): void
    {
        $http = $this->app->make('http');

        $this->assertInstanceOf(
            \MicroweberPackages\Http\Adapters\Guzzle::class,
            $http->adapter
        );
    }
}