<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\PhpQuery;

class SelectorTest extends TestCase
{
    private $html = '<div id="container" class="main">
        <h1>Title</h1>
        <ul class="list">
            <li class="item active" data-id="1">First</li>
            <li class="item" data-id="2">Second</li>
            <li class="item last" data-id="3">Third</li>
        </ul>
        <p class="text">Paragraph <strong>bold</strong> text</p>
        <div class="nested">
            <span class="deep">Deep</span>
        </div>
        <input type="text" name="email" value="test@example.com">
        <input type="password" name="pass">
        <input type="checkbox" name="agree" checked>
        <a href="http://example.com" title="Example">Link</a>
    </div>';

    private function doc()
    {
        return PhpQuery::newDocument($this->html);
    }

    public function test_find_by_tag()
    {
        $this->assertEquals(3, $this->doc()->find('li')->length());
    }

    public function test_find_by_id()
    {
        $found = $this->doc()->find('#container');
        $this->assertEquals(1, $found->length());
    }

    public function test_find_by_class()
    {
        $found = $this->doc()->find('.item');
        $this->assertEquals(3, $found->length());
    }

    public function test_find_by_multiple_classes()
    {
        $found = $this->doc()->find('.item.active');
        $this->assertEquals(1, $found->length());
        $this->assertEquals('First', $found->text());
    }

    public function test_find_by_attribute_equals()
    {
        $found = $this->doc()->find('[type=text]');
        $this->assertEquals(1, $found->length());
    }

    public function test_find_by_attribute_exists()
    {
        $found = $this->doc()->find('[checked]');
        $this->assertEquals(1, $found->length());
    }

    public function test_find_descendant_combinator()
    {
        $found = $this->doc()->find('.nested span');
        $this->assertEquals(1, $found->length());
        $this->assertEquals('Deep', $found->text());
    }

    public function test_find_child_combinator()
    {
        $found = $this->doc()->find('#container > h1');
        $this->assertEquals(1, $found->length());
    }

    public function test_find_comma_separated()
    {
        $found = $this->doc()->find('h1, p');
        $this->assertEquals(2, $found->length());
    }

    public function test_find_with_context()
    {
        $pq = $this->doc();
        $ul = $pq->find('ul')->get(0);
        $found = $pq->find('li', $ul);
        $this->assertEquals(3, $found->length());
    }

    public function test_find_wildcard()
    {
        $found = $this->doc()->find('ul > *');
        $this->assertEquals(3, $found->length());
    }

    public function test_attribute_starts_with()
    {
        $pq = PhpQuery::newDocument('<div><a href="http://one.com">1</a><a href="ftp://two.com">2</a></div>');
        $found = $pq->find('[href^=http]');
        $this->assertEquals(1, $found->length());
    }

    public function test_attribute_ends_with()
    {
        $pq = PhpQuery::newDocument('<div><a href="file.pdf">PDF</a><a href="file.doc">DOC</a></div>');
        $found = $pq->find('[href$=pdf]');
        $this->assertEquals(1, $found->length());
    }

    public function test_attribute_contains()
    {
        $pq = PhpQuery::newDocument('<div><a href="http://example.com/page">Link</a></div>');
        $found = $pq->find('[href*=example]');
        $this->assertEquals(1, $found->length());
    }

    public function test_pseudo_first()
    {
        $found = $this->doc()->find('li:first');
        $this->assertEquals(1, $found->length());
        $this->assertEquals('First', $found->text());
    }

    public function test_pseudo_last()
    {
        $found = $this->doc()->find('li:last');
        $this->assertEquals(1, $found->length());
        $this->assertEquals('Third', $found->text());
    }

    public function test_pseudo_eq()
    {
        $found = $this->doc()->find('li:eq(1)');
        $this->assertEquals(1, $found->length());
        $this->assertEquals('Second', $found->text());
    }

