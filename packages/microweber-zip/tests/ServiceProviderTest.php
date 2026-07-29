<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Tests;

use MicroweberPackages\Zip\Support\ZipBombGuard;
use MicroweberPackages\Zip\Unzip;
use MicroweberPackages\Zip\Zip;
use MicroweberPackages\Zip\ZipServiceProvider;
use PHPUnit\Framework\Attributes\Test;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function it_registers_bindings(): void
    {
        if (!method_exists($this, 'app') && !isset($this->app)) {
            $this->markTestSkipped('Container not available outside Laravel.');
        }

        $this->assertTrue($this->app->bound(ZipBombGuard::class));
        $this->assertTrue($this->app->bound(Unzip::class));
        $this->assertTrue($this->app->bound(Zip::class));

        $guard = $this->app->make(ZipBombGuard::class);
        $this->assertInstanceOf(ZipBombGuard::class, $guard);

        $unzip = $this->app->make(Unzip::class);
        $this->assertInstanceOf(Unzip::class, $unzip);
    }

    #[Test]
    public function it_merges_config(): void
    {
        if (!function_exists('config')) {
            $this->markTestSkipped('config() helper not available.');
        }

        $maxFiles = config('zip.max_files');
        $this->assertNotNull($maxFiles);
        $this->assertIsInt((int) $maxFiles);
    }

    #[Test]
    public function provider_class_exists(): void
    {
        $this->assertTrue(class_exists(ZipServiceProvider::class));
    }
}
