<?php
namespace MicroweberPackages\Content\CategoryManager\tests;

use MicroweberPackages\Core\tests\TestCase;

class CategoryManagerTest extends TestCase
{
    public function testCategoryTree()
    {
        $tree = category_tree('all=1');
    }

}