<?php

namespace MicroweberPackages\Core\tests;


/**
 * @runTestsInSeparateProcesses
 */
class TestArtisanCommand extends TestCase
{
    public function testIfArtisanCommandReturnErrors()
    {

        $res = \Artisan::call('config:cache');
        $this->assertEquals(0, $res);

        $res = \Artisan::call('config:clear');
        $this->assertEquals(0, $res);

        $res = \Artisan::call('cache:clear');
        $this->assertEquals(0, $res);

        $res = \Artisan::call('view:clear');
        $this->assertEquals(0, $res);

        $res = \Artisan::call('route:list');
        $this->assertEquals(0, $res);

        $res = \Artisan::call('route:cache');
        $this->assertEquals(0, $res);

        $res = \Artisan::call('route:clear');
        $this->assertEquals(0, $res);


    }


}
