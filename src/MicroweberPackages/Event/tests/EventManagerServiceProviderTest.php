<?php
namespace MicroweberPackages\Event\EventManager\tests;

class EventManagerServiceProviderTest extends BaseTest
{
	public function testIfIsEventWhenUsing(){
		$this->assertInstanceOf(\MicroweberPackages\Event\Event::class, app('event_manager'));
	}
}
