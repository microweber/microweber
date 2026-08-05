<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Tests\Feature;

use MicroweberPackages\PackageManagerClient\InstallDirDetector;
use MicroweberPackages\PackageManagerClient\PackageManagerClient;
use MicroweberPackages\PackageManagerClient\PackageManagerClientServiceProvider;
use MicroweberPackages\PackageManagerClient\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function it_registers_client_and_detector(): void
    {
        $this->assertTrue($this->app->bound(PackageManagerClient::class));
        $this->assertTrue($this->app->bound(InstallDirDetector::class));
        $this->assertInstanceOf(PackageManagerClient::class, $this->app->make(PackageManagerClient::class));
        $this->assertInstanceOf(InstallDirDetector::class, $this->app->make(InstallDirDetector::class));
    }

    #[Test]
    public function it_has_config(): void
    {
        $cfg = config('package-manager-client');
        $this->assertIsArray($cfg);
        $this->assertArrayHasKey('package_servers', $cfg);
        $this->assertArrayHasKey('modules_path', $cfg);
        $this->assertArrayHasKey('templates_path', $cfg);
    }

    #[Test]
    public function provider_class_exists(): void
    {
        $this->assertTrue(class_exists(PackageManagerClientServiceProvider::class));
    }
}
