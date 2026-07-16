<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\PhpQuery;
use MicroweberPackages\PhpQuery\Events\DOMEvent;
use MicroweberPackages\PhpQuery\Events\PhpQueryEvents;

class EventTest extends TestCase
{
    public function test_dom_event_creation()
    {
        $event = new DOMEvent([
            'type' => 'click',
            'target' => null,
        ]);
        $this->assertEquals('click', $event->type);
        $this->assertTrue($event->bubbles);
        $this->assertTrue($event->cancelable);
        $this->assertNotNull($event->timeStamp);
    }

    public function test_dom_event_prevent_default()
    {
        $event = new DOMEvent(['type' => 'click']);
        $this->assertTrue($event->runDefault);
        $event->preventDefault();
        $this->assertFalse($event->runDefault);
    }

    public function test_dom_event_stop_propagation()
    {
        $event = new DOMEvent(['type' => 'click']);
        $this->assertTrue($event->bubbles);
        $event->stopPropagation();
        $this->assertFalse($event->bubbles);
    }

    public function test_bind_and_trigger()
    {
        $pq = PhpQuery::newDocument('<div><button>Click</button></div>');
        $triggered = false;

        $pq->find('button')->bind('click', function ($event) use (&$triggered) {
            $triggered = true;
        });

        $pq->find('button')->trigger('click');
        $this->assertTrue($triggered);
    }

    public function test_unbind()
    {
        $pq = PhpQuery::newDocument('<div><button>Click</button></div>');
        $count = 0;
        $handler = function () use (&$count) { $count++; };

        $pq->find('button')->bind('click', $handler);
        $pq->find('button')->trigger('click');
        $this->assertEquals(1, $count);

        $pq->find('button')->unbind('click', $handler);
        $pq->find('button')->trigger('click');
        // After unbind, count should not increase
        $this->assertEquals(1, $count);
    }

    public function test_change_event()
    {
        $pq = PhpQuery::newDocument('<form><input type="text" value="old"></form>');
        $changed = false;
        $pq->find('input')->bind('change', function () use (&$changed) {
            $changed = true;
        });
        $pq->find('input')->change();
        $this->assertTrue($changed);
    }

    public function test_click_event()
    {
        $pq = PhpQuery::newDocument('<button>Click</button>');
        $clicked = false;
        $pq->find('button')->bind('click', function () use (&$clicked) {
            $clicked = true;
        });
        $pq->find('button')->click();
        $this->assertTrue($clicked);
    }
}
