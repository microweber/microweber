<?php
namespace MicroweberPackages\Shop\tests;

class ShopManagerTest extends BaseTest
{
    public function testCategoryTree()
    {
        $tree = category_tree('all=1');
    }

}