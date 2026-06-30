<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\PhpQuery;
use MicroweberPackages\PhpQuery\PhpQueryObject;

class PhpQueryTest extends TestCase
{
    // ===== Document Creation Tests =====

    public function test_new_document_from_html()
    {
        $pq = PhpQuery::newDocument('<div><p>Hello</p></div>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_new_document_from_full_html()
    {
        $pq = PhpQuery::newDocument('<!DOCTYPE html><html><head><title>Test</title></head><body><p>Content</p></body></html>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
        $this->assertStringContainsString('Content', $pq->find('p')->text());
    }

    public function test_new_document_html()
    {
        $pq = PhpQuery::newDocumentHTML('<div>Test</div>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_new_document_empty()
    {
        $pq = PhpQuery::newDocument('');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_new_document_null()
    {
        $pq = PhpQuery::newDocument(null);
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    // ===== Selector Tests =====

    public function test_find_by_tag()
    {
        $pq = PhpQuery::newDocument('<div><p>First</p><p>Second</p></div>');
        $this->assertEquals(2, $pq->find('p')->length());
    }

    public function test_find_by_class()
    {
        $pq = PhpQuery::newDocument('<div><p class="active">Active</p><p>Inactive</p></div>');
        $found = $pq->find('.active');
        $this->assertEquals(1, $found->length());
        $this->assertEquals('Active', $found->text());
    }

    public function test_find_by_id()
    {
        $pq = PhpQuery::newDocument('<div><p id="main">Main</p><p>Other</p></div>');
        $found = $pq->find('#main');
        $this->assertEquals(1, $found->length());
        $this->assertEquals('Main', $found->text());
    }

    public function test_find_by_attribute()
    {
        $pq = PhpQuery::newDocument('<div><input type="text" name="email"><input type="password" name="pass"></div>');
        $found = $pq->find('[type=text]');
        $this->assertEquals(1, $found->length());
    }

    public function test_find_nested()
    {
        $pq = PhpQuery::newDocument('<div class="outer"><div class="inner"><span>Found</span></div></div>');
        $found = $pq->find('.outer .inner span');
        $this->assertEquals(1, $found->length());
        $this->assertEquals('Found', $found->text());
    }

    public function test_find_child_combinator()
    {
        $pq = PhpQuery::newDocument('<div><p>Direct</p><span><p>Nested</p></span></div>');
        $found = $pq->find('div > p');
        $this->assertEquals(1, $found->length());
        $this->assertEquals('Direct', $found->text());
    }

    // ===== DOM Manipulation Tests =====

    public function test_append()
    {
        $pq = PhpQuery::newDocument('<div></div>');
        $pq->find('div')->append('<p>Appended</p>');
        $this->assertEquals(1, $pq->find('p')->length());
        $this->assertEquals('Appended', $pq->find('p')->text());
    }

    public function test_prepend()
    {
        $pq = PhpQuery::newDocument('<div><span>Existing</span></div>');
        $pq->find('div')->prepend('<p>Prepended</p>');
        $html = $pq->find('div')->html();
        $this->assertStringContainsString('Prepended', $html);
    }

    public function test_remove()
    {
        $pq = PhpQuery::newDocument('<div><p>Remove me</p><span>Keep me</span></div>');
        $pq->find('p')->remove();
        $this->assertEquals(0, $pq->find('p')->length());
        $this->assertEquals(1, $pq->find('span')->length());
    }

    public function test_html_get()
    {
        $pq = PhpQuery::newDocument('<div><p>Inner</p></div>');
        $html = $pq->find('div')->html();
        $this->assertStringContainsString('<p>Inner</p>', $html);
    }

    public function test_html_set()
    {
        $pq = PhpQuery::newDocument('<div><p>Old</p></div>');
        $pq->find('div')->html('<span>New</span>');
        $this->assertEquals(0, $pq->find('p')->length());
        $this->assertEquals(1, $pq->find('span')->length());
    }

    public function test_text_get()
    {
        $pq = PhpQuery::newDocument('<div><p>Hello <strong>World</strong></p></div>');
        $text = $pq->find('p')->text();
        $this->assertEquals('Hello World', $text);
    }

    public function test_text_set()
    {
        $pq = PhpQuery::newDocument('<div><p>Old</p></div>');
        $pq->find('p')->text('New Text');
        $this->assertEquals('New Text', $pq->find('p')->text());
    }

    // ===== Attribute Tests =====

    public function test_attr_get()
    {
        $pq = PhpQuery::newDocument('<div><a href="http://example.com">Link</a></div>');
        $this->assertEquals('http://example.com', $pq->find('a')->attr('href'));
    }

    public function test_attr_set()
    {
        $pq = PhpQuery::newDocument('<div><a href="#">Link</a></div>');
        $pq->find('a')->attr('href', 'http://new.com');
        $this->assertEquals('http://new.com', $pq->find('a')->attr('href'));
    }

    public function test_remove_attr()
    {
        $pq = PhpQuery::newDocument('<div><a href="#" title="Test">Link</a></div>');
        $pq->find('a')->removeAttr('title');
        $this->assertEmpty($pq->find('a')->attr('title'));
    }

    // ===== Class Manipulation Tests =====

    public function test_add_class()
    {
        $pq = PhpQuery::newDocument('<div><p>Text</p></div>');
        $pq->find('p')->addClass('highlight');
        $this->assertTrue($pq->find('p')->hasClass('highlight'));
    }

    public function test_remove_class()
    {
        $pq = PhpQuery::newDocument('<div><p class="active highlight">Text</p></div>');
        $pq->find('p')->removeClass('active');
        $this->assertFalse($pq->find('p')->hasClass('active'));
        $this->assertTrue($pq->find('p')->hasClass('highlight'));
    }

    public function test_has_class()
    {
        $pq = PhpQuery::newDocument('<div><p class="active">Text</p></div>');
        $this->assertTrue($pq->find('p')->hasClass('active'));
        $this->assertFalse($pq->find('p')->hasClass('inactive'));
    }

    public function test_toggle_class()
    {
        $pq = PhpQuery::newDocument('<div><p class="active">Text</p></div>');
        $pq->find('p')->toggleClass('active');
        $this->assertFalse($pq->find('p')->hasClass('active'));
        $pq->find('p')->toggleClass('active');
        $this->assertTrue($pq->find('p')->hasClass('active'));
    }

    // ===== Traversal Tests =====

    public function test_parent()
    {
        $pq = PhpQuery::newDocument('<div class="parent"><p>Child</p></div>');
        $parent = $pq->find('p')->parent();
        $this->assertTrue($parent->hasClass('parent'));
    }

    public function test_children()
    {
        $pq = PhpQuery::newDocument('<ul><li>One</li><li>Two</li><li>Three</li></ul>');
        $this->assertEquals(3, $pq->find('ul')->children()->length());
    }

    public function test_siblings()
    {
        $pq = PhpQuery::newDocument('<ul><li class="first">One</li><li class="active">Two</li><li>Three</li></ul>');
        $siblings = $pq->find('.active')->siblings();
        $this->assertEquals(2, $siblings->length());
    }

    public function test_eq()
    {
        $pq = PhpQuery::newDocument('<ul><li>One</li><li>Two</li><li>Three</li></ul>');
        $second = $pq->find('li')->eq(1);
        $this->assertEquals('Two', $second->text());
    }

    // ===== Filter Tests =====

    public function test_filter()
    {
        $pq = PhpQuery::newDocument('<div><p class="keep">Keep</p><p>Remove</p></div>');
        $filtered = $pq->find('p')->filter('.keep');
        $this->assertEquals(1, $filtered->length());
        $this->assertEquals('Keep', $filtered->text());
    }

    public function test_not()
    {
        $pq = PhpQuery::newDocument('<div><p class="skip">Skip</p><p>Keep</p></div>');
        $filtered = $pq->find('p')->not('.skip');
        $this->assertEquals(1, $filtered->length());
        $this->assertEquals('Keep', $filtered->text());
    }

    public function test_is()
    {
        $pq = PhpQuery::newDocument('<div><p class="active">Text</p></div>');
        $this->assertTrue($pq->find('p')->is('.active'));
        $this->assertFalse($pq->find('p')->is('.inactive'));
    }

    // ===== Chaining Tests =====

    public function test_chaining()
    {
        $pq = PhpQuery::newDocument('<div><ul><li>Item</li></ul></div>');
        $result = $pq->find('div')->find('ul')->find('li')->text();
        $this->assertEquals('Item', $result);
    }

    public function test_end()
    {
        $pq = PhpQuery::newDocument('<div><p>Text</p></div>');
        $result = $pq->find('div')->find('p')->end();
        $this->assertEquals('div', $result->get(0)->tagName);
    }

    // ===== Static Utility Tests =====

    public function test_is_markup()
    {
        $this->assertTrue(PhpQuery::isMarkup('<div>test</div>'));
        $this->assertFalse(PhpQuery::isMarkup('div.class'));
        $this->assertFalse(PhpQuery::isMarkup('#id'));
    }

    public function test_unload_documents()
    {
        PhpQuery::newDocument('<div>1</div>');
        PhpQuery::newDocument('<div>2</div>');
        $this->assertNotEmpty(PhpQuery::$documents);

        PhpQuery::unloadDocuments();
        $this->assertEmpty(PhpQuery::$documents);
    }

    public function test_to_json()
    {
        $data = ['key' => 'value'];
        $json = PhpQuery::toJSON($data);
        $this->assertEquals('{"key":"value"}', $json);
    }

    public function test_parse_json()
    {
        $result = PhpQuery::parseJSON('{"key":"value"}');
        $this->assertEquals(['key' => 'value'], $result);
    }

    // ===== Iterator Tests =====

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

    // ===== Value Tests =====

    public function test_val_input()
    {
        $pq = PhpQuery::newDocument('<form><input type="text" value="hello"></form>');
        $this->assertEquals('hello', $pq->find('input')->val());
    }

    public function test_val_set()
    {
        $pq = PhpQuery::newDocument('<form><input type="text" value="old"></form>');
        $pq->find('input')->val('new');
        $this->assertEquals('new', $pq->find('input')->val());
    }

    public function test_val_select()
    {
        $pq = PhpQuery::newDocument('<select><option value="a">A</option><option value="b" selected>B</option></select>');
        $this->assertEquals('b', $pq->find('select')->val());
    }

    public function test_val_textarea()
    {
        $pq = PhpQuery::newDocument('<textarea>Hello World</textarea>');
        $this->assertEquals('Hello World', $pq->find('textarea')->val());
    }

    // ===== Wrap Tests =====

    public function test_wrap_all()
    {
        $pq = PhpQuery::newDocument('<div><p>Text</p></div>');
        $pq->find('p')->wrapAll('<section></section>');
        $this->assertEquals(1, $pq->find('section p')->length());
    }

    // ===== Contents Tests =====

    public function test_contents()
    {
        $pq = PhpQuery::newDocument('<div>Text <span>and span</span></div>');
        $contents = $pq->find('div')->contents();
        $this->assertGreaterThan(0, $contents->length());
    }

    // ===== Replace Tests =====

    public function test_replace_with()
    {
        $pq = PhpQuery::newDocument('<div><p>Old</p></div>');
        $pq->find('p')->replaceWith('<span>New</span>');
        $this->assertEquals(0, $pq->find('p')->length());
        $this->assertEquals(1, $pq->find('span')->length());
        $this->assertEquals('New', $pq->find('span')->text());
    }

    // ===== Before/After Tests =====

    public function test_after()
    {
        $pq = PhpQuery::newDocument('<div><p>First</p></div>');
        $pq->find('p')->after('<span>After</span>');
        $html = $pq->find('div')->html();
        $this->assertStringContainsString('After', $html);
    }

    public function test_before()
    {
        $pq = PhpQuery::newDocument('<div><p>First</p></div>');
        $pq->find('p')->before('<span>Before</span>');
        $html = $pq->find('div')->html();
        $this->assertStringContainsString('Before', $html);
    }

    // ===== Data Tests =====

    public function test_data()
    {
        $pq = PhpQuery::newDocument('<div><p>Text</p></div>');
        $p = $pq->find('p');
        $p->data('key', 'value');
        $this->assertEquals('value', $p->data('key'));
    }

    // ===== Size/Length Tests =====

    public function test_size()
    {
        $pq = PhpQuery::newDocument('<ul><li>A</li><li>B</li><li>C</li></ul>');
        $this->assertEquals(3, $pq->find('li')->size());
    }

    public function test_length_property()
    {
        $pq = PhpQuery::newDocument('<ul><li>A</li><li>B</li></ul>');
        $this->assertEquals(2, $pq->find('li')->length);
    }

    // ===== Multiple Documents Test =====

    public function test_multiple_documents()
    {
        $pq1 = PhpQuery::newDocument('<div id="doc1">Doc1</div>');
        $pq2 = PhpQuery::newDocument('<div id="doc2">Doc2</div>');

        $this->assertNotEquals($pq1->getDocumentID(), $pq2->getDocumentID());
        $this->assertEquals('Doc1', $pq1->find('#doc1')->text());
        $this->assertEquals('Doc2', $pq2->find('#doc2')->text());
    }

    // ===== Stack Tests =====

    public function test_stack()
    {
        $pq = PhpQuery::newDocument('<ul><li>A</li><li>B</li></ul>');
        $stack = $pq->find('li')->stack();
        $this->assertIsArray($stack);
        $this->assertCount(2, $stack);
    }

    // ===== Slice Tests =====

    public function test_slice()
    {
        $pq = PhpQuery::newDocument('<ul><li>A</li><li>B</li><li>C</li><li>D</li></ul>');
        $sliced = $pq->find('li')->slice(1, 3);
        $this->assertEquals(2, $sliced->length());
    }

    // ===== Add Tests =====

    public function test_add()
    {
        $pq = PhpQuery::newDocument('<div><p>P</p><span>S</span></div>');
        $combined = $pq->find('p')->add('span');
        $this->assertEquals(2, $combined->length());
    }

    // ===== Index Tests =====

    public function test_index()
    {
        $pq = PhpQuery::newDocument('<ul><li>A</li><li class="target">B</li><li>C</li></ul>');
        $target = $pq->find('.target')->get(0);
        $idx = $pq->find('li')->index($target);
        $this->assertEquals(1, $idx);
    }
}