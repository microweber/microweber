<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\PhpQuery;
use MicroweberPackages\PhpQuery\PhpQueryObject;

/**
 * Tests for handling invalid/malformed HTML
 */
class PhpQueryInvalidHtmlTest extends TestCase
{
    public function test_unclosed_tags()
    {
        $pq = PhpQuery::newDocument('<div><p>Paragraph without close<span>Span');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
        $this->assertGreaterThan(0, $pq->find('p')->length());
    }

    public function test_mismatched_tags()
    {
        $pq = PhpQuery::newDocument('<div><p>Text</div></p>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
        $this->assertStringContainsString('Text', $pq->find('p')->text());
    }

    public function test_duplicate_ids()
    {
        $pq = PhpQuery::newDocument('<div><p id="dup">First</p><p id="dup">Second</p></div>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
        // Should find at least one
        $this->assertGreaterThanOrEqual(1, $pq->find('#dup')->length());
    }

    public function test_empty_tags()
    {
        $pq = PhpQuery::newDocument('<div></div><p></p><span></span>');
        $this->assertEquals(1, $pq->find('div')->length());
        $this->assertEquals(1, $pq->find('p')->length());
        $this->assertEquals(1, $pq->find('span')->length());
    }

    public function test_self_closing_tags()
    {
        $pq = PhpQuery::newDocument('<div><br/><hr/><img src="test.jpg"/></div>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_missing_html_head_body()
    {
        $pq = PhpQuery::newDocument('<p>Just a paragraph</p>');
        $this->assertEquals('Just a paragraph', $pq->find('p')->text());
    }

    public function test_html_entities()
    {
        $pq = PhpQuery::newDocument('<p>&amp; &lt; &gt; &quot;</p>');
        $text = $pq->find('p')->text();
        $this->assertStringContainsString('&', $text);
        $this->assertStringContainsString('<', $text);
        $this->assertStringContainsString('>', $text);
    }

    public function test_nested_quotes_in_attributes()
    {
        $pq = PhpQuery::newDocument('<div data-info="He said &quot;hello&quot;">Text</div>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_whitespace_only()
    {
        $pq = PhpQuery::newDocument('   ');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_only_text_no_tags()
    {
        $pq = PhpQuery::newDocument('Just plain text with no tags');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_deeply_nested_invalid_structure()
    {
        $html = '<table><tr><td><div><p><span>';
        $pq = PhpQuery::newDocument($html);
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_special_characters_in_content()
    {
        $pq = PhpQuery::newDocument('<p>Text with < and > symbols & "quotes"</p>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_script_tag_content()
    {
        $pq = PhpQuery::newDocument('<div><script>var x = "<p>not html</p>";</script><p>Real</p></div>');
        $this->assertEquals('Real', $pq->find('p')->text());
    }

    public function test_style_tag_content()
    {
        $pq = PhpQuery::newDocument('<div><style>.class { color: red; }</style><p>Content</p></div>');
        $this->assertEquals('Content', $pq->find('p')->text());
    }

    public function test_comments_in_html()
    {
        $pq = PhpQuery::newDocument('<div><!-- This is a comment --><p>After comment</p></div>');
        $this->assertEquals('After comment', $pq->find('p')->text());
    }

    public function test_multiple_root_elements()
    {
        $pq = PhpQuery::newDocument('<p>First</p><p>Second</p><p>Third</p>');
        $this->assertEquals(3, $pq->find('p')->length());
    }

    public function test_malformed_attributes()
    {
        $pq = PhpQuery::newDocument('<div class=noquotes data-val=test><p>Text</p></div>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_large_html()
    {
        $html = '<ul>';
        for ($i = 0; $i < 1000; $i++) {
            $html .= "<li>Item $i</li>";
        }
        $html .= '</ul>';

        $pq = PhpQuery::newDocument($html);
        $this->assertEquals(1000, $pq->find('li')->length());
    }

    public function test_html_with_cdata()
    {
        $html = '<div><![CDATA[Some <raw> content]]></div>';
        $pq = PhpQuery::newDocument($html);
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_mixed_case_tags()
    {
        $pq = PhpQuery::newDocument('<DIV><P class="test">Hello</P></DIV>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
        $this->assertGreaterThan(0, $pq->find('p')->length());
    }

    public function test_boolean_attributes()
    {
        $pq = PhpQuery::newDocument('<form><input type="checkbox" checked disabled><input type="text" required></form>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_inline_styles()
    {
        $pq = PhpQuery::newDocument('<p style="color: red; font-size: 12px;">Styled</p>');
        $this->assertEquals('color: red; font-size: 12px;', $pq->find('p')->attr('style'));
    }
}