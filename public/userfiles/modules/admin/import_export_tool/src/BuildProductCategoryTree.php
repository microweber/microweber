<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool;

class BuildProductCategoryTree
{
    public $categoryTreeItems;
    public $productCategories;

    public function __construct($categoryTreeItems, $productCategories)
    {
        $this->categoryTreeItems = $categoryTreeItems;
        $this->productCategories = $productCategories;
    }

    public function get()
    {
        $productIds = [];

        foreach ($this->productCategories as $categoryItem) {
            if (isset($categoryItem['category'])) {
                $productIds[] = $categoryItem['category']['id'];
            }
        }

        $productCategoriesPlainText = [];

        foreach($this->categoryTreeItems as $categoryTreeItem) {

            $tree = new BuildCategoryTree($categoryTreeItem);
            $getTree = $tree->get();

            if (isset($getTree['ids'])) {

                $founded = false;
                foreach ($getTree['ids'] as $treeId) {
                    if (in_array($treeId, $productIds)) {
                        $founded = true;
                    }
                }
                if ($founded) {
                    $productCategoriesPlainText[] = $getTree;
                }
            }
        }

        return $productCategoriesPlainText;
    }

}

