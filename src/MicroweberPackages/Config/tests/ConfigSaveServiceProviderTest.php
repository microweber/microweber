<?php
namespace MicroweberPackages\Config\tests;

use MicroweberPackages\Core\tests\TestCase;

class ConfigSaveServiceProviderTest extends TestCase
{
	public function testConfigIsConfigSaveWhenUsing(){

		$this->assertInstanceOf(\MicroweberPackages\Config\ConfigSave::class, app('Config'));
	}

}
