<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Content\Models\Content;
use Modules\Category\Models\Category;
use Modules\Category\Models\CategoryItem;
use Modules\ContentData\Models\ContentData;
use Modules\CustomFields\Models\CustomField;
use Modules\CustomFields\Models\CustomFieldValue;

class DatabaseOptimizationTest extends TestCase
{
    private function requireTables(array $tables): bool
    {
        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                $this->markTestSkipped("Required table '{$table}' is not available in current test environment");
                return false;
            }
        }

        return true;
    }

    /**
     * Test that database indexes exist on critical tables
     */
    public function test_content_data_indexes_exist()
    {
        if (!$this->requireTables(['content_data'])) {
            return;
        }

        $this->assertTrue(
            Schema::hasIndex('content_data', 'content_data_rel_lookup_index'),
            'Composite index on content_data (rel_type, rel_id, field_name) should exist'
        );
        $this->assertTrue(
            Schema::hasIndex('content_data', 'content_data_rel_type_index'),
            'Index on content_data.rel_type should exist'
        );
        $this->assertTrue(
            Schema::hasIndex('content_data', 'content_data_rel_id_index'),
            'Index on content_data.rel_id should exist'
        );
    }

    public function test_custom_fields_values_indexes_exist()
    {
        if (!$this->requireTables(['custom_fields_values'])) {
            return;
        }

        $this->assertTrue(
            Schema::hasIndex('custom_fields_values', 'custom_fields_values_custom_field_id_index'),
            'Index on custom_fields_values.custom_field_id should exist'
        );
    }

    public function test_cart_indexes_exist()
    {
        if (!$this->requireTables(['cart'])) {
            return;
        }

        $this->assertTrue(
            Schema::hasIndex('cart', 'cart_session_id_index'),
            'Index on cart.session_id should exist'
        );
        $this->assertTrue(
            Schema::hasIndex('cart', 'cart_session_order_completed_index'),
            'Composite index on cart (session_id, order_completed) should exist'
        );
        $this->assertTrue(
            Schema::hasIndex('cart', 'cart_order_id_index'),
            'Index on cart.order_id should exist'
        );
    }

    public function test_categories_indexes_exist()
    {
        if (!$this->requireTables(['categories'])) {
            return;
        }

        $this->assertTrue(
            Schema::hasIndex('categories', 'categories_parent_id_index'),
            'Index on categories.parent_id should exist'
        );
        $this->assertTrue(
            Schema::hasIndex('categories', 'categories_parent_data_type_index'),
            'Composite index on categories (parent_id, data_type) should exist'
        );
    }

    /**
     * Test batch category loading prevents N+1
     */
    public function test_batch_category_loading_prevents_n_plus_one()
    {
        if (!$this->requireTables(['categories', 'categories_items', 'content'])) {
            return;
        }

        // Create test content items with categories
        $contentIds = [];
        $categoryIds = [];

        // Create 5 categories
        for ($i = 1; $i <= 5; $i++) {
            $category = Category::create([
                'title' => "Test Category $i",
                'data_type' => 'category',
                'parent_id' => 0,
                'is_active' => 1,
            ]);
            $categoryIds[] = $category->id;
        }

        // Create 10 content items
        for ($i = 1; $i <= 10; $i++) {
            $content = Content::create([
                'title' => "Test Content $i",
                'content_type' => 'page',
                'is_active' => 1,
            ]);
            $contentIds[] = $content->id;

            // Associate content with random categories
            \DB::table('categories_items')->insert([
                'rel_type' => morph_name(Content::class),
                'rel_id' => $content->id,
                'parent_id' => $categoryIds[($i - 1) % count($categoryIds)],
            ]);
        }

        // Enable query logging
        DB::enableQueryLog();

        // Test batch loading
        $categories = app()->content_repository->getCategories($contentIds[0]);

        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        // Should use batch loading (max 2 queries: category_items + categories)
        $this->assertLessThanOrEqual(
            2,
            count($queries),
            'Batch loading should use at most 2 queries, not N+1'
        );

        // Cleanup
        Content::whereIn('id', $contentIds)->delete();
        Category::whereIn('id', $categoryIds)->delete();
        CategoryItem::whereIn('rel_id', $contentIds)->delete();
    }

    /**
     * Test batch custom field loading prevents N+1
     */
    public function test_batch_custom_field_loading_prevents_n_plus_one()
    {
        if (!$this->requireTables(['content', 'custom_fields', 'custom_fields_values'])) {
            return;
        }

        // Create test content with custom fields
        $content = Content::create([
            'title' => 'Test Content With Custom Fields',
            'content_type' => 'product',
            'is_active' => 1,
        ]);

        // Create 5 custom fields with values
        $customFieldIds = [];
        for ($i = 1; $i <= 5; $i++) {
            $cf = CustomField::create([
                'rel_type' => morph_name(Content::class),
                'rel_id' => $content->id,
                'name' => "Field $i",
                'type' => 'text',
            ]);
            $customFieldIds[] = $cf->id;

            CustomFieldValue::create([
                'custom_field_id' => $cf->id,
                'value' => "Value $i",
            ]);
        }

        // Enable query logging
        DB::enableQueryLog();

        // Load custom fields
        $customFields = app()->content_repository->getCustomFields($content->id);

        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        // Should use batch loading (max 2 queries: custom_fields + custom_fields_values)
        $this->assertLessThanOrEqual(
            2,
            count($queries),
            'Custom field batch loading should use at most 2 queries, not N+1'
        );

        // Cleanup
        CustomFieldValue::whereIn('custom_field_id', $customFieldIds)->delete();
        CustomField::whereIn('id', $customFieldIds)->delete();
        Content::find($content->id)?->delete();
    }

    /**
     * Test AbstractRepository handles array IDs efficiently
     */
    public function test_abstract_repository_handles_array_ids_efficiently()
    {
        if (!$this->requireTables(['content'])) {
            return;
        }

        // Create test content items
        $contentIds = [];
        for ($i = 1; $i <= 5; $i++) {
            $content = Content::create([
                'title' => "Test Content $i",
                'content_type' => 'page',
                'is_active' => 1,
            ]);
            $contentIds[] = $content->id;
        }

        // Enable query logging
        DB::enableQueryLog();

        // Test batch loading with array of IDs
        $items = app()->content_repository->getById($contentIds);

        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        // Should use single whereIn query, not individual queries
        $this->assertEquals(
            1,
            count($queries),
            'Array IDs should be loaded in single query using whereIn'
        );

        // Should return all items
        $this->assertCount(5, $items);

        // Cleanup
        Content::whereIn('id', $contentIds)->delete();
    }
}
