<?php

declare(strict_types=1);

namespace MicroweberPackages\Http\Tests;

use MicroweberPackages\Http\Http;
use MicroweberPackages\Http\Adapters\Guzzle;
use PHPUnit\Framework\Attributes\Test;

class HttpTest extends TestCase
{
    #[Test]
    public function it_creates_instance_with_guzzle_adapter(): void
    {
        $http = new Http();

        $this->assertInstanceOf(Guzzle::class, $http->adapter);
    }

    #[Test]
    public function it_set_url_returns_self_for_chaining(): void
    {
        $http = new Http();
        $result = $http->url('https://example.com');

        $this->assertSame($http, $result);
        $this->assertSame('https://example.com', $http->url);
        $this->assertSame('https://example.com', $http->adapter->url);
    }

    #[Test]
    public function it_set_timeout_returns_self_for_chaining(): void
    {
        $http = new Http();
        $result = $http->set_timeout(120);

        $this->assertSame($http, $result);
        $this->assertSame(120, $http->adapter->timeout);
    }

    #[Test]
    public function it_set_cache_returns_self_for_chaining(): void
    {
        $http = new Http();
        $result = $http->set_cache(3600);

        $this->assertSame($http, $result);
        $this->assertSame(3600, $http->cache);
    }

    #[Test]
    public function it_default_cache_is_false(): void
    {
        $http = new Http();

        $this->assertFalse($http->cache);
    }

    #[Test]
    public function it_default_url_is_false(): void
    {
        $http = new Http();

        $this->assertFalse($http->url);
    }
}