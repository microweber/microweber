<?php

namespace MicroweberPackages\Repository\Tests;

use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Repository\Repositories\CachingModelRepository;
use MicroweberPackages\Repository\Tests\Stubs\TestCachingRepository;
use MicroweberPackages\Repository\Tests\Stubs\TestModel;

class CachingModelRepositoryTest extends TestCase
{
    protected TestCachingRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('test_items', function ($table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->integer('position')->default(0);
        });

        // Reset static state between tests
        CachingModelRepository::enableCache();

        $this->repository = new TestCachingRepository();
    }

    public function test_get_model()
    {
        $model = $this->repository->getModel();
        $this->assertInstanceOf(TestModel::class, $model);
    }

    public function test_get_cache_tags()
    {
        $tags = $this->repository->getCacheTags();
        $this->assertIsArray($tags);
        $this->assertContains('repositories', $tags);
        $this->assertContains('test_items', $tags);
    }

    public function test_clear_cache()
    {
        $this->repository->clearCache();

        // After clearing cache, the static disabled flag should be true
        $reflection = new \ReflectionClass(CachingModelRepository::class);
        $prop = $reflection->getProperty('cacheDisabled');
        $prop->setAccessible(true);
        $this->assertTrue($prop->getValue());

        // Re-enable for next tests
        CachingModelRepository::enableCache();
    }

    public function test_get_all_items()
    {
        TestModel::create(['title' => 'Item A']);
        TestModel::create(['title' => 'Item B']);

        $items = $this->repository->getAllItems();
        $this->assertCount(2, $items);
    }
}