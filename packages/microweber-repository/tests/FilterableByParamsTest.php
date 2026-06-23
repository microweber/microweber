<?php

namespace MicroweberPackages\Repository\Tests;

use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Repository\Tests\Stubs\TestModel;
use MicroweberPackages\Repository\Traits\FilterableByParams;

class FilterableByParamsTest extends TestCase
{
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
    }

    public function test_unify_filter_params()
    {
        $params = [
            'orderby' => 'id desc',
            'nolimit' => true,
            'page' => 3,
            'count_paging' => true,
        ];

        $unified = FilterableByParams::unifyFilterParams($params);

        $this->assertArrayHasKey('order_by', $unified);
        $this->assertArrayHasKey('no_limit', $unified);
        $this->assertArrayHasKey('current_page', $unified);
        $this->assertEquals(3, $unified['current_page']);
    }

    public function test_filter_by_params_with_limit()
    {
        for ($i = 1; $i <= 10; $i++) {
            TestModel::create(['title' => "Item $i", 'position' => $i]);
        }

        $results = TestModel::filterByParams(['limit' => 5])->get();
        $this->assertCount(5, $results);
    }

    public function test_filter_by_params_no_limit()
    {
        for ($i = 1; $i <= 50; $i++) {
            TestModel::create(['title' => "Item $i", 'position' => $i]);
        }

        $results = TestModel::filterByParams(['no_limit' => true])->get();
        $this->assertCount(50, $results);
    }

    public function test_filter_by_params_order_by()
    {
        TestModel::create(['title' => 'B', 'position' => 2]);
        TestModel::create(['title' => 'A', 'position' => 1]);
        TestModel::create(['title' => 'C', 'position' => 3]);

        $results = TestModel::filterByParams([
            'order_by' => 'position asc',
            'no_limit' => true,
        ])->get();

        $this->assertEquals('A', $results->first()->title);
        $this->assertEquals('C', $results->last()->title);
    }

    public function test_filter_by_params_exclude_ids()
    {
        $a = TestModel::create(['title' => 'A']);
        $b = TestModel::create(['title' => 'B']);
        $c = TestModel::create(['title' => 'C']);

        $results = TestModel::filterByParams([
            'exclude_ids' => "{$a->id},{$b->id}",
            'no_limit' => true,
        ])->get();

        $this->assertCount(1, $results);
        $this->assertEquals('C', $results->first()->title);
    }

    public function test_filter_by_params_include_ids()
    {
        $a = TestModel::create(['title' => 'A']);
        $b = TestModel::create(['title' => 'B']);
        TestModel::create(['title' => 'C']);

        $results = TestModel::filterByParams([
            'ids' => "{$a->id},{$b->id}",
            'no_limit' => true,
        ])->get();

        $this->assertCount(2, $results);
    }

    public function test_filter_by_params_select_fields()
    {
        TestModel::create(['title' => 'WithFields', 'status' => 'active', 'description' => 'Some desc']);

        $results = TestModel::filterByParams([
            'fields' => 'title,status',
            'no_limit' => true,
        ])->get();

        $first = $results->first();
        $this->assertEquals('WithFields', $first->title);
        $this->assertEquals('active', $first->status);
    }

    public function test_filter_by_params_group_by()
    {
        TestModel::create(['title' => 'A', 'status' => 'active']);
        TestModel::create(['title' => 'B', 'status' => 'active']);
        TestModel::create(['title' => 'C', 'status' => 'draft']);

        // SQLite requires that selected columns are in GROUP BY or are aggregates
        $results = TestModel::filterByParams([
            'group_by' => 'status',
            'fields' => 'status',
            'no_limit' => true,
        ])->get();

        $this->assertCount(2, $results);
    }

    public function test_filter_by_params_closure()
    {
        TestModel::create(['title' => 'A', 'position' => 1]);
        TestModel::create(['title' => 'B', 'position' => 5]);
        TestModel::create(['title' => 'C', 'position' => 10]);

        $results = TestModel::filterByParams([
            'custom_filter' => function ($query) {
                return $query->where('position', '>', 3);
            },
            'no_limit' => true,
        ])->get();

        $this->assertCount(2, $results);
    }
}