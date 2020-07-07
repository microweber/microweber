<?php
namespace MicroweberPackages\Shop\tests;

class ShopManagerServiceProviderTest extends BaseTest
{
	public function testShopManagerWhenUsing() {
		$this->assertInstanceOf(\MicroweberPackages\ShopManager\ShopManager::class, app('shop_manager'));
	}
}