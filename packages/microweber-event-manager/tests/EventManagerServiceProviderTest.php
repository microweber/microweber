<?php

namespace MicroweberPackages\Event\Tests;

use MicroweberPackages\Event\Event;
use MicroweberPackages\Event\EventManagerServiceProvider;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function it_provides_event_manager_key(): void
    {
        $provider = new EventManagerServiceProvider($this->app);
        $this->assertContains('event_manager', $provider->provides());
    }

    #[Test]
    public function terminating_hook_clears_listeners(): void
    {
        /** @var Event $manager */
        $manager = app('event_manager');
        $manager->on('will_be_cleared', fn () => null);
        $this->assertTrue($manager->hasListeners('will_be_cleared'));

        // Simulate app termination
        $this->app->terminate();

        $this->assertFalse($manager->hasListeners('will_be_cleared'));
    }
}