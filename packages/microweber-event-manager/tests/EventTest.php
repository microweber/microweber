<?php

namespace MicroweberPackages\Event\Tests;

use PHPUnit\Framework\Attributes\Test;

class EventTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Reset hooks between tests
        \MicroweberPackages\Event\LaravelEvent::$hooks = [];
    }

    #[Test]
    public function it_can_bind_and_trigger_event(): void
    {
        $unitTest = $this;

        event_bind('some_event', function ($params) use ($unitTest) {
            $unitTest->assertArrayHasKey('wow1', $params);
        });

        event_trigger('some_event', array('wow1' => 'waw!1'));
    }

    #[Test]
    public function it_can_bind_multiple_events(): void
    {
        $unitTest = $this;

        event_bind('some_event', function ($params) use ($unitTest) {
            $unitTest->assertArrayHasKey('wow1', $params);
        });

        event_bind('some_event2', function ($params) use ($unitTest) {
            $unitTest->assertArrayHasKey('wow2', $params);
        });

        event_trigger('some_event', array('wow1' => 'waw!1'));
        event_trigger('some_event2', array('wow2' => 'waw!2'));
    }

    #[Test]
    public function it_can_use_response_method(): void
    {
        $eventManager = app('event_manager');

        event_bind('modify_data', function ($data) {
            return array_merge($data, ['added_key' => 'added_value']);
        });

        $result = $eventManager->response('modify_data', ['original_key' => 'original_value']);
        $this->assertArrayHasKey('original_key', $result);
    }

    #[Test]
    public function it_returns_empty_when_no_listeners(): void
    {
        $result = event_trigger('nonexistent_event', ['data' => 'value']);
        $this->assertNull($result);
    }
}