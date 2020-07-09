<?php
namespace MicroweberPackages\Cms\tests;

class CategoryManagerTest extends BaseTest
{
    public function testCategoryTree()
    {
        $tree = category_tree('all=1');
    }

}