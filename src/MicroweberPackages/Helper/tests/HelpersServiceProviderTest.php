<?php
namespace MicroweberPackages\Helper\tests;

use MicroweberPackages\Core\tests\TestCase;

class HelpersServiceProviderTest extends TestCase
{
	public function testFormatWhenUsing(){

		$this->assertInstanceOf(\MicroweberPackages\Helper\Format::class, app('format'));
	}

	public function testXSSSecurityWhenUsing(){

		$this->assertInstanceOf(\MicroweberPackages\Helper\XSSSecurity::class, app('xss_security'));
	}

}
