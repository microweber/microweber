<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\PhpQuery;

class MemoryLeakTest extends TestCase
{
    public function test_unload_documents_frees_memory()
    {
        $before = count(PhpQuery::$documents);

        for ($i = 0; $i < 100; $i++) {
            PhpQuery::newDocument('<div>Test ' . $i . '</div>');
        }

        $during = count(PhpQuery::$documents);
        $this->assertGreaterThan($before, $during);

        PhpQuery::unloadDocuments();
        $this->assertEmpty(PhpQuery::$documents);
    }

    public function test_no_document_leak_after_operations()
    {
        PhpQuery::unloadDocuments();

        $pq = PhpQuery::newDocument('<div><p>Test</p></div>');
        $pq->find('p')->addClass('test');
        $pq->find('p')->attr('data-x', 'y');
        $pq->find('div')->append('<span>New</span>');

        $docCount = count(PhpQuery::$documents);
        // Should have created only 1 main document (fragments are temporary)
        $this->assertLessThanOrEqual(2, $docCount);

        PhpQuery::unloadDocuments();
        $this->assertEmpty(PhpQuery::$documents);
    }

    public function test_repeated_parse_and_unload()
    {
        for ($i = 0; $i < 50; $i++) {
            $pq = PhpQuery::newDocument('<div><ul><li>Item</li></ul></div>');
            $pq->find('li')->text();
            $pq->find('div')->html();
            PhpQuery::unloadDocuments($pq->getDocumentID());
        }

        // After unloading each time, should be minimal documents left
        $this->assertLessThanOrEqual(1, count(PhpQuery::$documents));
    }
}
