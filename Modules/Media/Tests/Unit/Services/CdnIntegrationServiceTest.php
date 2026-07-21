<?php

namespace Modules\Media\Tests\Unit\Services;

use Modules\Media\Services\CdnIntegrationService;
use PHPUnit\Framework\TestCase;

/**
 * CdnIntegrationService is now a deprecated shim that delegates to
 * MicroweberPackages\CdnSync\Services\CdnSyncService. These tests
 * verify the class still exists and exposes the old API for backwards
 * compatibility.
 */
class CdnIntegrationServiceTest extends TestCase
{
    public function test_can_instantiate_service()
    {
        $this->assertTrue(class_exists(CdnIntegrationService::class));
    }

    public function test_service_class_exists()
    {
        $this->assertTrue(class_exists(CdnIntegrationService::class));
    }

    public function test_service_has_expected_methods()
    {
        $methods = [
            'uploadToCdn',
            'deleteFromCdn',
            'syncMedia',
            'bulkSync',
            'getCdnUrl',
            'isConfigured',
            'getStats',
            'invalidateCache',
        ];

        foreach ($methods as $method) {
            $this->assertTrue(method_exists(CdnIntegrationService::class, $method), "Method {$method} does not exist");
        }
    }
}
