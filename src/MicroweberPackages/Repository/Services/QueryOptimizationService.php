<?php

namespace MicroweberPackages\Repository\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Service for optimizing database queries and preventing N+1 issues
 */
class QueryOptimizationService
{
    /**
     * Batch load categories for multiple content IDs to prevent N+1 queries
     *
     * @param array $contentIds Array of content IDs
     * @return array Associative array with content_id as key and categories as value
     */
    public static function batchLoadCategories(array $contentIds): array
    {
        if (empty($contentIds)) {
            return [];
        }

        $contentIds = array_unique($contentIds);
        $cacheKey = 'batch_categories_' . md5(implode(',', $contentIds));

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($contentIds) {
            $categories = [];

            // Single query to get all category items for all content IDs
            $categoryItems = DB::table('categories_items')
                ->select('rel_id', 'parent_id')
                ->where('rel_type', morph_name(\Modules\Content\Models\Content::class))
                ->whereIn('rel_id', $contentIds)
                ->get()
                ->groupBy('rel_id');

            // Collect all unique category IDs
            $allCategoryIds = [];
            foreach ($categoryItems as $items) {
                foreach ($items as $item) {
                    $allCategoryIds[] = $item->parent_id;
                }
            }
            $allCategoryIds = array_unique($allCategoryIds);

            // Batch load all categories in a single query
            $categoriesData = [];
            if (!empty($allCategoryIds)) {
                $categoriesResult = DB::table('categories')
                    ->whereIn('id', $allCategoryIds)
                    ->get();

                foreach ($categoriesResult as $category) {
                    $categoriesData[$category->id] = (array) $category;
                }
            }

            // Map categories to content IDs
            foreach ($contentIds as $contentId) {
                $categories[$contentId] = [];
                if (isset($categoryItems[$contentId])) {
                    foreach ($categoryItems[$contentId] as $item) {
                        if (isset($categoriesData[$item->parent_id])) {
                            $categories[$contentId][] = $categoriesData[$item->parent_id];
                        }
                    }
                }
            }

            return $categories;
        });
    }

    /**
     * Batch load custom field values for multiple custom field IDs
     *
     * @param array $customFieldIds Array of custom field IDs
     * @return array Associative array with custom_field_id as key and values as value
     */
    public static function batchLoadCustomFieldValues(array $customFieldIds): array
    {
        if (empty($customFieldIds)) {
            return [];
        }

        $customFieldIds = array_unique($customFieldIds);
        $cacheKey = 'batch_cf_values_' . md5(implode(',', $customFieldIds));

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($customFieldIds) {
            $values = [];

            $results = DB::table('custom_fields_values')
                ->select('custom_field_id', 'value', 'position')
                ->whereIn('custom_field_id', $customFieldIds)
                ->get()
                ->groupBy('custom_field_id');

            foreach ($customFieldIds as $fieldId) {
                $values[$fieldId] = $results->get($fieldId, collect())->toArray();
            }

            return $values;
        });
    }

    /**
     * Load category tree with optimized single-query approach
     *
     * @return array
     */
    public static function loadCategoryTreeOptimized(): array
    {
        $cacheKey = 'category_tree_optimized';

        return Cache::remember($cacheKey, now()->addHours(1), function () {
            // Load all categories in a single query
            $allCategories = DB::table('categories')
                ->where('data_type', 'category')
                ->orderBy('position')
                ->get()
                ->keyBy('id');

            // Build tree structure
            $tree = [];
            $childrenMap = [];

            foreach ($allCategories as $category) {
                $category->childs = [];
                $parentId = $category->parent_id ?? 0;

                if (!isset($childrenMap[$parentId])) {
                    $childrenMap[$parentId] = [];
                }
                $childrenMap[$parentId][] = (array) $category;
            }

            // Recursively build tree starting from root
            $buildTree = function ($parentId) use (&$buildTree, $childrenMap) {
                $children = $childrenMap[$parentId] ?? [];
                foreach ($children as &$child) {
                    $child['childs'] = $buildTree($child['id']);
                }
                return $children;
            };

            return $buildTree(0);
        });
    }

    /**
     * Clear query optimization caches
     */
    public static function clearCaches(): void
    {
        // Clear caches by tag or pattern if using cache tags
        // Otherwise, individual cache entries will expire naturally
    }
}
