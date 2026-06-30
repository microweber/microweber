<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\PhpQueryManager;
use MicroweberPackages\PhpQuery\PhpQueryObject;

class PhpQueryManagerTest extends TestCase
{
    public function test_manager_is_bound()
    {
        $manager = $this->app->make('phpquery');
        $this->assertInstanceOf(PhpQueryManager::class, $manager);
    }

    public function test_manager_new_document()
    {
        $manager = $this->app->make('phpquery');
        $pq = $manager->newDocument('<div><p>Hello</p></div>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
        $this->assertEquals('Hello', $pq->find('p')->text());
    }

    public function test_manager_new_document_html()
    {
        $manager = $this->app->make('phpquery');
        $pq = $manager->newDocumentHTML('<p>Test</p>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
    }

    public function test_manager_pq()
    {
        $manager = $this->app->make('phpquery');
        $manager->newDocument('<div><p>Hello</p></div>');
        $pq = $manager->pq('p');
        $this->assertEquals('Hello', $pq->text());
    }

    public function test_manager_unload_documents()
    {
        $manager = $this->app->make('phpquery');
        $manager->newDocument('<div>Test</div>');
        $manager->unloadDocuments();
        // Should not throw
        $this->assertTrue(true);
    }

    public function test_facade()
    {
        $pq = \MicroweberPackages\PhpQuery\Facades\PhpQueryFacade::newDocument('<div><p>Facade</p></div>');
        $this->assertInstanceOf(PhpQueryObject::class, $pq);
        $this->assertEquals('Facade', $pq->find('p')->text());
    }

    public function test_app_helper_access()
    {
        $manager = app('phpquery');
        $this->assertInstanceOf(PhpQueryManager::class, $manager);
    }
}