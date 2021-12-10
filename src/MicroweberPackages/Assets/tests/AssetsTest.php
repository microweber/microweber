<?php
namespace MicroweberPackages\Assets\tests;

use MicroweberPackages\Core\tests\TestCase;

class AssetsTest extends TestCase
{
    public function testAllClassMethods()
    {
        // Must be empty
        $assetsAll = app()->assets->all();
        $this->assertEmpty($assetsAll);

        // Now put some js in group
        $randGroup = rand(1111, 9999) . time();
        app()->assets->group($randGroup)->add('something.js');
        app()->assets->group($randGroup)->add(['something-new.js', 'something.css']);

        // Again must be empty
        $assetsAll = app()->assets->all();
        $this->assertEmpty($assetsAll);

        $assetsAll = app()->assets->group($randGroup)->all();
        $this->assertNotEmpty($assetsAll);
        $this->assertTrue(strpos($assetsAll, 'something.js') !== false);
        $this->assertTrue(strpos($assetsAll, 'something-new.js') !== false);
        $this->assertTrue(strpos($assetsAll, 'something.css') !== false);

        // After call group function must be autocleared on new instance
        $assetsAll = app()->assets->all();
        $this->assertEmpty($assetsAll); // This must be empty

    }

    public function testAssetsFunctions() {

        // Must be empty
        $assetsAll = assets_all();
        $this->assertEmpty($assetsAll);

        // Now put some js in group
        $randGroup = rand(1111, 9999) . time();
        assets_add('something.js', $randGroup);
        assets_add(['something-new.js', 'something.css'], $randGroup);

        // Again must be empty
        $assetsAll = assets_all();
        $this->assertEmpty($assetsAll);

        $assetsAll = assets_all($randGroup);
        $this->assertNotEmpty($assetsAll);
        $this->assertTrue(strpos($assetsAll, 'something.js') !== false);
        $this->assertTrue(strpos($assetsAll, 'something-new.js') !== false);
        $this->assertTrue(strpos($assetsAll, 'something.css') !== false);

        // After call group function must be autocleared on new instance
        $assetsAll = assets_all();
        $this->assertEmpty($assetsAll); // This must be empty
    }
}
