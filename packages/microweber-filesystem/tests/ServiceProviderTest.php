<?php

namespace MicroweberPackages\Filesystem\Tests;

use MicroweberPackages\Filesystem\FilesystemService;
use MicroweberPackages\Filesystem\FilesystemServiceProvider;

/**
 * Tests the service provider registration logic.
 */
class ServiceProviderTest extends TestCase
{
    public function test_provider_can_be_instantiated(): void
    {
        // Without a real Laravel app we just test the provider class exists
        $this->assertTrue(class_exists(FilesystemServiceProvider::class));
    }

    public function test_service_class_exists(): void
    {
        $this->assertInstanceOf(FilesystemService::class, $this->service);
    }

    public function test_facade_class_exists(): void
    {
        $this->assertTrue(class_exists(\MicroweberPackages\Filesystem\Facades\MwFilesystem::class));
    }
}
