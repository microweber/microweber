<?php

class EventManagerServiceProviderTest extends BaseTest
{
	public function testEventManagerWhenUsing() {
		$this->assertInstanceOf(\MicroweberPackages\EventManager\Event::class, app('event_manager'));
	}
}