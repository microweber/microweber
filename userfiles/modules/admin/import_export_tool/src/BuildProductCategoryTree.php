<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool;

class BuildProductCategoryTree
{
    public $productCategoriesPlainText = [];
    public $categoryTreeItems;
    public $productCategories;

    public function __construct($categoryTreeItems, $productCategories)
    {
        $this->categoryTreeItems = $categoryTreeItems;
        $this->productCategories = $productCategories;
    }
    public function get()
    {
        dd($this->categoryTreeItems);
    }
}

class BuildCategoryTree {

}
