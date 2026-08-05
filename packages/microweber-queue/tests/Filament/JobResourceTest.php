<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Tests\Filament;

use MicroweberPackages\Queue\Filament\QueuePlugin;
use MicroweberPackages\Queue\Filament\Resources\FailedJobResource;
use MicroweberPackages\Queue\Filament\Resources\JobResource;
use MicroweberPackages\Queue\Tests\TestCase;

class JobResourceTest extends TestCase
{
    public function test_resource_classes_exist(): void
    {
        $this->assertTrue(class_exists(JobResource::class));
        $this->assertTrue(class_exists(FailedJobResource::class));
        $this->assertTrue(class_exists(QueuePlugin::class));
    }

    public function test_plugin_id(): void
    {
        $plugin = QueuePlugin::make();
        $this->assertSame('microweber-queue', $plugin->getId());
    }

    public function test_navigation_meta(): void
    {
        $this->assertSame('queue-jobs', JobResource::getSlug());
        $this->assertSame('failed-jobs', FailedJobResource::getSlug());
        $this->assertSame('Queue Jobs', JobResource::getNavigationLabel());
        $this->assertSame('Failed Jobs', FailedJobResource::getNavigationLabel());
    }

    public function test_can_access_returns_bool(): void
    {
        $this->assertIsBool(JobResource::canAccess());
        $this->assertIsBool(FailedJobResource::canAccess());
    }

    public function test_cannot_create_jobs_via_resource(): void
    {
        $this->assertFalse(JobResource::canCreate());
        $this->assertFalse(FailedJobResource::canCreate());
    }

    public function test_pages_registered(): void
    {
        $this->assertArrayHasKey('index', JobResource::getPages());
        $this->assertArrayHasKey('index', FailedJobResource::getPages());
    }
}
