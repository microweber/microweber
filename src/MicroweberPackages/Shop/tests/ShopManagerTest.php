<?php
namespace MicroweberPackages\Shop\tests;

use MicroweberPackages\Core\tests\TestCase;

class ShopManagerTest extends TestCase
{
    public function testCategoryTree()
    {
        $tree = category_tree('all=1');
    }

}