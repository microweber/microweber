<?php
namespace MicroweberPackages\Event\tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

class EventManagerServiceProviderTest extends TestCase
{
	#[Test]
	public function it_if_is_event_when_using(): void {
		$this->assertInstanceOf(\MicroweberPackages\Event\Event::class, app('event_manager'));
	}
}
