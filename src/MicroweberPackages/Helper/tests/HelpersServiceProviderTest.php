<?php
namespace MicroweberPackages\Helper\tests;

use PHPUnit\Framework\Attributes\Test;

use MicroweberPackages\Core\tests\TestCase;

class HelpersServiceProviderTest extends TestCase
{
	#[Test]
	public function it_format_when_using(): void {

		$this->assertInstanceOf(\MicroweberPackages\Helper\Format::class, app('format'));
	}

	#[Test]

	public function it_x_s_s_security_when_using(): void {

		$this->assertInstanceOf(\MicroweberPackages\Helper\XSSSecurity::class, app('xss_security'));
	}

}
