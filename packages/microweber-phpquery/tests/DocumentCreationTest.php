<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\PhpQuery;
use MicroweberPackages\PhpQuery\PhpQueryObject;

class DocumentCreationTest extends TestCase
{
    public function test_new_document_from_html_fragment()
    {
        $pq = PhpQuery::newDocument('<div><p>Hello</p></div>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
        $this->assertEquals('Hello', $pq->find('p')->text());
    }

    public function test_new_document_from_full_html()
    {
        $html = '<!DOCTYPE html><html><head><title>Test</title></head><body><p>Content</p></body></html>';
        $pq = PhpQuery::newDocument($html);
        $this->assertStringContainsString('Content', $pq->find('p')->text());
    }

    public function test_new_document_html_explicit()
    {
        $pq = PhpQuery::newDocumentHTML('<div>Test</div>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_new_document_html_with_charset()
    {
        $pq = PhpQuery::newDocumentHTML('<div>Ünïcödé</div>', 'UTF-8');
        $this->assertStringContainsString('Ünïcödé', $pq->find('div')->text());
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

    public function test_new_document_xml()
    {
        $xml = '<?xml version="1.0"?><root><item>Test</item></root>';
        $pq = PhpQuery::newDocumentXML($xml);
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_multiple_documents_are_independent()
    {
        $pq1 = PhpQuery::newDocument('<div id="doc1">Doc1</div>');
        $pq2 = PhpQuery::newDocument('<div id="doc2">Doc2</div>');

        $this->assertNotEquals($pq1->getDocumentID(), $pq2->getDocumentID());
        $this->assertEquals('Doc1', $pq1->find('#doc1')->text());
        $this->assertEquals('Doc2', $pq2->find('#doc2')->text());
    }

    public function test_select_document()
    {
        $pq1 = PhpQuery::newDocument('<div id="first">First</div>');
        $id1 = $pq1->getDocumentID();
        PhpQuery::newDocument('<div id="second">Second</div>');

        PhpQuery::selectDocument($id1);
        $this->assertEquals($id1, PhpQuery::$defaultDocumentID);
    }

    public function test_unload_single_document()
    {
        $pq1 = PhpQuery::newDocument('<div>1</div>');
        $pq2 = PhpQuery::newDocument('<div>2</div>');
        $id1 = $pq1->getDocumentID();

        PhpQuery::unloadDocuments($id1);
        $this->assertArrayNotHasKey($id1, PhpQuery::$documents);
        $this->assertNotEmpty(PhpQuery::$documents);
    }

    public function test_unload_all_documents()
    {
        PhpQuery::newDocument('<div>1</div>');
        PhpQuery::newDocument('<div>2</div>');

        PhpQuery::unloadDocuments();
        $this->assertEmpty(PhpQuery::$documents);
    }

    public function test_get_document_id()
    {
        $pq = PhpQuery::newDocument('<div>Test</div>');
        $id = $pq->getDocumentID();
        $this->assertNotNull($id);
        $this->assertIsString($id);
    }

    public function test_get_document()
    {
        $pq = PhpQuery::newDocument('<div>Test</div>');
        $doc = PhpQuery::getDocument($pq->getDocumentID());
        $this->assertInstanceOf(PhpQueryObject::class, $doc);
    }

    public function test_document_is_html()
    {
        $pq = PhpQuery::newDocumentHTML('<div>HTML</div>');
        $this->assertTrue($pq->isHTML());
    }

    public function test_document_fragment_detection()
    {
        $pq = PhpQuery::newDocument('<div>Fragment</div>');
        $this->assertTrue($pq->documentFragment);
    }

    public function test_full_document_not_fragment()
    {
        $pq = PhpQuery::newDocument('<!DOCTYPE html><html><body>Full</body></html>');
        $this->assertFalse($pq->documentFragment);
    }

    public function test_pq_function_shortcut()
    {
        PhpQuery::newDocument('<div><p>Hello</p></div>');
        $result = pq('p');
        $this->assertEquals('Hello', $result->text());
    }

    public function test_to_string()
    {
        $pq = PhpQuery::newDocument('<div><p>Hello</p></div>');
        $str = (string) $pq->find('p');
        $this->assertStringContainsString('Hello', $str);
    }
}
