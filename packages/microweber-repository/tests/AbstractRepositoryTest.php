<?php

namespace MicroweberPackages\Repository\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Repository\Tests\Stubs\TestModel;
use MicroweberPackages\Repository\Tests\Stubs\TestRepository;

class AbstractRepositoryTest extends TestCase
{
    protected TestRepository $repository;

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

        $this->repository = new TestRepository();
    }

    public function test_create()
    {
        $entity = $this->repository->create([
            'title' => 'Test Item',
            'description' => 'A test description',
            'status' => 'active',
        ]);

        $this->assertNotFalse($entity);
        $this->assertEquals('Test Item', $entity->title);
        $this->assertDatabaseHas('test_items', ['title' => 'Test Item']);
    }

    public function test_find()
    {
        $created = $this->repository->create(['title' => 'Findable']);

        $found = $this->repository->find($created->id);
        $this->assertNotNull($found);
        $this->assertEquals('Findable', $found->title);
    }

    public function test_find_by()
    {
        $this->repository->create(['title' => 'UniqueTitle', 'status' => 'draft']);

        $found = $this->repository->findBy('title', 'UniqueTitle');
        $this->assertNotNull($found);
        $this->assertEquals('draft', $found->status);
    }

    public function test_find_all_by()
    {
        $this->repository->create(['title' => 'A', 'status' => 'active']);
        $this->repository->create(['title' => 'B', 'status' => 'active']);
        $this->repository->create(['title' => 'C', 'status' => 'draft']);

        $results = $this->repository->findAllBy('status', 'active');
        $this->assertCount(2, $results);
    }

    public function test_update()
    {
        $entity = $this->repository->create(['title' => 'Original']);

        $result = $this->repository->update($entity, ['title' => 'Updated']);
        $this->assertNotFalse($result);
        $this->assertEquals('Updated', $result->title);
        $this->assertDatabaseHas('test_items', ['title' => 'Updated']);
    }

    public function test_delete()
    {
        $entity = $this->repository->create(['title' => 'Deletable']);

        $result = $this->repository->delete($entity);
        $this->assertTrue($result);
        $this->assertDatabaseMissing('test_items', ['title' => 'Deletable']);
    }

    public function test_all()
    {
        $this->repository->create(['title' => 'Item 1']);
        $this->repository->create(['title' => 'Item 2']);

        $all = $this->repository->all();
        $this->assertCount(2, $all);
    }

    public function test_count()
    {
        $this->repository->create(['title' => 'Item 1']);
        $this->repository->create(['title' => 'Item 2']);
        $this->repository->create(['title' => 'Item 3']);

        $count = $this->repository->count();
        $this->assertEquals(3, $count);
    }

    public function test_pluck()
    {
        $this->repository->create(['title' => 'Alpha']);
        $this->repository->create(['title' => 'Beta']);

        $plucked = $this->repository->pluck('title');
        $this->assertContains('Alpha', $plucked);
        $this->assertContains('Beta', $plucked);
    }

    public function test_paginate()
    {
        for ($i = 1; $i <= 25; $i++) {
            $this->repository->create(['title' => "Item $i"]);
        }

        $paginated = $this->repository->paginate(10);
        $this->assertEquals(10, $paginated->count());
        $this->assertEquals(25, $paginated->total());
    }

    public function test_save_creates_new()
    {
        $id = $this->repository->save(['title' => 'Saved New']);
        $this->assertNotFalse($id);
        $this->assertDatabaseHas('test_items', ['id' => $id, 'title' => 'Saved New']);
    }

    public function test_save_updates_existing()
    {
        $entity = $this->repository->create(['title' => 'Before Update']);
        $id = $this->repository->save(['id' => $entity->id, 'title' => 'After Update']);
        $this->assertEquals($entity->id, $id);
        $this->assertDatabaseHas('test_items', ['id' => $id, 'title' => 'After Update']);
    }

    public function test_find_by_id()
    {
        $entity = $this->repository->create(['title' => 'ById']);
        $found = $this->repository->findById($entity->id);
        $this->assertNotNull($found);
        $this->assertEquals('ById', $found->title);
    }

    public function test_get_model()
    {
        $model = $this->repository->getModel();
        $this->assertInstanceOf(TestModel::class, $model);
    }

    public function test_unify_params()
    {
        $params = [
            'orderby' => 'id desc',
            'nolimit' => true,
            'page' => 2,
            'count_paging' => true,
        ];

        $unified = TestRepository::unifyParams($params);

        $this->assertArrayHasKey('order_by', $unified);
        $this->assertArrayNotHasKey('orderby', $unified);
        $this->assertArrayHasKey('no_limit', $unified);
        $this->assertArrayNotHasKey('nolimit', $unified);
        $this->assertArrayHasKey('current_page', $unified);
        $this->assertArrayNotHasKey('page', $unified);
        $this->assertArrayHasKey('page_count', $unified);
        $this->assertArrayNotHasKey('count_paging', $unified);
    }
}