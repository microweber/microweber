<?php
namespace MicroweberPackages\Core\EventManager\tests;

class EventManagerServiceProviderTest extends BaseTest
{
	public function testEventManagerWhenUsing() {
		$this->assertInstanceOf(\MicroweberPackages\Core\EventManager\Event::class, app('event_manager'));
	}
}