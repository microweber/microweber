<?php

namespace MicroweberPackages\Event\Tests\Cms;

use MicroweberPackages\Event\EventService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use MicroweberPackages\Event\Facades\EventManager;

class EventManagerServiceProviderTest extends TestCase
{
    #[Test]
    public function it_registers_event_manager_singleton(): void
    {
        $this->assertInstanceOf(EventService::class, EventManager::getFacadeRoot());
    }

    #[Test]
    public function it_returns_same_instance(): void
    {
        $instance1 = EventManager::getFacadeRoot();
        $instance2 = EventManager::getFacadeRoot();
        $this->assertSame($instance1, $instance2);
    }
}