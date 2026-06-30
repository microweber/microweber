<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\Dom\DOMDocumentWrapper;
use MicroweberPackages\PhpQuery\PhpQuery;

class DOMDocumentWrapperTest extends TestCase
{
    public function test_load_valid_html()
    {
        $wrapper = new DOMDocumentWrapper('<html><body><p>Hello</p></body></html>');
        $this->assertInstanceOf(\DOMDocument::class, $wrapper->document);
        $this->assertTrue($wrapper->isHTML);
        $this->assertFalse($wrapper->isXML);
        $this->assertNotNull($wrapper->root);
    }

    public function test_load_html_fragment()
    {
        $wrapper = new DOMDocumentWrapper('<p>Hello World</p>');
        $this->assertTrue($wrapper->isDocumentFragment);
        $this->assertTrue($wrapper->isHTML);
    }

    public function test_load_full_html_document()
    {
        $wrapper = new DOMDocumentWrapper('<!DOCTYPE html><html><head><title>Test</title></head><body><p>Hello</p></body></html>');
        $this->assertFalse($wrapper->isDocumentFragment);
        $this->assertTrue($wrapper->isHTML);
    }

    public function test_load_invalid_html()
    {
        // Even invalid HTML should load (DOMDocument is lenient)
        $wrapper = new DOMDocumentWrapper('<p>Unclosed paragraph<div>Mixed</p></div>');
        $this->assertInstanceOf(\DOMDocument::class, $wrapper->document);
        $this->assertTrue($wrapper->isHTML);
    }

    public function test_load_empty_html()
    {
        $wrapper = new DOMDocumentWrapper('');
        $this->assertInstanceOf(\DOMDocument::class, $wrapper->document);
    }

    public function test_load_xml()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><root><item>Test</item></root>';
        $wrapper = new DOMDocumentWrapper($xml);
        $this->assertTrue($wrapper->isXML);
        $this->assertFalse($wrapper->isHTML);
    }

    public function test_load_with_content_type()
    {
        $wrapper = new DOMDocumentWrapper('<div>Test</div>', 'text/html');
        $this->assertTrue($wrapper->isHTML);
        $this->assertEquals('text/html', $wrapper->contentType);
    }

    public function test_markup_output()
    {
        $html = '<p>Hello World</p>';
        $wrapper = new DOMDocumentWrapper($html);
        $markup = $wrapper->markup();
        $this->assertStringContainsString('Hello World', $markup);
        $this->assertStringContainsString('<p>', $markup);
    }

    public function test_import_markup()
    {
        $wrapper = new DOMDocumentWrapper('<div>Parent</div>');
        $nodes = $wrapper->import('<span>Child</span>');
        $this->assertIsArray($nodes);
        $this->assertGreaterThan(0, count($nodes));
    }

    public function test_is_document_fragment_html()
    {
        $this->assertTrue(DOMDocumentWrapper::isDocumentFragmentHTML('<div>Hello</div>'));
        $this->assertFalse(DOMDocumentWrapper::isDocumentFragmentHTML('<html><body>Hello</body></html>'));
        $this->assertFalse(DOMDocumentWrapper::isDocumentFragmentHTML('<!DOCTYPE html><html></html>'));
    }

    public function test_is_document_fragment_xml()
    {
        $this->assertTrue(DOMDocumentWrapper::isDocumentFragmentXML('<root>Hello</root>'));
        $this->assertFalse(DOMDocumentWrapper::isDocumentFragmentXML('<?xml version="1.0"?><root></root>'));
    }

    public function test_expand_empty_tag()
    {
        $xml = '<div><script src="test.js" /></div>';
        $result = DOMDocumentWrapper::expandEmptyTag('script', $xml);
        $this->assertStringContainsString('></script>', $result);
    }

    public function test_load_html_with_special_characters()
    {
        $html = '<p>Text with &amp; ampersand and &lt;tags&gt;</p>';
        $wrapper = new DOMDocumentWrapper($html);
        $this->assertInstanceOf(\DOMDocument::class, $wrapper->document);
    }

    public function test_load_html_with_unicode()
    {
        $html = '<p>Héllo Wörld — Ünïcödé</p>';
        $wrapper = new DOMDocumentWrapper($html);
        $markup = $wrapper->markup();
        $this->assertStringContainsString('Héllo', $markup);
    }

    public function test_load_deeply_nested_html()
    {
        $html = str_repeat('<div>', 20) . 'Deep' . str_repeat('</div>', 20);
        $wrapper = new DOMDocumentWrapper($html);
        $this->assertInstanceOf(\DOMDocument::class, $wrapper->document);
        $markup = $wrapper->markup();
        $this->assertStringContainsString('Deep', $markup);
    }

    public function test_load_html_with_malformed_tags()
    {
        $html = '<div><p>Paragraph without close<span>Span</span>';
        $wrapper = new DOMDocumentWrapper($html);
        $this->assertInstanceOf(\DOMDocument::class, $wrapper->document);
    }
}