<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\Events\DOMEvent;

class DOMEventTest extends TestCase
{
    public function test_event_creation_with_data()
    {
        $event = new DOMEvent([
            'type' => 'click',
            'target' => 'someNode',
        ]);

        $this->assertEquals('click', $event->type);
        $this->assertEquals('someNode', $event->target);
        $this->assertNotNull($event->timeStamp);
        $this->assertTrue($event->bubbles);
        $this->assertTrue($event->cancelable);
        $this->assertTrue($event->runDefault);
        $this->assertNull($event->data);
    }

    public function test_prevent_default()
    {
        $event = new DOMEvent(['type' => 'submit']);
        $this->assertTrue($event->runDefault);

        $event->preventDefault();
        $this->assertFalse($event->runDefault);
    }

    public function test_stop_propagation()
    {
        $event = new DOMEvent(['type' => 'click']);
        $this->assertTrue($event->bubbles);

        $event->stopPropagation();
        $this->assertFalse($event->bubbles);
    }

    public function test_custom_timestamp()
    {
        $ts = 1234567890;
        $event = new DOMEvent(['type' => 'load', 'timeStamp' => $ts]);
        $this->assertEquals($ts, $event->timeStamp);
    }

    public function test_auto_timestamp_when_not_provided()
    {
        $before = time();
        $event = new DOMEvent(['type' => 'test']);
        $after = time();

        $this->assertGreaterThanOrEqual($before, $event->timeStamp);
        $this->assertLessThanOrEqual($after, $event->timeStamp);
    }
}