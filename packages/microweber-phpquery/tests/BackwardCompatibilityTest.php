<?php

namespace MicroweberPackages\PhpQuery\Tests;

/**
 * Tests that the old global class aliases still work
 */
class BackwardCompatibilityTest extends TestCase
{
    public function test_phpquery_alias_exists()
    {
        $this->assertTrue(class_exists('phpQuery'));
    }

    public function test_phpquery_object_alias_exists()
    {
        $this->assertTrue(class_exists('phpQueryObject'));
    }

    public function test_dom_event_alias_exists()
    {
        $this->assertTrue(class_exists('DOMEvent'));
    }

    public function test_phpquery_alias_new_document()
    {
        $pq = \phpQuery::newDocument('<div><p>Backward Compatible</p></div>');
        $this->assertInstanceOf(\phpQueryObject::class, $pq);
        $this->assertEquals('Backward Compatible', $pq->find('p')->text());
    }

    public function test_phpquery_alias_find()
    {
        $pq = \phpQuery::newDocument('<ul><li>A</li><li>B</li></ul>');
        $this->assertEquals(2, $pq->find('li')->length());
    }

    public function test_phpquery_alias_unload()
    {
        \phpQuery::newDocument('<div>Test</div>');
        \phpQuery::unloadDocuments();
        $this->assertEmpty(\phpQuery::$documents);
    }

    public function test_phpquery_alias_dom_manipulation()
    {
        $pq = \phpQuery::newDocument('<div></div>');
        $pq->find('div')->append('<p>Added</p>');
        $this->assertEquals('Added', $pq->find('p')->text());
    }

    public function test_callback_alias_exists()
    {
        $this->assertTrue(class_exists('Callback'));
    }

    public function test_pq_function_exists()
    {
        $this->assertTrue(function_exists('pq'));
    }

    public function test_pq_function()
    {
        \phpQuery::newDocument('<div><p>PQ Function</p></div>');
        $result = pq('p');
        $this->assertEquals('PQ Function', $result->text());
    }
}