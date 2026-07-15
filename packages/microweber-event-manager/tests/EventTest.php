<?php

namespace MicroweberPackages\Event\Tests;

use MicroweberPackages\Event\Event;
use PHPUnit\Framework\Attributes\Test;

class EventTest extends TestCase
{
    // ------------------------------------------------------------------
    // Basic bind / trigger
    // ------------------------------------------------------------------

    #[Test]
    public function it_can_bind_and_trigger_event(): void
    {
        $called = false;

        event_bind('some_event', function ($params) use (&$called) {
            $called = true;
            $this->assertArrayHasKey('wow1', $params);
        });

        event_trigger('some_event', ['wow1' => 'waw!1']);
        $this->assertTrue($called);
    }

    #[Test]
    public function it_can_bind_multiple_events(): void
    {
        $calledA = false;
        $calledB = false;

        event_bind('some_event_a', function ($params) use (&$calledA) {
            $calledA = true;
            $this->assertArrayHasKey('wow1', $params);
        });

        event_bind('some_event_b', function ($params) use (&$calledB) {
            $calledB = true;
            $this->assertArrayHasKey('wow2', $params);
        });

        event_trigger('some_event_a', ['wow1' => 'waw!1']);
        event_trigger('some_event_b', ['wow2' => 'waw!2']);

        $this->assertTrue($calledA);
        $this->assertTrue($calledB);
    }

    #[Test]
    public function it_can_bind_multiple_listeners_to_same_event(): void
    {
        $results = [];

        event_bind('multi', function () use (&$results) {
            $results[] = 'first';
        });
        event_bind('multi', function () use (&$results) {
            $results[] = 'second';
        });

        event_trigger('multi');
        $this->assertSame(['first', 'second'], $results);
    }

    #[Test]
    public function it_returns_null_when_no_listeners(): void
    {
        $result = event_trigger('nonexistent_event', ['data' => 'value']);
        $this->assertNull($result);
    }

    #[Test]
    public function trigger_returns_collected_responses(): void
    {
        /** @var Event $manager */
        $manager = app('event_manager');

        $manager->on('returns', fn () => 'alpha');
        $manager->on('returns', fn () => 'beta');

        $result = $manager->trigger('returns');
        $this->assertSame(['alpha', 'beta'], $result);
    }

    // ------------------------------------------------------------------
    // response() merging
    // ------------------------------------------------------------------

    #[Test]
    public function response_preserves_unchanged_criteria(): void
    {
        /** @var Event $manager */
        $manager = app('event_manager');

        event_bind('modify_data', function ($data) {
            // Return the same key with the same value — should be preserved.
            return ['original_key' => 'original_value'];
        });

        $result = $manager->response('modify_data', ['original_key' => 'original_value']);
        $this->assertSame('original_value', $result['original_key']);
    }

    #[Test]
    public function response_overwrites_changed_values(): void
    {
        /** @var Event $manager */
        $manager = app('event_manager');

        $manager->on('overwrite', function () {
            return ['colour' => 'red'];
        });

        $result = $manager->response('overwrite', ['colour' => 'blue']);
        $this->assertSame('red', $result['colour']);
    }

    #[Test]
    public function response_passes_through_dunder_keys(): void
    {
        /** @var Event $manager */
        $manager = app('event_manager');

        $manager->on('dunder', function () {
            return ['__secret' => 42];
        });

        $result = $manager->response('dunder', ['visible' => 'yes']);
        $this->assertSame(42, $result['__secret']);
    }

    // ------------------------------------------------------------------
    // unbind / unbindAll
    // ------------------------------------------------------------------

    #[Test]
    public function unbind_removes_listeners_for_specific_event(): void
    {
        /** @var Event $manager */
        $manager = app('event_manager');

        $manager->on('removable', fn () => 'should not run');
        $this->assertTrue($manager->hasListeners('removable'));

        $manager->unbind('removable');
        $this->assertFalse($manager->hasListeners('removable'));
        $this->assertNull($manager->trigger('removable'));
    }

    #[Test]
    public function unbind_all_clears_every_event(): void
    {
        /** @var Event $manager */
        $manager = app('event_manager');

        $manager->on('ev1', fn () => null);
        $manager->on('ev2', fn () => null);

        $manager->unbindAll();
        $this->assertFalse($manager->hasListeners('ev1'));
        $this->assertFalse($manager->hasListeners('ev2'));
    }

    #[Test]
    public function event_unbind_helper_works(): void
    {
        event_bind('helper_test', fn () => null);

        /** @var Event $manager */
        $manager = app('event_manager');
        $this->assertTrue($manager->hasListeners('helper_test'));

        event_unbind('helper_test');
        $this->assertFalse($manager->hasListeners('helper_test'));
    }

    #[Test]
    public function event_unbind_all_helper_works(): void
    {
        event_bind('all_a', fn () => null);
        event_bind('all_b', fn () => null);

        event_unbind_all();

        /** @var Event $manager */
        $manager = app('event_manager');
        $this->assertFalse($manager->hasListeners('all_a'));
        $this->assertFalse($manager->hasListeners('all_b'));
    }

    // ------------------------------------------------------------------
    // hasListeners
    // ------------------------------------------------------------------

    #[Test]
    public function has_listeners_returns_false_when_none(): void
    {
        /** @var Event $manager */
        $manager = app('event_manager');
        $this->assertFalse($manager->hasListeners('empty'));
    }

    #[Test]
    public function has_listeners_returns_true_when_bound(): void
    {
        /** @var Event $manager */
        $manager = app('event_manager');
        $manager->on('occupied', fn () => null);
        $this->assertTrue($manager->hasListeners('occupied'));
    }

    // ------------------------------------------------------------------
    // No static state leaks between tests
    // ------------------------------------------------------------------

    #[Test]
    public function no_state_leaks_from_previous_test_part_one(): void
    {
        event_bind('leak_check', fn () => 'part_one');
        $this->assertTrue(app('event_manager')->hasListeners('leak_check'));
    }

    #[Test]
    public function no_state_leaks_from_previous_test_part_two(): void
    {
        // If static state leaked, 'leak_check' would still have a listener.
        // The container is rebuilt between Orchestra tests, so the singleton
        // and its instance adapter should be fresh.
        /** @var Event $manager */
        $manager = app('event_manager');
        $this->assertFalse($manager->hasListeners('leak_check'));
    }

    // ------------------------------------------------------------------
    // Shutdown callbacks
    // ------------------------------------------------------------------

    #[Test]
    public function register_shutdown_event_executes_on_call(): void
    {
        /** @var Event $manager */
        $manager = app('event_manager');

        $executed = false;
        $manager->registerShutdownEvent(function () use (&$executed) {
            $executed = true;
        });

        $manager->callRegisteredShutdown();
        $this->assertTrue($executed);
    }

    #[Test]
    public function shutdown_callbacks_are_cleared_after_execution(): void
    {
        /** @var Event $manager */
        $manager = app('event_manager');

        $count = 0;
        $manager->registerShutdownEvent(function () use (&$count) {
            $count++;
        });

        $manager->callRegisteredShutdown();
        $manager->callRegisteredShutdown(); // second call should be a no-op

        $this->assertSame(1, $count);
    }

    // ------------------------------------------------------------------
    // Adapter access
    // ------------------------------------------------------------------

    #[Test]
    public function get_adapter_returns_laravel_event_instance(): void
    {
        /** @var Event $manager */
        $manager = app('event_manager');
        $this->assertInstanceOf(\MicroweberPackages\Event\LaravelEvent::class, $manager->getAdapter());
    }
}