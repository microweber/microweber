<?php

declare(strict_types=1);

namespace MicroweberPackages\Monitoring\Tests;

use MicroweberPackages\Monitoring\Services\ErrorTrackingService;
use PHPUnit\Framework\Attributes\Test;

class ResolvesTest extends TestCase
{
    #[Test]
    public function error_tracking_service_resolves_from_the_container(): void
    {
        $svc = $this->app->make(ErrorTrackingService::class);

        $this->assertInstanceOf(ErrorTrackingService::class, $svc);
    }

    #[Test]
    public function key_package_classes_are_autoloadable(): void
    {
        $this->assertTrue(class_exists(ErrorTrackingService::class));
        $this->assertTrue(class_exists(\MicroweberPackages\Monitoring\Providers\MonitoringServiceProvider::class));
        $this->assertTrue(class_exists(\MicroweberPackages\Monitoring\Models\ErrorTracking::class));
    }
}
