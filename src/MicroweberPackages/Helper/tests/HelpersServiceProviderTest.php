<?php
namespace MicroweberPackages\Helper\tests;

class HelpersServiceProviderTest extends BaseTest
{
	public function testFormatWhenUsing(){

		$this->assertInstanceOf(\MicroweberPackages\Helper\Format::class, app('format'));
	}
	
	public function testXSSSecurityWhenUsing(){

		$this->assertInstanceOf(\MicroweberPackages\Helper\XSSSecurity::class, app('xss_security'));
	}

}