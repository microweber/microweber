<?php
namespace MicroweberPackages\Modules\Video\tests;

use MicroweberPackages\Core\tests\TestCase;

class VideoTestBackend extends TestCase
{
    public function testModule()
    {
        $resp = mw()->module('video');
        $this->assertIsObject($resp);


    }
}
