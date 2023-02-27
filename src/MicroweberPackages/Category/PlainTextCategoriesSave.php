<?php

namespace MicroweberPackages\Category;

use Illuminate\Support\Str;
use MicroweberPackages\Category\Models\Category;

class PlainTextCategoriesSave
{

    /**
     * Example input:
     *  $categoriesToSave = [];
        $categoriesToSave[] = 'Properties > Locations > City > Sofia > Dragalevci';
        $categoriesToSave[] = 'Properties > Locations > City > Sofia > Mladost';
        $categoriesToSave[] = 'Properties > Locations > City > Sofia > Nadejda';
     * @param array $categoriesToSave
     * @param $mainCategoryId
     * @return void
     */
    public function saveCategories(array $categoriesToSave, $mainCategoryId)
    {
        foreach ($categoriesToSave as $categoryTreePlain) {
            $categoriesToSave = app()->format->stringToTree($categoryTreePlain);
            $this->_addCategoryRecursive($categoriesToSave, $mainCategoryId);
        }
    }


    private function _addCategory($title, $parentId = 0)
    {
        $findOrNeCategoryQuery = Category::query();
        $findOrNeCategoryQuery->where('title', $title);
        if ($parentId > 0) {
            $findOrNeCategoryQuery->where('parent_id', $parentId);
        }
        $findOrNeCategory = $findOrNeCategoryQuery->first();

        if ($findOrNeCategory == null) {
            $findOrNeCategory = new Category();
        }

        $findOrNeCategory->title = $title;

        if ($parentId > 0) {
            $findOrNeCategory->parent_id = $parentId;
        }

        $findOrNeCategory->save();

        return $findOrNeCategory->id;
    }

    private function _addCategoryRecursive($array, $parentId = 0)
    {
        if (is_array($array)) {
            foreach ($array as $categoryName=>$categoryChildren) {
                $parentId = $this->_addCategory($categoryName, $parentId);
                if (!empty($categoryChildren)) {
                    $this->_addCategoryRecursive($categoryChildren, $parentId);
                }
            }
        }
    }

    private function parseGoogleTaxonomy($content)
    {
        // Add google categories
        $categories = [];
        foreach (explode(PHP_EOL, $content) as $row) {
            if (Str::contains($row, 'Google_Product_Taxonomy_Version')) {
                continue;
            }
            $expRow = explode(' - ', $row);
            if (isset($expRow[1])) {
                $categories[] = $expRow[1];
            }
        }

        return $categories;
    }

}
