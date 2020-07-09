<?php
namespace MicroweberPackages\Helpers\tests;

class HelpersServiceProviderTest extends BaseTest
{
	public function testFormatWhenUsing(){

		$this->assertInstanceOf(\MicroweberPackages\Helpers\Format::class, app('format'));
	}
	
	public function testXSSSecurityWhenUsing(){

		$this->assertInstanceOf(\MicroweberPackages\Helpers\XSSSecurity::class, app('xss_security'));
	}

}