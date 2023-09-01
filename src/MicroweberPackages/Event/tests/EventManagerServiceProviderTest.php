<?php
namespace MicroweberPackages\Event\EventManager\tests;

use MicroweberPackages\Core\tests\TestCase;

class EventManagerServiceProviderTest extends TestCase
{
	public function testIfIsEventWhenUsing(){
		$this->assertInstanceOf(\MicroweberPackages\Event\Event::class, app('event_manager'));
	}
}
