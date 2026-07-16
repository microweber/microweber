<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\PhpQuery;
use MicroweberPackages\PhpQuery\PhpQueryObject;
use MicroweberPackages\PhpQuery\Callbacks\Callback;
use MicroweberPackages\PhpQuery\Callbacks\CallbackReturnValue;
use MicroweberPackages\PhpQuery\Callbacks\CallbackParam;

class IteratorAndCallbackTest extends TestCase
{
    public function test_foreach_iteration()
    {
        $pq = PhpQuery::newDocument('<ul><li>A</li><li>B</li><li>C</li></ul>');
        $items = [];
        foreach ($pq->find('li') as $li) {
            $items[] = $li->textContent;
        }
        $this->assertEquals(['A', 'B', 'C'], $items);
    }

    public function test_count()
    {
        $pq = PhpQuery::newDocument('<ul><li>A</li><li>B</li></ul>');
        $this->assertEquals(2, count($pq->find('li')));
    }

    public function test_array_access_exists()
    {
        $pq = PhpQuery::newDocument('<div><p>Text</p></div>');
        $div = $pq->find('div');
        $this->assertTrue(isset($div['p']));
    }

    public function test_array_access_get()
    {
        $pq = PhpQuery::newDocument('<div><p>Hello</p></div>');
        $div = $pq->find('div');
        $result = $div['p'];
        $this->assertInstanceOf(PhpQueryObject::class, $result);
        $this->assertEquals('Hello', $result->text());
    }

    public function test_array_access_set()
    {
        $pq = PhpQuery::newDocument('<div><p>Old</p></div>');
        $div = $pq->find('div');
        $div['p'] = 'New';
        $this->assertEquals('New', $pq->find('p')->text());
    }

    public function test_each_callback()
    {
        $pq = PhpQuery::newDocument('<ul><li>A</li><li>B</li></ul>');
        $items = [];
        $pq->find('li')->each(function ($node) use (&$items) {
            $items[] = $node->textContent;
        });
        $this->assertEquals(['A', 'B'], $items);
    }

    public function test_map()
    {
        $pq = PhpQuery::newDocument('<ul><li>A</li><li>B</li><li>C</li></ul>');
        $mapped = $pq->find('li')->map(function ($node) {
            return $node->textContent;
        });
        // map returns a new PhpQueryObject; the mapping results are strings
        $this->assertInstanceOf(PhpQueryObject::class, $mapped);
    }

    public function test_filter_callback()
    {
        $pq = PhpQuery::newDocument('<ul><li class="keep">A</li><li>B</li><li class="keep">C</li></ul>');
        $filtered = $pq->find('li')->filterCallback(function ($index, $node) {
            return $node->getAttribute('class') === 'keep';
        });
        $this->assertEquals(2, $filtered->length());
    }

    public function test_callback_class()
    {
        $callback = new Callback(function ($x) { return $x * 2; });
        $result = PhpQuery::callbackRun($callback, [5]);
        $this->assertEquals(10, $result);
    }

    public function test_callback_return_value()
    {
        $callback = new CallbackReturnValue('hello', 'test');
        $result = PhpQuery::callbackRun($callback, []);
        $this->assertEquals('hello', $result);
    }

    public function test_json_encode()
    {
        $this->assertEquals('{"a":1}', PhpQuery::toJSON(['a' => 1]));
    }

    public function test_json_decode()
    {
        $result = PhpQuery::parseJSON('{"a":1}');
        $this->assertEquals(['a' => 1], $result);
    }

    public function test_static_each()
    {
        $result = [];
        PhpQuery::each(['a', 'b', 'c'], function ($key, $value) use (&$result) {
            $result[] = $value;
        });
        $this->assertEquals(['a', 'b', 'c'], $result);
    }

    public function test_static_grep()
    {
        $result = PhpQuery::grep([1, 2, 3, 4, 5], function ($v) {
            return $v > 3;
        });
        $this->assertEquals([4, 5], array_values($result));
    }

    public function test_static_map()
    {
        $result = PhpQuery::map([1, 2, 3], function ($v) {
            return $v * 2;
        });
        $this->assertEquals([2, 4, 6], $result);
    }

    public function test_unique()
    {
        $result = PhpQuery::unique([1, 2, 2, 3, 3, 3]);
        $this->assertEquals([1, 2, 3], array_values($result));
    }

    public function test_trim()
    {
        $this->assertEquals('hello', PhpQuery::trim('  hello  '));
    }

    public function test_is_function()
    {
        $this->assertTrue(PhpQuery::isFunction('strlen'));
        $this->assertTrue(PhpQuery::isFunction(function () {}));
    }
}
