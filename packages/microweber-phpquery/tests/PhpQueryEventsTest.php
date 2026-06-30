<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\PhpQuery;
use MicroweberPackages\PhpQuery\Events\PhpQueryEvents;

class PhpQueryEventsTest extends TestCase
{
    public function test_bind_and_trigger_event()
    {
        $pq = PhpQuery::newDocument('<div><p>Click me</p></div>');
        $triggered = false;

        $pq->find('p')->bind('click', function () use (&$triggered) {
            $triggered = true;
        });

        $pq->find('p')->trigger('click');

        $this->assertTrue($triggered);
    }

    public function test_unbind_event()
    {
        $pq = PhpQuery::newDocument('<div><p>Click me</p></div>');
        $count = 0;

        $callback = function () use (&$count) {
            $count++;
        };

        $pq->find('p')->bind('click', $callback);
        $pq->find('p')->trigger('click');
        $this->assertEquals(1, $count);

        $pq->find('p')->unbind('click', $callback);
        $pq->find('p')->trigger('click');
        // After unbind, should not increment
        $this->assertEquals(1, $count);
    }

    public function test_change_event()
    {
        $pq = PhpQuery::newDocument('<form><input type="text"></form>');
        $changed = false;

        $pq->find('input')->change(function () use (&$changed) {
            $changed = true;
        });

        $pq->find('input')->trigger('change');
        $this->assertTrue($changed);
    }
}