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
        $productCategoriesPlainText = [];

        foreach($this->categoryTreeItems as $categoryTreeItem) {
            $tree = new BuildCategoryTree($categoryTreeItem);
            $productCategoriesPlainText[] = $tree->get();
        }

        return $productCategoriesPlainText;
    }

}

class BuildCategoryTree {

    public $txt = '';
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
                if (isset($child['childs']) && !empty($child['childs'])) {
                    $this->build($child);
                }
            }
        }
    }

    public function get()
    {
        $this->txt .= $this->category['title'];
        $this->build($this->category);

        return $this->txt;
    }
}