    public function test_pseudo_gt()
    {
        $found = $this->doc()->find('li:gt(0)');
        $this->assertEquals(2, $found->length());
    }

    public function test_pseudo_lt()
    {
        $found = $this->doc()->find('li:lt(2)');
        // phpQuery :lt uses 1-based comparison, so :lt(2) returns items at index < 3
        $this->assertEquals(3, $found->length());
    }

    public function test_pseudo_even()
    {
        $found = $this->doc()->find('li:even');
        $this->assertEquals(2, $found->length()); // 0-indexed: items 0 and 2
    }

    public function test_pseudo_odd()
    {
        $found = $this->doc()->find('li:odd');
        $this->assertEquals(1, $found->length()); // 0-indexed: item 1
    }

    public function test_pseudo_contains()
    {
        $found = $this->doc()->find('li:contains("Second")');
        $this->assertEquals(1, $found->length());
    }

    public function test_pseudo_not()
    {
        $found = $this->doc()->find('li:not(.active)');
        $this->assertEquals(2, $found->length());
    }

    public function test_pseudo_has()
    {
        $found = $this->doc()->find('div:has(span)');
        $this->assertGreaterThanOrEqual(1, $found->length());
    }

    public function test_pseudo_empty()
    {
        $pq = PhpQuery::newDocument('<div><p></p><p>Not empty</p></div>');
        $found = $pq->find('p:empty');
        $this->assertEquals(1, $found->length());
    }

    public function test_pseudo_parent()
    {
        $pq = PhpQuery::newDocument('<div><p></p><p>Has content</p></div>');
        $found = $pq->find('p:parent');
        $this->assertEquals(1, $found->length());
    }

    public function test_pseudo_header()
    {
        $pq = PhpQuery::newDocument('<div><h1>H1</h1><h2>H2</h2><p>P</p></div>');
        $found = $pq->find(':header');
        $this->assertEquals(2, $found->length());
    }

    public function test_pseudo_checked()
    {
        $found = $this->doc()->find(':checked');
        $this->assertEquals(1, $found->length());
    }

    public function test_pseudo_first_child()
    {
        $found = $this->doc()->find('li:first-child');
        $this->assertEquals(1, $found->length());
        $this->assertEquals('First', $found->text());
    }

    public function test_pseudo_last_child()
    {
        $found = $this->doc()->find('li:last-child');
        $this->assertEquals(1, $found->length());
        $this->assertEquals('Third', $found->text());
    }

    public function test_pseudo_only_child()
    {
        $pq = PhpQuery::newDocument('<div><ul><li>Only</li></ul><ul><li>A</li><li>B</li></ul></div>');
        $found = $pq->find('li:only-child');
        $this->assertEquals(1, $found->length());
        $this->assertEquals('Only', $found->text());
    }

    public function test_pseudo_nth_child()
    {
        $found = $this->doc()->find('li:nth-child(2)');
        $this->assertEquals(1, $found->length());
        $this->assertEquals('Second', $found->text());
    }

    public function test_adjacent_sibling()
    {
        $pq = PhpQuery::newDocument('<div><h1>H</h1><p>After H</p><span>After P</span></div>');
        $found = $pq->find('h1 + p');
        $this->assertEquals(1, $found->length());
        $this->assertEquals('After H', $found->text());
    }

    public function test_general_sibling()
    {
        $pq = PhpQuery::newDocument('<div><h1>H</h1><p>P1</p><p>P2</p></div>');
        $found = $pq->find('h1 ~ p');
        $this->assertEquals(2, $found->length());
    }

    public function test_is_markup()
    {
        $this->assertTrue(PhpQuery::isMarkup('<div>test</div>'));
        $this->assertTrue(PhpQuery::isMarkup('<p>'));
        $this->assertFalse(PhpQuery::isMarkup('div.class'));
        $this->assertFalse(PhpQuery::isMarkup('#id'));
        $this->assertFalse(PhpQuery::isMarkup('.class'));
    }
}
