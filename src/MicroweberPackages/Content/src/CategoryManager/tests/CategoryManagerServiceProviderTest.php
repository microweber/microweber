<?php
namespace MicroweberPackages\Content\tests;

use MicroweberPackages\Content\CategoryManager\CategoryManager;

class CategoryManagerServiceProviderTest extends BaseTest
{
    public function testCategoryManagerWhenUsing() {
        $this->assertInstanceOf(CategoryManager::class, app('category_manager'));
    }
}