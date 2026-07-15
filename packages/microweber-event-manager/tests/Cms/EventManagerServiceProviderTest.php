<?php

namespace MicroweberPackages\Event\Tests\Cms;

use MicroweberPackages\Event\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventManagerServiceProviderTest extends TestCase
{
    #[Test]
    public function it_registers_event_manager_singleton(): void
    {
        $this->assertInstanceOf(Event::class, app('event_manager'));
    }

    #[Test]
    public function it_returns_same_instance(): void
    {
        $instance1 = app('event_manager');
        $instance2 = app('event_manager');
        $this->assertSame($instance1, $instance2);
    }
}