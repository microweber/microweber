<?php
namespace MicroweberPackages\Helper\tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

class HelpersServiceProviderTest extends TestCase
{
	#[Test]
	public function it_format_when_using(): void {

		$this->assertInstanceOf(\MicroweberPackages\Format\Format::class, app('format'));
	}

	#[Test]

	public function it_x_s_s_security_when_using(): void {

		$this->assertInstanceOf(\MicroweberPackages\Security\XSSSecurity::class, app('xss_security'));
	}

}
