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
            $productIds[] = $categoryItem['category']['id'];
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

class BuildCategoryTree {

    public $txt = '';
    public $ids = [];
    public $category = [];

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function build($category)
    {
        if (isset($category['childs'])) {
            foreach ($category['childs'] as $child) {
                $this->txt .= ' > ' . $child['title'];
                $this->ids[] = $child['id'];
                if (isset($child['childs']) && !empty($child['childs'])) {
                    $this->build($child);
                }
            }
        }
    }

    public function get()
    {
        $this->txt .= $this->category['title'];
        $this->ids[] = $this->category['id'];

        $this->build($this->category);

        return ['plain'=>$this->txt,'ids'=>$this->ids];
    }
}
