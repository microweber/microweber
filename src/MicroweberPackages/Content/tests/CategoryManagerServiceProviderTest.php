<?php
namespace MicroweberPackages\Cms\tests;

class CategoryManagerServiceProviderTest extends BaseTest
{
	public function testCategoryManagerWhenUsing() {
		$this->assertInstanceOf(\MicroweberPackages\Content\CategoryManager\CategoryManager::class, app('category_manager'));
	}
}