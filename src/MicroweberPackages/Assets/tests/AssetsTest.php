<?php
namespace MicroweberPackages\Assets\tests;

use MicroweberPackages\Core\tests\TestCase;

class AssetsTest extends TestCase
{
    public function testAddGroup()
    {
        $assetsAll = app()->assets->all();
        $this->assertEmpty($assetsAll);

        $randGroup = rand(1111,9999).time();
        app()->assets->group($randGroup)->add('neshto.js');


        $assetsAll = app()->assets->group($randGroup)->all();

        dd($assetsAll);
    }
}
