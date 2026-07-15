<?php

namespace MicroweberPackages\Event\Tests;

use MicroweberPackages\Event\LaravelEvent;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Unit tests for the LaravelEvent adapter (no container needed).
 */
class LaravelEventTest extends PHPUnitTestCase
{
    private LaravelEvent $adapter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adapter = new LaravelEvent();
    }

    #[Test]
    public function listen_and_fire(): void
    {
        $this->adapter->listen('ping', fn () => 'pong');
        $result = $this->adapter->fire('ping');
        $this->assertSame(['pong'], $result);
    }

    #[Test]
    public function fire_returns_null_when_no_listeners(): void
    {
        $this->assertNull($this->adapter->fire('nothing'));
    }

    #[Test]
    public function fire_passes_data_to_listeners(): void
    {
        $this->adapter->listen('echo', fn ($data) => $data);
        $result = $this->adapter->fire('echo', ['key' => 'val']);
        $this->assertSame([['key' => 'val']], $result);
    }

    #[Test]
    public function multiple_listeners_are_called_in_order(): void
    {
        $this->adapter->listen('order', fn () => 'A');
        $this->adapter->listen('order', fn () => 'B');

        $this->assertSame(['A', 'B'], $this->adapter->fire('order'));
    }

    #[Test]
    public function unbind_removes_specific_event(): void
    {
        $this->adapter->listen('temp', fn () => 'x');
        $this->adapter->unbind('temp');

        $this->assertFalse($this->adapter->hasListeners('temp'));
        $this->assertNull($this->adapter->fire('temp'));
    }

    #[Test]
    public function unbind_all_clears_everything(): void
    {
        $this->adapter->listen('a', fn () => null);
        $this->adapter->listen('b', fn () => null);

        $this->adapter->unbindAll();

        $this->assertSame([], $this->adapter->getHooks());
    }

    #[Test]
    public function has_listeners_is_accurate(): void
    {
        $this->assertFalse($this->adapter->hasListeners('x'));

        $this->adapter->listen('x', fn () => null);
        $this->assertTrue($this->adapter->hasListeners('x'));

        $this->adapter->unbind('x');
        $this->assertFalse($this->adapter->hasListeners('x'));
    }

    #[Test]
    public function get_hooks_returns_internal_state(): void
    {
        $fn = fn () => null;
        $this->adapter->listen('peek', $fn);

        $hooks = $this->adapter->getHooks();
        $this->assertArrayHasKey('peek', $hooks);
        $this->assertCount(1, $hooks['peek']);
    }

    #[Test]
    public function separate_instances_do_not_share_state(): void
    {
        $other = new LaravelEvent();

        $this->adapter->listen('isolated', fn () => null);
        $this->assertFalse($other->hasListeners('isolated'));
    }

    #[Test]
    public function callable_string_listeners_are_invoked(): void
    {
        // Use a named function that exists in the PHP global scope.
        $this->adapter->listen('strlen_event', 'strlen');

        $result = $this->adapter->fire('strlen_event', 'hello');
        $this->assertSame([5], $result);
    }
}