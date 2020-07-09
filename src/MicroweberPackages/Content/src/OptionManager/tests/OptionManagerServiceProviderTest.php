<?php
namespace MicroweberPackages\Content\OptionManager\tests;

class OptionManagerServiceProviderTest extends BaseTest
{
	public function testOptionManagerWhenUsing() {
		$this->assertInstanceOf(\MicroweberPackages\Content\OptionManager\OptionManager::class, app('option_manager'));
	}
}