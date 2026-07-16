<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\PhpQuery;

class AttributeTest extends TestCase
{
    public function test_attr_get()
    {
        $pq = PhpQuery::newDocument('<a href="http://example.com" title="Link">Click</a>');
        $this->assertEquals('http://example.com', $pq->find('a')->attr('href'));
        $this->assertEquals('Link', $pq->find('a')->attr('title'));
    }

    public function test_attr_set()
    {
        $pq = PhpQuery::newDocument('<a href="#">Link</a>');
        $pq->find('a')->attr('href', 'http://new.com');
        $this->assertEquals('http://new.com', $pq->find('a')->attr('href'));
    }

    public function test_attr_set_new()
    {
        $pq = PhpQuery::newDocument('<div>Test</div>');
        $pq->find('div')->attr('data-custom', 'value');
        $this->assertEquals('value', $pq->find('div')->attr('data-custom'));
    }

    public function test_attr_get_nonexistent()
    {
        $pq = PhpQuery::newDocument('<div>Test</div>');
        $this->assertNull($pq->find('div')->attr('nonexistent'));
    }

    public function test_attr_all()
    {
        $pq = PhpQuery::newDocument('<a href="#" title="Test">Link</a>');
        $attrs = $pq->find('a')->attr('*');
        $this->assertIsArray($attrs);
        $this->assertArrayHasKey('href', $attrs);
        $this->assertArrayHasKey('title', $attrs);
    }

    public function test_remove_attr()
    {
        $pq = PhpQuery::newDocument('<a href="#" title="Test">Link</a>');
        $pq->find('a')->removeAttr('title');
        $this->assertNull($pq->find('a')->attr('title'));
    }

    public function test_add_class()
    {
        $pq = PhpQuery::newDocument('<p>Text</p>');
        $pq->find('p')->addClass('highlight');
        $this->assertTrue($pq->find('p')->hasClass('highlight'));
    }

    public function test_add_class_multiple()
    {
        $pq = PhpQuery::newDocument('<p class="existing">Text</p>');
        $pq->find('p')->addClass('new');
        $this->assertTrue($pq->find('p')->hasClass('existing'));
        $this->assertTrue($pq->find('p')->hasClass('new'));
    }

    public function test_remove_class()
    {
        $pq = PhpQuery::newDocument('<p class="one two three">Text</p>');
        $pq->find('p')->removeClass('two');
        $this->assertTrue($pq->find('p')->hasClass('one'));
        $this->assertFalse($pq->find('p')->hasClass('two'));
        $this->assertTrue($pq->find('p')->hasClass('three'));
    }

    public function test_has_class()
    {
        $pq = PhpQuery::newDocument('<p class="active visible">Text</p>');
        $this->assertTrue($pq->find('p')->hasClass('active'));
        $this->assertTrue($pq->find('p')->hasClass('visible'));
        $this->assertFalse($pq->find('p')->hasClass('hidden'));
    }

    public function test_toggle_class()
    {
        $pq = PhpQuery::newDocument('<p class="on">Text</p>');
        $pq->find('p')->toggleClass('on');
        $this->assertFalse($pq->find('p')->hasClass('on'));
        $pq->find('p')->toggleClass('on');
        $this->assertTrue($pq->find('p')->hasClass('on'));
    }

    public function test_val_input()
    {
        $pq = PhpQuery::newDocument('<input type="text" value="hello">');
        $this->assertEquals('hello', $pq->find('input')->val());
    }

    public function test_val_set()
    {
        $pq = PhpQuery::newDocument('<input type="text" value="old">');
        $pq->find('input')->val('new');
        $this->assertEquals('new', $pq->find('input')->val());
    }

    public function test_val_select()
    {
        $pq = PhpQuery::newDocument('<select><option value="a">A</option><option value="b" selected="selected">B</option></select>');
        $this->assertEquals('b', $pq->find('select')->val());
    }

    public function test_val_set_select()
    {
        $pq = PhpQuery::newDocument('<select><option value="a">A</option><option value="b">B</option></select>');
        $pq->find('select')->val('b');
        $this->assertEquals('b', $pq->find('select')->val());
    }

    public function test_val_textarea()
    {
        $pq = PhpQuery::newDocument('<textarea>Content</textarea>');
        $this->assertEquals('Content', $pq->find('textarea')->val());
    }

    public function test_val_checkbox_array()
    {
        $pq = PhpQuery::newDocument('<div><input type="checkbox" name="c1" value="a"><input type="checkbox" name="c2" value="b"></div>');
        $pq->find('input')->val(['a']);
        $this->assertEquals('checked', $pq->find('[value=a]')->attr('checked'));
    }

    public function test_data()
    {
        $pq = PhpQuery::newDocument('<div><p>Text</p></div>');
        $p = $pq->find('p');
        $p->data('key', 'value');
        $this->assertEquals('value', $p->data('key'));
    }

    public function test_data_multiple_keys()
    {
        $pq = PhpQuery::newDocument('<div><p>Text</p></div>');
        $p = $pq->find('p');
        $p->data('name', 'John');
        $p->data('age', 30);
        $this->assertEquals('John', $p->data('name'));
        $this->assertEquals(30, $p->data('age'));
    }

    public function test_serialize()
    {
        $pq = PhpQuery::newDocument('<form><input type="text" name="email" value="test@test.com"><input type="text" name="name" value="John"></form>');
        $serialized = $pq->find('form')->serialize();
        // The library serializes as array-formatted query string from serializeArray()
        $this->assertNotEmpty($serialized);
        $this->assertStringContainsString('email', $serialized);
        $this->assertStringContainsString('test%40test.com', $serialized);
    }

    public function test_serialize_array()
    {
        $pq = PhpQuery::newDocument('<form><input type="text" name="field" value="val"></form>');
        $arr = $pq->find('form')->serializeArray();
        $this->assertIsArray($arr);
        $this->assertEquals('field', $arr[0]['name']);
        $this->assertEquals('val', $arr[0]['value']);
    }
}
