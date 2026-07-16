<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\PhpQuery;

class ManipulationTest extends TestCase
{
    public function test_append()
    {
        $pq = PhpQuery::newDocument('<div></div>');
        $pq->find('div')->append('<p>Appended</p>');
        $this->assertEquals(1, $pq->find('p')->length());
        $this->assertEquals('Appended', $pq->find('p')->text());
    }

    public function test_append_multiple()
    {
        $pq = PhpQuery::newDocument('<div></div>');
        $pq->find('div')->append('<p>One</p>');
        $pq->find('div')->append('<p>Two</p>');
        $this->assertEquals(2, $pq->find('p')->length());
    }

    public function test_prepend()
    {
        $pq = PhpQuery::newDocument('<div><span>Last</span></div>');
        $pq->find('div')->prepend('<p>First</p>');
        $html = $pq->find('div')->html();
        $pos_p = strpos($html, '<p>');
        $pos_span = strpos($html, '<span>');
        $this->assertLessThan($pos_span, $pos_p);
    }

    public function test_after()
    {
        $pq = PhpQuery::newDocument('<div><p>Before</p></div>');
        $pq->find('p')->after('<span>After</span>');
        $html = $pq->find('div')->html();
        $this->assertStringContainsString('After', $html);
    }

    public function test_before()
    {
        $pq = PhpQuery::newDocument('<div><p>After</p></div>');
        $pq->find('p')->before('<span>Before</span>');
        $html = $pq->find('div')->html();
        $this->assertStringContainsString('Before', $html);
    }

    public function test_remove()
    {
        $pq = PhpQuery::newDocument('<div><p>Remove</p><span>Keep</span></div>');
        $pq->find('p')->remove();
        $this->assertEquals(0, $pq->find('p')->length());
        $this->assertEquals(1, $pq->find('span')->length());
    }

    public function test_empty_element()
    {
        $pq = PhpQuery::newDocument('<div><p>Content</p></div>');
        $pq->find('div')->empty();
        $this->assertEquals('', trim($pq->find('div')->html()));
    }

    public function test_replace_with()
    {
        $pq = PhpQuery::newDocument('<div><p>Old</p></div>');
        $pq->find('p')->replaceWith('<span>New</span>');
        $this->assertEquals(0, $pq->find('p')->length());
        $this->assertEquals('New', $pq->find('span')->text());
    }

    public function test_wrap()
    {
        $pq = PhpQuery::newDocument('<div><p>Text</p></div>');
        $pq->find('p')->wrap('<section></section>');
        $this->assertEquals(1, $pq->find('section p')->length());
    }

    public function test_wrap_inner()
    {
        $pq = PhpQuery::newDocument('<div><p>Text</p><span>More</span></div>');
        $pq->find('div')->wrapInner('<section></section>');
        $this->assertEquals(1, $pq->find('section p')->length());
    }

    public function test_contents_unwrap()
    {
        $pq = PhpQuery::newDocument('<div><p><span>Text</span></p></div>');
        $pq->find('p')->contentsUnwrap();
        $this->assertEquals(0, $pq->find('p')->length());
        $this->assertEquals(1, $pq->find('div span')->length());
    }

    public function test_clone()
    {
        $pq = PhpQuery::newDocument('<div><p class="original">Text</p></div>');
        $cloned = $pq->find('p')->clone();
        $this->assertEquals(1, $cloned->length());
        $this->assertTrue($cloned->hasClass('original'));
    }

    public function test_html_get()
    {
        $pq = PhpQuery::newDocument('<div><p>Inner <strong>HTML</strong></p></div>');
        $html = $pq->find('div')->html();
        $this->assertStringContainsString('<p>', $html);
        $this->assertStringContainsString('<strong>', $html);
    }

    public function test_html_set()
    {
        $pq = PhpQuery::newDocument('<div><p>Old</p></div>');
        $pq->find('div')->html('<span>New Content</span>');
        $this->assertEquals(0, $pq->find('p')->length());
        $this->assertEquals(1, $pq->find('span')->length());
    }

    public function test_text_get()
    {
        $pq = PhpQuery::newDocument('<div><p>Hello <strong>World</strong></p></div>');
        $this->assertEquals('Hello World', $pq->find('p')->text());
    }

    public function test_text_set()
    {
        $pq = PhpQuery::newDocument('<div><p>Old</p></div>');
        $pq->find('p')->text('New & <safe>');
        $text = $pq->find('p')->text();
        $this->assertStringContainsString('New', $text);
    }

    public function test_html_outer()
    {
        $pq = PhpQuery::newDocument('<div><p class="test">Hello</p></div>');
        $outer = $pq->find('p')->htmlOuter();
        $this->assertStringContainsString('<p class="test">', $outer);
        $this->assertStringContainsString('Hello', $outer);
    }

    public function test_insert_after()
    {
        $pq = PhpQuery::newDocument('<div><p>First</p><p>Third</p></div>');
        PhpQuery::pq('<span>Second</span>', $pq->getDocumentID())->insertAfter($pq->find('p:first'));
        $html = $pq->find('div')->html();
        $this->assertStringContainsString('Second', $html);
    }

    public function test_append_to()
    {
        $pq = PhpQuery::newDocument('<div><ul></ul></div>');
        PhpQuery::pq('<li>Item</li>', $pq->getDocumentID())->appendTo($pq->find('ul'));
        $this->assertEquals(1, $pq->find('li')->length());
    }

    public function test_switch_with()
    {
        $pq = PhpQuery::newDocument('<div><section><p>Content</p></section></div>');
        $pq->find('section')->switchWith('<article></article>');
        $this->assertEquals(1, $pq->find('article p')->length());
    }
}
