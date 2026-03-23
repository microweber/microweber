<?php

namespace Modules\Category\Repositories;

use Illuminate\Support\Facades\DB;

/**
 * Optimized category tree loading to prevent N+1 queries
 * These methods can be used as replacements for the recursive methods
 */
trait CategoryRepositoryOptimized
{
    /**
     * Build complete category tree with all children in a single query
     *
     * @return array|false
     */
    public function treeOptimized()
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {
            // Load all categories in a single query
            $allCategories = DB::table('categories')
                ->where('data_type', 'category')
                ->orderBy('position')
                ->get()
                ->keyBy('id');

            if ($allCategories->isEmpty()) {
                return false;
            }

            // Build tree structure
            $tree = [];
            $childrenMap = [];

            // Build parent -> children mapping
            foreach ($allCategories as $category) {
                $parentId = $category->parent_id ?? 0;
                if (!isset($childrenMap[$parentId])) {
                    $childrenMap[$parentId] = [];
                }
                $categoryArray = (array) $category;
                $categoryArray['childs'] = [];
                $childrenMap[$parentId][] = &$categoryArray;
            }

            // Build tree recursively
            $buildTree = function ($parentId) use (&$buildTree, $childrenMap) {
                $result = [];
                if (isset($childrenMap[$parentId])) {
                    foreach ($childrenMap[$parentId] as &$child) {
                        $child['childs'] = $buildTree($child['id']);
                        $result[] = $child;
                    }
                }
                return $result;
            };

            $tree = $buildTree(0);

            return !empty($tree) ? $tree : false;
        });
    }

    /**
     * Get child tree for a specific category with optimized loading
     *
     * @param mixed $categoryId
     * @return array|false
     */
    public function getChildsTreeOptimized($categoryId)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($categoryId) {
            // Load all relevant categories in a single query
            $allCategories = DB::table('categories')
                ->where('data_type', 'category')
                ->orderBy('position')
                ->get()
                ->keyBy('id');

            if ($allCategories->isEmpty()) {
                return false;
            }

            // Get IDs of categories to include (parent and its descendants)
            $idsToInclude = is_array($categoryId) ? $categoryId : [$categoryId];
            $this->collectDescendantIds($allCategories, $idsToInclude);

            // Filter to only included categories
            $filteredCategories = $allCategories->filter(function ($category) use ($idsToInclude) {
                return in_array($category->id, $idsToInclude);
            });

            // Build tree from filtered categories
            $tree = $this->buildTreeFromCategories($filteredCategories, is_array($categoryId) ? $categoryId[0] : $categoryId);

            return $tree ?: false;
        });
    }

    /**
     * Recursively collect all descendant category IDs
     *
     * @param \Illuminate\Support\Collection $categories
     * @param array $ids
     * @return void
     */
    protected function collectDescendantIds($categories, array &$ids)
    {
        $found = false;
        foreach ($categories as $category) {
            if (in_array($category->parent_id, $ids) && !in_array($category->id, $ids)) {
                $ids[] = $category->id;
                $found = true;
            }
        }

        // Keep recursing until no new IDs are found
        if ($found) {
            $this->collectDescendantIds($categories, $ids);
        }
    }

    /**
     * Build tree structure from categories collection
     *
     * @param \Illuminate\Support\Collection $categories
     * @param int $parentId
     * @return array|false
     */
    protected function buildTreeFromCategories($categories, $parentId)
    {
        $tree = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $categoryArray = (array) $category;
                $categoryArray['childs'] = $this->buildTreeFromCategories($categories, $category->id);
                $tree[] = $categoryArray;
            }
        }

        return $tree ?: false;
    }
}
