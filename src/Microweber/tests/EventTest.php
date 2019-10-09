<?php

namespace Microweber\tests;

/**
 * Run test
 * @author Bobi Slaveykvo Microweber
 * @command php phpunit.phar --filter EventTest
 */
class EventTest extends TestCase
{
    public function testBind()
    {
        $unitTest = $this;

        event_bind('some_event', function($params) use ($unitTest) {
           $unitTest->assertArrayHasKey('wow1', $params);
        });

        event_trigger('some_event', array('wow1'=>'waw!1'));


        event_bind('some_event2', function($params) use ($unitTest) {
            $unitTest->assertArrayHasKey('wow2', $params);
        });

        event_trigger('some_event2', array('wow2'=>'waw!2'));
    }
}