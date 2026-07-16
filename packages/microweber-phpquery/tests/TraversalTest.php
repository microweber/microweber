<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\PhpQuery;

class TraversalTest extends TestCase
{
    private function doc()
    {
        return PhpQuery::newDocument('
            <div id="root">
                <ul>
                    <li class="first">One</li>
                    <li class="second active">Two</li>
                    <li class="third">Three</li>
                </ul>
                <p class="para">Text</p>
            </div>
        ');
    }

    public function test_parent()
    {
        $parent = $this->doc()->find('.second')->parent();
        $this->assertEquals('ul', $parent->get(0)->tagName);
    }

    public function test_parents()
    {
        $parents = $this->doc()->find('.second')->parents();
        $this->assertGreaterThanOrEqual(2, $parents->length());
    }

    public function test_children()
    {
        $children = $this->doc()->find('ul')->children();
        $this->assertEquals(3, $children->length());
    }

    public function test_children_with_selector()
    {
        $children = $this->doc()->find('ul')->children('.active');
        $this->assertEquals(1, $children->length());
    }

    public function test_siblings()
    {
        $siblings = $this->doc()->find('.second')->siblings();
        $this->assertEquals(2, $siblings->length());
    }

    public function test_next()
    {
        $next = $this->doc()->find('.first')->_next();
        $this->assertTrue($next->hasClass('second'));
    }

    public function test_next_all()
    {
        $nextAll = $this->doc()->find('.first')->nextAll();
        $this->assertEquals(2, $nextAll->length());
    }

    public function test_prev()
    {
        $prev = $this->doc()->find('.third')->prev();
        $this->assertTrue($prev->hasClass('second'));
    }

    public function test_prev_all()
    {
        $prevAll = $this->doc()->find('.third')->prevAll();
        $this->assertEquals(2, $prevAll->length());
    }

    public function test_eq()
    {
        $second = $this->doc()->find('li')->eq(1);
        $this->assertEquals('Two', $second->text());
    }

    public function test_first()
    {
        $first = $this->doc()->find('li:first');
        $this->assertEquals('One', $first->text());
    }

    public function test_last()
    {
        $last = $this->doc()->find('li:last');
        $this->assertEquals('Three', $last->text());
    }

    public function test_filter()
    {
        $filtered = $this->doc()->find('li')->filter('.active');
        $this->assertEquals(1, $filtered->length());
    }

    public function test_not()
    {
        $filtered = $this->doc()->find('li')->not('.active');
        $this->assertEquals(2, $filtered->length());
    }

    public function test_is()
    {
        $this->assertTrue((bool) $this->doc()->find('.second')->is('.active'));
        $this->assertFalse((bool) $this->doc()->find('.first')->is('.active'));
    }

    public function test_end()
    {
        $pq = $this->doc();
        $result = $pq->find('ul')->find('li')->end();
        $this->assertEquals('ul', $result->get(0)->tagName);
    }

    public function test_contents()
    {
        $pq = PhpQuery::newDocument('<div>Text <span>and span</span></div>');
        $contents = $pq->find('div')->contents();
        $this->assertGreaterThan(0, $contents->length());
    }

    public function test_add()
    {
        $pq = $this->doc();
        $combined = $pq->find('li.first')->add('li.third');
        $this->assertEquals(2, $combined->length());
    }

    public function test_slice()
    {
        $sliced = $this->doc()->find('li')->slice(1, 3);
        $this->assertEquals(2, $sliced->length());
    }

    public function test_reverse()
    {
        $pq = $this->doc();
        $reversed = $pq->find('li')->reverse();
        $this->assertEquals('Three', $reversed->eq(0)->text());
        $this->assertEquals('One', $reversed->eq(2)->text());
    }

    public function test_get_array()
    {
        $nodes = $this->doc()->find('li')->get();
        $this->assertIsArray($nodes);
        $this->assertCount(3, $nodes);
    }

    public function test_get_single()
    {
        $node = $this->doc()->find('li')->get(0);
        $this->assertInstanceOf(\DOMElement::class, $node);
    }

    public function test_stack()
    {
        $stack = $this->doc()->find('li')->stack();
        $this->assertIsArray($stack);
        $this->assertCount(3, $stack);
    }

    public function test_index()
    {
        $pq = $this->doc();
        $target = $pq->find('.second')->get(0);
        $idx = $pq->find('li')->index($target);
        $this->assertEquals(1, $idx);
    }

    public function test_and_self()
    {
        $pq = $this->doc();
        $ul = $pq->find('ul');
        $children = $ul->find('li')->andSelf();
        // Should include ul + 3 li = 4 elements
        $this->assertEquals(4, $children->length());
    }
}
