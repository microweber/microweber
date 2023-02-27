<?php

namespace MicroweberPackages\Admin\tests;

use MicroweberPackages\Core\tests\TestCase;

class AdminTest extends TestCase
{
    public function testAdmin()
    {
        $this->assertTrue(true);
    }

    public function testAdminManagerFacade()
    {

        \MicroweberPackages\Admin\Facades\AdminManager::addScript('test-my-module-admin-js', 'test.js', ['test' => 1]);
        \MicroweberPackages\Admin\Facades\AdminManager::addStyle('test-module-admin-css', 'test.css', ['test' => 2]);
        \MicroweberPackages\Admin\Facades\AdminManager::addCustomHeadTag('<script>alert("ok")</script>');

        $scrips = \MicroweberPackages\Admin\Facades\AdminManager::scripts();
        $styles = \MicroweberPackages\Admin\Facades\AdminManager::styles();
        $custom = \MicroweberPackages\Admin\Facades\AdminManager::customHeadTags();

        $this->assertStringContainsString('<script id="test-my-module-admin-js" src="test.js" test="1"></script>', $scrips);
        $this->assertStringContainsString('<link rel="stylesheet" id="test-module-admin-css" href="test.css" type="text/css" test="2" />', $styles);
        $this->assertStringContainsString('<script>alert("ok")</script>', $custom);

        $all = \MicroweberPackages\Admin\Facades\AdminManager::headTags();

        $this->assertStringContainsString('<script id="test-my-module-admin-js" src="test.js" test="1"></script>', $all);
        $this->assertStringContainsString('<link rel="stylesheet" id="test-module-admin-css" href="test.css" type="text/css" test="2" />', $all);
        $this->assertStringContainsString('<script>alert("ok")</script>', $all);



    }
}
