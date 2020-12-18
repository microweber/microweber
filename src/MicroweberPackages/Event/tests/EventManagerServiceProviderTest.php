<?php
namespace MicroweberPackages\Event\EventManager\tests;

class EventManagerServiceProviderTest extends BaseTest
{
	public function testEventManagerWhenUsing() {
		$this->assertInstanceOf(\MicroweberPackages\Event\Event::class, app('event_manager'));
	}
}