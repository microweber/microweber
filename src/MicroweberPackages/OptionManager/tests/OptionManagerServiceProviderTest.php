<?php

class OptionManagerServiceProviderTest extends BaseTest
{
	public function testOptionManagerWhenUsing() {
		$this->assertInstanceOf(\MicroweberPackages\OptionManager\OptionManager::class, app('option_manager'));
	}
}